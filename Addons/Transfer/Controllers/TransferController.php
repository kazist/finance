<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Addons\Transfer\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Finance\Addons\Transfer\Models\TransferModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class TransferController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new TransferModel;

        $transfers = $model->getTransfers();
        $total_transfers = $model->getTotalTransfers();

        $data_arr['transfers'] = $transfers;
        $data_arr['total_transfers'] = $total_transfers;

        $this->html = $this->render('Finance:Addons:Transfer:views:transfer.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
