<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Masspays\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Subscriptions\Subscriptions\Code\Classes\Subscriber;
use Kazist\Service\Email\Email;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class MasspaysModel extends BaseModel {

//put your code here

    public $relative_path = '';
    public $web_path = '';
    public $absolute_path = '';
    public $file_path = '';
    public $file_is_unique = true;

    public function completeProcess() {

        $factory = new KazistFactory();



        $token = $this->request->request->get('token');

        $masspay_query = new Query();
        $masspay_query->update('#__finance_masspay');
        $masspay_query->where('token=:token');
        $masspay_query->setParameter('token', $token);
        $masspay_query->set('is_processed', '1');
        $masspay_query->execute();

        $withdraw_query = new Query();
        $withdraw_query->update('#__finance_withdraws');
        $withdraw_query->where('token=:token');
        $withdraw_query->setParameter('token', $token);
        $withdraw_query->set('paid_status', '1');
        $withdraw_query->execute();
    }

    public function generateMasspay() {

        $gateways = $this->getGateways();

        if (!empty($gateways)) {
            foreach ($gateways as $key => $gateway) {
                $this->processGatewayMasspay($gateway);
            }
        }
    }

    public function processGatewayMasspay($gateway) {

        $total_withdraws = $this->getTotalWithdraws($gateway->id);

        if ($total_withdraws) {

            $withdraws = $this->getWithdraws($gateway->id, $gateway->file_limit);

            $unique_id = substr(uniqid(), -7);
            $this->prepareFile($unique_id, $gateway->short_name, $gateway->file_type);
            file_put_contents($this->file_path, $gateway->withdraw_file_prefix . PHP_EOL, FILE_APPEND | LOCK_EX);

            foreach ($withdraws as $key => $withdraw) {
                $this->processSingleWithdrawal($withdraw, $gateway->withdraw_file_structure, $unique_id);
            }

            file_put_contents($this->file_path, $gateway->withdraw_file_suffix . PHP_EOL, FILE_APPEND | LOCK_EX);
            $masspay_id = $this->saveMassPay($gateway, $unique_id);

            $this->sendEmail($gateway, $masspay_id);

            if ($total_withdraws > $gateway->file_limit) {
                $this->processGatewayMasspay($gateway);
            }
        }
    }

    public function sendEmail($gateway, $masspay_id) {

        $email = new Email();
        $factory = new KazistFactory();

        $masspay = $factory->getRecord('#__finance_masspay', 'fm', array('id=:id'), array('id' => $masspay_id));
        $users = $factory->getRecord('#__users_users', 'uu', array('is_admin=:is_admin'), array('is_admin' => 1));
        $attachments = array($this->file_path);

        $tmp_array['gateway'] = $gateway;
        $tmp_array['masspay'] = $masspay;

        $email->sendDefinedLayoutEmail('finance.masspay.generatefile', $users, $tmp_array, $attachments);
    }

    public function saveMassPay($gateway, $unique_id) {

        $factory = new KazistFactory();

        $year = date('Y');
        $month = date('M');
        $date = date('d');

        $data_obj = new \stdClass();
        $data_obj->year = $year;
        $data_obj->month = $month;
        $data_obj->date = $date;
        $data_obj->type = $gateway->short_name;
        $data_obj->token = $unique_id;
        $data_obj->file = $this->web_path;
        $data_obj->max_limit = $gateway->file_limit;

        return $factory->saveRecord('#__finance_masspay', $data_obj);
    }

    public function processSingleWithdrawal($withdraw, $structure, $unique_id) {

        $tmp_array = array();

        $factory = new KazistFactory();
        $subscriber = new Subscriber();

        $structure_no_space = trim($structure, ' ');
        $structure = (strlen($structure_no_space)) ? $structure_no_space : '{{ user.name }}, {{ user.username }}, {{ user.email }}, {{ withdraw.amount }}, {{ user.id }}, {{ subscription.withdraw_id }}, {{ user.id }} ';

        $subscriber_obj = $subscriber->getUserSubscriptionInfo($withdraw->user_id);
        $user = $factory->getQueryBuilder('#__users_users', 'uu', array('id=:id'), array('id', $withdraw->user_id));

        $tmp_array['withdraw'] = $withdraw;
        $tmp_array['user'] = $user;
        $tmp_array['subscription'] = $subscriber_obj;

        $withdraw_str = $factory->renderString($tmp_array, $structure);

        file_put_contents($this->file_path, $withdraw_str . PHP_EOL, FILE_APPEND | LOCK_EX);

        $data_obj = new \stdClass();
        $data_obj->id = $withdraw->id;
        $data_obj->token = $unique_id;

        $factory->saveRecord('#__finance_withdraws', $data_obj);
    }

    public function getTotalWithdraws($gateway_id) {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('COUNT(*) as total');
        $query->from('#__finance_withdraws', 'fg');
        $query->where('(fg.token=\'\' OR fg.token IS NULL)');
        $query->andWhere('(fg.is_canceled=0 OR fg.is_canceled IS NULL)');
        if ($gateway_id) {
            $query->andWhere('fg.gateway_id=:gateway_id');
            $query->setParameter('gateway_id', $gateway_id);
        } else {
            $query->andWhere('1=-1');
        }

        $total = $query->loadResult();

        return $total;
    }

    public function getWithdraws($gateway_id, $limit) {

        $factory = new KazistFactory();


        $limit = ($limit) ? $limit : 2000;

        $query = new Query();
        $query->select('fg.*');
        $query->from('#__finance_withdraws', 'fg');
        $query->where('(fg.token=\'\' OR fg.token IS NULL)');
        $query->andWhere('(fg.is_canceled=0 OR fg.is_canceled IS NULL)');
        if ($gateway_id) {
            $query->andWhere('fg.gateway_id=:gateway_id');
            $query->setParameter('gateway_id', $gateway_id);
        } else {
            $query->andWhere('1=-1');
        }

        $query->setFirstResult(0);
        $query->setMaxResults($limit);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getGateways() {

        $factory = new KazistFactory();


        $query = new Query();
        $query->select('fg.*');
        $query->from('#__finance_gateways', 'fg');
        $query->where('fg.can_withdraw=1');

        $records = $query->loadObjectList();

        return $records;
    }

    public function prepareFile($unique_id, $file_name, $file_ext = 'txt') {

        $factory = new KazistFactory();

        $unique_id = ($this->file_is_unique) ? '-' . $unique_id : '';
        $file_ext = ($file_ext <> '') ? $file_ext : 'txt';

        $year = date('Y');
        $month = date('M');
        $date = date('d');

        $this->relative_path = 'uploads/finance/masspay/' . $year . '/' . $month . '/' . $date . '/';
        $this->web_path = $this->relative_path . $file_name . $unique_id . '.' . $file_ext;
        $this->absolute_path = rtrim(JPATH_ROOT, '/') . '/' . $this->relative_path;
        $this->file_path = rtrim(JPATH_ROOT, '/') . '/' . $this->web_path;

        $factory->makeDir($this->absolute_path);

        return $this->web_path;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  Download Masspay
    public function downloadMasspay() {

        $factory = new KazistFactory();


        $id = $this->request->request->get('id');

        $check_arr = array('id' => $id);

        $masspay = $factory->getRecord('#__finance_masspay', 'fm', array('id=:id'), $check_arr);



        if (is_object($masspay)) {

            $file_path = JPATH_ROOT . $masspay->file;
            $file_name_arr = explode('/', $masspay->file);
            $file_name_arr_rev = array_reverse($file_name_arr);

            $quoted = $file_name_arr_rev[0];
            $size = filesize($file_path);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $quoted);
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . $size);

            print file_get_contents($file_path);
            exit;
        } else {
            $factory->enqueueMessage('Masspay Record Not Found.');
            $factory->redirect('finance.masspay.masspay');
        }
    }

}
