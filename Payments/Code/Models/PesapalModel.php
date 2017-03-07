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
class PesapalModel extends PaymentModel {

    public $code = '';

    public function notificationTransaction($payment_id) {

        $this->processPesapal($payment_id);
    }

    public function completeTransaction($payment_id) {
        $this->notificationTransaction($payment_id);
    }

    public function cancelTransaction($payment_id) {
        parent::cancelTransaction($payment_id);
    }

    public function processPesapal($payment_id) {

        $payment_method = 'pesapal';
        $payment = $this->getPaymentById($payment_id);
        $gateway = $this->getGatewayByShortName($payment_method);

        if (strcmp($res, "VERIFIED") == 0) {

            if ($posted_data['payment_status'] == 'Completed') {

                if ($payment->code == '') {

                    $payment->type = 'pesapal';
                    $payment->gateway_id = $gateway->id;
                    $payment->code = $this->request->get('pesapal_transaction_tracking_id');
                    $payment->receipt_no = $payment->receipt_no;

                    parent::savePaidAmount($payment, $required_amount, $paid_amount);

                    if ($paid_amount >= $required_amount) {
                        parent::successfulTransaction($payment_id, $this->code);
                    } else {
                        parent::failTransaction($payment_id);
                    }
                }
            } elseif ($posted_data['payment_status'] == "Canceled_Reversal" || $posted_data['payment_status'] == "Denied" ||
                    $posted_data['payment_status'] == "Expired" || $posted_data['payment_status'] == "Failed" ||
                    $posted_data['payment_status'] == "Refunded" || $posted_data['payment_status'] == "Voided") {

                $msg = $status_array[$posted_data['payment_status']];


                parent::failTransaction($payment_id);

                $factory->enqueueMessage($msg, 'error');
            } else {
                if ($posted_data['txn_type'] != 'subscr_eot' && $posted_data['txn_type'] != 'subscr_signup' && $posted_data['txn_type'] != 'subscr_modify') {
                    parent::pendingTransaction($payment_id);
                }
            }
        }
    }

    public function getPaypalParams() {

        $posted_data = array();

        $factory = new KazistFactory();
        $input = $factory->getInput();

        $posted_data['pesapal_notification_type'] = $this->request->get('pesapal_notification_type');
        $posted_data['pesapal_transaction_tracking_id'] = $this->request->request->get('pesapal_transaction_tracking_id');
        $posted_data['pesapal_merchant_reference'] = $this->request->request->get('pesapal_merchant_reference');

        return $posted_data;
    }

}
