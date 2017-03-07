<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Transfers\Views\Transfer;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\View\Edit\EditHtmlView as GeneralEditHtmlView;
use Kazicode\Service\KazistFactory;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class InvoiceHtmlView extends GeneralEditHtmlView {

    public function prepare() {

        parent::prepare();

        $rates = $this->model->getInvoice();
        $form = $this->model->getForm();
        $subscriber = $this->model->getSubscriber();
        
        $this->renderer->set('rates', $rates);
        $this->renderer->set('rates_json', json_encode($rates));
        $this->renderer->set('form', $form);
        $this->renderer->set('subscriber', $subscriber);
    }

}
