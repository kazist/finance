<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Payments\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class EvripayModel extends BaseModel {

    public function notificationTransaction($payment_id) {

        $factory = new KazistFactory();


        $posted_data['order_id'] = $this->request->request->get('orderid');
        $posted_data['card_status'] = $this->request->request->get('Express_Payment_Card_Status');
        $posted_data['description'] = $this->request->request->get('Express_Result_Description');
        $posted_data['merchant_reference'] = $this->request->request->get('Express_Merchant_Reference');
        $posted_data['bank_reference'] = $this->request->request->get('Express_Bank_Reference');
        $posted_data['transaction_date'] = $this->request->request->get('Express_Transaction_Date');

        $this->saveParams($payment_id, $posted_data);

        $status = $posted_data['card_status'];

        if ($status == 0) {
            parent::successfulTransaction($payment_id);
        } elseif ($status == 1 || $status == 2 || $status == 5 || $status == 9) {
            $msg = 'Evripay Network Error';
            parent::failTransaction($payment_id);
        } elseif ($status < 255) {
            $msg = 'Evripay Expired or Denied';
            parent::failTransaction($payment_id);
        } elseif ($status == 255) {
            $msg = 'Evripay Required Variables Not Provided.';
            parent::failTransaction($payment_id);
        } else {
            $msg = 'Evripay Denied or Expired';
            parent::pendingTransaction($payment_id);
        }
    }

    public function completeTransaction($payment_id) {
        $this->notificationTransaction($payment_id);
    }

    public function cancelTransaction($payment_id) {
        parent::cancelTransaction($payment_id);
    }

}
