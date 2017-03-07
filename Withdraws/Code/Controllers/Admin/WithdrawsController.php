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

namespace Finance\Withdraws\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Finance\Withdraws\Code\Models\WithdrawsModel;

class WithdrawsController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $this->data_arr['gatewaysinput'] = $this->model->getGetawaysInput();
        $this->data_arr['paidinput'] = $this->model->getPaidInput();
        $this->data_arr['show_action'] = false;
        $this->data_arr['show_search'] = true;
        $this->data_arr['show_messages'] = true;

        return parent::indexAction($offset, $limit);
    }

    public function editAction() {
        $factory = new KazistFactory();

        $minimum_amount = $factory->getSetting('finance_withdraw_minimum_amount');

        $this->model = new WithdrawsModel();

        $item = $this->model->getRecord();
        $gateways = $this->model->getWithdrawGateways();
        $subscriber = $this->model->getSubscriber();

        $data_arr['item'] = $item;
        $data_arr['gateways'] = $gateways;
        $data_arr['subscriber'] = $subscriber;
        $data_arr['minimum_amount'] = $minimum_amount;

        $this->html = $this->render('Finance:Withdraws:Code:views:admin:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function reversewithdrawAction() {
        $this->model = new WithdrawsModel();
        $this->model->reverseWithdraw();

        return $this->redirectToRoute('admin.finance.withdraws');
    }

}
