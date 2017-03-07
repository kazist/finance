<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Payments\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Finance\Payments\Code\Models\PaymentsModel;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class BonusModel extends PaymentsModel {

    public function notificationTransaction($payment_id) {

        parent::notificationTransaction($payment_id);
    }

    public function completeTransaction($payment_id) {

        $process_bonus = $this->processBonusPayment($payment_id);

        if ($process_bonus) {
            parent::successfulTransaction($payment_id);
        } else {
            parent::failTransaction($payment_id);
        }
    }

    public function cancelTransaction($payment_id) {

        parent::cancelTransaction($payment_id);
    }

 
    public function processBonusPayment($payment_id) {
  print_r('$payment');
        exit;
        $factory = new KazistFactory();

        $payment = $this->getPaymentById($payment_id);
        $deductions = json_decode($payment->deductions);

        $total_earning = $this->getUserTotalEarning($payment);

        if ($total_earning > $deductions->amount) {
            
            return true;
        } else {
            return false;
        }
    }

    public function getUserTotalEarning($payment) {

        $debit = $this->getUserTotalDebit($payment);
        $credit = $this->getUserTotalCredit($payment);

        return $credit - $debit;
    }

    public function getUserTotalDebit($payment) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('SUM(fg.debit) as total');
        $query->from('#__finance_transactions', 'fg');
        $query->where('fg.user_id=:user_id');
        $query->setParameter('user_id', $payment->user_id);
     

        $record = $query->loadObject();

        return $record->total;
    }

    public function getUserTotalCredit($payment) {
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('SUM(fg.credit) as total');
        $query->from('#__finance_transactions', 'fg');
        $query->where('fg.user_id=:user_id');
        $query->setParameter('user_id', $payment->user_id);
     

        $record = $query->loadObject();

        return $record->total;
    }

}
