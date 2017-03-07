<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of WithdrawsController
 *
 * @author sbc
 */

namespace Finance\Withdraws\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Finance\Withdraws\Code\Models\WithdrawsModel;

class WithdrawsController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {


        $this->data_arr['gatewaysinput'] = $this->model->getGetawaysInput();
        $this->data_arr['paidinput'] = $this->model->getPaidInput();

        return parent::indexAction($offset, $limit);
    }

    public function saveAction($form = '') {

        $factory = new KazistFactory();

        $form = $this->request->request->get('form');
        $subscriber = $this->model->getSubscriber();

        $minimum_amount = $factory->getSetting('finance_withdraw_minimum_amount');

        if ($form['amount'] >= $minimum_amount && $subscriber->money_in >= $form['amount']) {
            return parent::saveAction($form);
        } else {
            $factory->enqueueMessage(' Wrong amount provided. Enter correct amount.', 'error');
            return $this->redirectToRoute('finance.withdraws.add');
        }
    }

    public function addAction() {

        $factory = new KazistFactory();

        $minimum_amount = $factory->getSetting('finance_withdraw_minimum_amount');

        $this->model = new WithdrawsModel();

        $item = $this->model->getRecord();
        $subscriber = $this->model->getSubscriber();
        $gateways = $this->model->getWithdrawGateways($subscriber);
        $gateway = $this->model->getWithdrawGateway($subscriber);

        if ($subscriber->subscription->website == '') {
            $factory->enqueueMessage('Hosting is mandatory for all upgraded accounts. Please set up you hosting Domain Details.', 'error');
            return $this->redirectToRoute('subscriptions.subscriptions.hosting');
        } elseif (empty($gateways)) {
            $factory->enqueueMessage(' Please provide all financial details in order to proceed with withdrawal.', 'error');
            return $this->redirectToRoute('subscriptions.subscriptions.edit', array('user_id' => $subscriber->id));
        }

        $data_arr['item'] = $item;
        $data_arr['gateways'] = $gateways;
        $data_arr['gateway'] = $gateway;
        $data_arr['subscriber'] = $subscriber;
        $data_arr['minimum_amount'] = $minimum_amount;

        $this->html = $this->render('Finance:Withdraws:Code:views:edit.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    public function invoiceAction() {

        $factory = new KazistFactory();

        $minimum_amount = $factory->getSetting('finance_withdraw_minimum_amount');

        $this->model = new WithdrawsModel();

        $form = $this->request->get('form');
        $rates = $this->model->getInvoice();
        $subscriber = $this->model->getSubscriber();

        if ($minimum_amount > $form['amount']) {
            $factory->enqueueMessage('The Amount Withdrawn (' . $form['amount'] . ') is less than minimum anount (' . $minimum_amount . ')', 'error');
            return $this->redirectToRoute('finance.withdraws.add');
        }

        $data_arr['rates'] = $rates;
        $data_arr['rates_json'] = json_encode($rates);
        $data_arr['form'] = $form;
        $data_arr['subscriber'] = $subscriber;
        $data_arr['minimum_amount'] = $minimum_amount;

        $this->html = $this->render('Finance:Withdraws:Code:views:invoice.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
