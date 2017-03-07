<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Payment\Views\Payment;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\View\Edit\EditHtmlView as GeneralEditHtmlView;
use Kazicode\Service\KazistFactory;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class PayHtmlView extends GeneralEditHtmlView {

    public function prepare() {
        parent::prepare();

        $factory = new KazistFactory();
        $input = $factory ->getInput();
        $payment_id = $input->get('payment_id');

        $factory ->validUser();

        $user = $this->model->getUser();
        $converter = $this->model->getConverter();
        $gateways = $this->model->getPaymentGateways($payment_id);
        $payment = $this->model->getPayment($payment_id);

        $gateways_arr = json_decode(json_encode($gateways), true);
 
        $this->renderer->set('gateways', $gateways_arr);
        $this->renderer->set('payment', $payment);
        $this->renderer->set('user', $user);
        $this->renderer->set('converter', $converter);
    }

}
