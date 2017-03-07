<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Withdraw\Views\Withdraw;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\View\Edit\EditHtmlView as GeneralEditHtmlView;
use Kazicode\Service\KazistFactory;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class EditHtmlView extends GeneralEditHtmlView {

    public function prepare() {
        $factory = new KazistFactory();

        $minimum_amount = $factory ->getSetting('finance_withdraw_minimum_amount');
               
        parent::prepare();

        $gateways = $this->model->getWithdrawGateways();
        $subscriber = $this->model->getSubscriber();

        $this->renderer->set('gateways', $gateways);
        $this->renderer->set('subscriber', $subscriber);
        $this->renderer->set('minimum_amount', $minimum_amount);
    }

}
