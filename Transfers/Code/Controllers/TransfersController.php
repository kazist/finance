<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of TransfersController
 *
 * @author sbc
 */

namespace Finance\Transfers\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Finance\Transfers\Code\Models\TransfersModel;

class TransfersController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $this->data_arr['gatewaysinput'] = $this->model->getGetawaysInput();

        return parent::indexAction($offset, $limit);
    }

    public function invoiceAction() {

        $this->model = new TransfersModel();

        $rates = $this->model->getInvoice();
        $form = $this->model->getForm();
        $subscriber = $this->model->getSubscriber();

        $data_arr['rates'] = $rates;
        $data_arr['rates_json'] = json_encode($rates);
        $data_arr['form'] = $form;
        $data_arr['subscriber'] = $subscriber;

        $this->html = $this->render('Finance:Transfers:Code:views:invoice.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    public function editAction() {

        $this->model = new TransfersModel();

        $item = $this->model->getRecord();
        $gateways = $this->model->getTransferGateways();

        $data_arr['item'] = $item;
        $data_arr['gateways'] = $gateways;

        $this->html = $this->render('Finance:Transfers:Code:views:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
