<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Addons\Withdraw\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Finance\Addons\Withdraw\Models\WithdrawModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class WithdrawController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new WithdrawModel;

        $withdraws = $model->getWithdraws();
        $total_withdraws = $model->getTotalWithdraws();

        $data_arr['withdraws'] = $withdraws;
        $data_arr['total_withdraws'] = $total_withdraws;

        $this->html = $this->render('Finance:Addons:Withdraw:views:withdraw.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
