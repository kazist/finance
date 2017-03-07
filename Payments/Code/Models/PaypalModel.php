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
class PaypalModel extends PaymentModel {

    public $code = '';

    public function notificationTransaction($payment_id) {

        $this->processPaypal($payment_id);
    }

    public function completeTransaction($payment_id) {
        $this->notificationTransaction($payment_id);
    }

    public function cancelTransaction($payment_id) {
        parent::cancelTransaction($payment_id);
    }

    public function processPaypal($payment_id) {

        $factory = new KazistFactory();

        $status_array = $this->getPaypalStatus();
        $posted_data = $this->getPaypalParams();

        $payment = $this->getPaymentById($payment_id);
        $gateway = $this->getGatewayByShortName('paypal');
        $deductions = json_decode($payment->deductions);
        $required_amount = (isset($deductions->amount) && $deductions->amount) ? $deductions->amount : $payment->amount;
        $paid_amount = (isset($posted_data['payment_amount']) && $posted_data['payment_amount']) ? $posted_data['payment_amount'] : 0;

        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2)
                $myPost[$keyval[0]] = urldecode($keyval[1]);
        }

        $req = 'cmd=_notify-validate';
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }

        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        $ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        if (!($res = curl_exec($ch))) {
            curl_close($ch);
            exit;
        }

        curl_close($ch);


        if (strcmp($res, "VERIFIED") == 0) {

            if ($posted_data['payment_status'] == 'Completed') {

                if ($payment->code == '') {

                    $payment->type = 'paypal';
                    $payment->gateway_id = $gateway->id;
                    $payment->code = $posted_data['txn_id'];
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

        $posted_data['item_name'] = $this->request->request->get('item_name');
        $posted_data['item_number'] = $this->request->request->get('item_number');
        $posted_data['payment_status'] = $this->request->request->get('payment_status');
        $posted_data['payment_amount'] = ($this->request->request->get('amount')) ? $this->request->request->get('amount') : $this->request->request->get('mc_gross');
        $posted_data['payment_currency'] = $this->request->request->get('mc_currency');
        $posted_data['txn_id'] = $this->code = $this->request->request->get('txn_id');
        $posted_data['subscr_id'] = $this->request->request->get('subscr_id');
        $posted_data['receiver_email'] = $this->request->request->get('receiver_email');
        $posted_data['payer_email'] = $this->request->request->get('payer_email');
        $posted_data['txn_type'] = $this->request->request->get('txn_type');

        return $posted_data;
    }

    public function getPaypalStatus() {

        $status_array = array();

        $status_array['Canceled_Reversal'] = 'Canceled_Reversal: A reversal has been canceled. For example, you won a dispute with the customer, and the funds for the transaction that was reversed have been returned to you.';
        $status_array['Completed'] = 'Completed: The payment has been completed, and the funds have been added successfully to your account balance.';
        $status_array['Created'] = 'Created: A German ELV payment is made using Express Checkout.';
        $status_array['Denied'] = 'Denied: You denied the payment. This happens only if the payment was previously pending because of possible reasons described for the pending_reason variable or the Fraud_Management_Filters_x variable.';
        $status_array['Expired'] = 'Expired: This authorization has expired and cannot be captured.';
        $status_array['Failed'] = 'Failed: The payment has failed. This happens only if the payment was made from your customer’s bank account.';
        $status_array['Pending'] = 'Pending: The payment is pending. See pending_reason for more information.';
        $status_array['Refunded'] = 'Refunded: You refunded the payment.';
        $status_array['Reversed'] = 'Reversed: A payment was reversed due to a chargeback or other type of reversal. The funds have been removed from your account balance and returned to the buyer. The reason for the reversal is specified in the ReasonCode element.';
        $status_array['Processed'] = 'Processed: A payment has been accepted.';
        $status_array['Voided'] = 'Voided: This authorization has been voided .';

        return $status_array;
    }

}
