<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Withdraw\Views\Withdraw;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\View\Edit\EditHtmlView as GeneralEditHtmlView;
use Kazist\Service\KazistFactory;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class InvoiceHtmlView extends GeneralEditHtmlView {

    public function prepare() {

        $factory = new KazistFactory();

        $minimum_amount = $factory->getSetting('finance_withdraw_minimum_amount');

        

        $rates = $this->model->getInvoice();
        $form = $this->model->getForm();
        $subscriber = $this->model->getSubscriber();

        if ($minimum_amount > $form['amount']) {
            $factory->enqueueMessage('The Amount Withdrawn (' . $form['amount'] . ') is less than minimum anount (' . $minimum_amount . ')', 'error');
            $factory->redirect('finance.withdraw.withdraw');
        }


        $data_arr['rates'] = $rates;
        $data_arr['rates_json'] = json_encode($rates);
        $data_arr['form'] = $form;
        $data_arr['subscriber'] = $subscriber;
        $data_arr['minimum_amount'] = $minimum_amount;
        
        $this->html = $this->render('Business:Addons:Categories:views:categories.twig', $data_arr);

        $response = $this->response($this->html);

        

        return $response;
    }

}
