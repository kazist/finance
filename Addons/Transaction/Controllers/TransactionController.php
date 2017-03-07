<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Addons\Transaction\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Finance\Addons\Transaction\Models\TransactionModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class TransactionController extends AddonController {

    function indexAction($offset = 0, $limit = 6) {

        $model = new TransactionModel;

        $transactions = $model->getTransactions();
        $total_credit = $model->getTotalCredit();
        $total_debit = $model->getTotalDebit();

        $data_arr['transactions'] = $transactions;
        $data_arr['total_credit'] = $total_credit;
        $data_arr['total_debit'] = $total_debit;

        $this->html = $this->render('Finance:Addons:Transaction:views:transaction.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
