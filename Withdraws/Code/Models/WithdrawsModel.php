<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Withdraws\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Finance\Payments\Code\Models\PaymentsModel;
use Finance\Transactions\Code\Models\TransactionsModel;
use Subscriptions\Subscriptions\Code\Classes\Subscriber;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class WithdrawsModel extends BaseModel {

    //put your code here

    public function getQueryBuilder() {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        if (WEB_IS_ADMIN) {
            $user_id = $this->request->get('user_id');
        } else {
            $user_id = $user->id;
        }

        $query = parent::getQueryBuilder('#__finance_withdraws', 'fw');

        if ($user_id) {
            $query->where('fw.user_id=' . $user_id);
        }

        return $query;
    }

    public function save($form) {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        $form = $this->request->get('form');

        if (!WEB_IS_ADMIN) {

            $url = $this->generateUrl('finance.withdraws');

            $form['user_id'] = $user->id;
            $form['return_url'] = base64_encode($url);
        }

        $subscriber = $this->getSubscriber($form['user_id']);
        $total_amount = $subscriber->money_in - $subscriber->money_out;

        $params = $this->getInvoice();
        $params = array_reverse($params);
        $total_param = $params[0];
        unset($params[0]);

        if ($total_param->amount > $total_amount) {
            $factory->enqueueMessage('Amount to be withdrawn (' . $total_param->amount . ') is more than Available Amount (' . $total_amount . ')');
            return false;
        }

        $form['gateway_id'] = (isset($subscriber->subscription->gateway_id)) ? $subscriber->subscription->gateway_id : 0;
        // $form['amount'] = ($total_param->amount) ? $total_param->amount : 0;

        $withdraw_id = parent::save($form);

        if ($withdraw_id) {
            $this->sendEmail($form['user_id'], $withdraw_id);
            $this->getDeductCommission($form['user_id'], $withdraw_id, $params);
        }

        return $withdraw_id;
    }

    public function sendEmail($user_id, $withdraw_id) {

        $tmp_array = array();
        $email = new Email();
        $factory = new KazistFactory();

        $tmp_array['user'] = $factory->getRecord('#__users_users', 'uu', array('id=:id'), array('id' => $user_id));
        $tmp_array['withdraw'] = $factory->getRecord('#__finance_withdraws', 'fw', array('id=:id'), array('id' => $withdraw_id));

        $email->sendDefinedLayoutEmail('finance.withdraws.fundwithdraw', $tmp_array['user']->email, $tmp_array);
    }

    public function getDeductCommission($user_id, $withdraw_id, $params) {

        $factory = new KazistFactory();

        $parent_id = '';
        $user = $factory->getRecord('#__users_users', 'uu', array('uu.id=:id'), array('id' => $user_id));

        $params = array_reverse($params);

        if (!empty($params)) {
            foreach ($params as $key => $param) {

                $data_obj = new \stdClass();
                $data_obj->user_id = $user_id;
                $data_obj->behalf_user_id = $user_id;
                $data_obj->description = ($key) ? $param->title . ' For ' . $parent_id : 'Fund Withdrawal To ' . $user->username;
                $data_obj->item_id = $withdraw_id;
                $data_obj->rate_id = $param->id;
                $data_obj->source = 'withdraw';
                $data_obj->debit = $param->amount;
                $data_obj->type = 'fund-withdraw';

                $id = $factory->saveRecord('#__finance_transactions', $data_obj);

                if (!$key) {
                    $parent_id = $id;
                }
            }
        }
    }

    public function getWithdrawGateway($subscriber = '') {

        $user = (is_object($subscriber)) ? $subscriber : $this->getSubscriber();

        $query = new Query();
        $query->select('fg.*');
        $query->from('#__finance_gateways', 'fg');
        $query->where('fg.id=:id');
        $query->setParameter('id', (int) $user->subscription->gateway_id);
        $record = $query->loadObject();

        return $record;
    }

    public function getPaidInput() {

        $tmp_array = array();
        $tmp_array[] = array('value' =>'1', 'text' => 'Paid');
        $tmp_array[] = array('value' =>'0', 'text' => 'Not Paid');
        return $tmp_array;
    }

    public function getGetawaysInput() {

        $tmp_array = array();

        $query = new Query();
        $query->select('fg.*');
        $query->from('#__finance_gateways', 'fg');
        $query->where('fg.published=1');
        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->long_name . ' (' . $record->short_name . ')');
            }
        }

        return $tmp_array;
    }

    public function getWithdrawGateways($subscriber = '') {

        $tmp_array = array();
        $factory = new KazistFactory();

        $user = (is_object($subscriber)) ? $subscriber : $this->getSubscriber();


        $query = new Query();
        $query->select('fg.*');
        $query->from('#__finance_gateways', 'fg');
        $query->where('fg.id=:id');
        $query->setParameter('id', (int) $user->subscription->gateway_id);
        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->long_name . ' (' . $record->short_name . ')');
            }
        } else {
            $factory->enqueueMessage('No Withdraw Gateway Set. Kindly Edit You Subscription.', 'error');
        }

        return $tmp_array;
    }

    public function getInvoice() {

        $tmp_array = array();

        $factory = new KazistFactory();

        $form = $this->request->get('form', null, null);

        $paymentModel = new PaymentsModel();
        $rates = $paymentModel->getPaymentGatewayInvoiceRates($form['amount'], $form['gateway_id'], 'withdraw');

        return $rates;
    }

    public function getForm() {

        $factory = new KazistFactory();


        $form = $this->request->request->get('form', null, null);

        return $form;
    }

    public function getSubscriber($user_id = '') {

        $tmp_array = array();

        $factory = new KazistFactory();


        $user = $factory->getUser();

        $form = $this->request->request->get('form', null, null);

        if (!$user_id) {
            $user_id = (!WEB_IS_ADMIN) ? $user->id : $this->request->request->get('user_id');
        }
        $subscriber = new Subscriber();
        $subscriber_obj = $subscriber->getSubscriber($user_id, true, false);

        return $subscriber_obj;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  reverseWithdraw
    public function reverseWithdraw() {

        $factory = new KazistFactory();

        $transactionModel = new TransactionsModel();

        $id = $this->request->get('id');

        $transactionModel->reverseTransaction($id, 'withdraw');

        $data_obj = new \stdClass();
        $data_obj->id = $id;
        $data_obj->is_canceled = 1;
        $factory->saveRecord('#__finance_withdraws', $data_obj);
    }

}
