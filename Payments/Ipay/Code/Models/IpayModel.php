<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Payments\Ipay\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Finance\Payments\Code\Models\PaymentsModel;
use Kazist\Service\Email\Email;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class IpayModel extends PaymentsModel {

    public $code = '';

    public function notificationTransaction($payment_id) {
        $this->processIpay($payment_id);
    }

    public function completeTransaction($payment_id) {
        $this->notificationTransaction($payment_id);
    }

    public function cancelTransaction($payment_id) {
        parent::cancelTransaction($payment_id);
    }

    public function getUrlByPaymentId() {

        $factory = new KazistFactory();

        return $this->generateUrl('subscriptions.subscriptions');
    }

    public function processIpay($payment_id) {

        $email = new Email();

        $posted_data = $this->getIpayParams($payment_id);
        $payment_method = $posted_data['p1'];

        $payment = $this->getPaymentById($payment_id);
        $gateway = $this->getGatewayByShortName($payment_method);
        $deductions = json_decode($payment->deductions);
        $required_amount = (isset($deductions->amount) && $deductions->amount) ? $deductions->amount : $payment->amount;
        $paid_amount = (isset($posted_data['mc']) && $posted_data['mc']) ? $posted_data['mc'] : 0;

        $paid_amount = $this->getConverterAmount($paid_amount, $gateway, false);
        $required_amount = $this->getConverterAmount($required_amount, $gateway, false);

        $vendor_ref = $this->getGatewayParameter($gateway->id, 'vendor_ref');

        $ipnurl = "https://www.ipayafrica.com/ipn/?vendor=" . $vendor_ref .
                "&id=" . $posted_data['item_id'] .
                "&ivm=" . $posted_data['ivm'] .
                "&qwh=" . $posted_data['qwh'] .
                "&afd=" . $posted_data['afd'] .
                "&poi=" . $posted_data['poi'] .
                "&uyt=" . $posted_data['uyt'] .
                "&ifd=" . $posted_data['ifd'];


        $fp = fopen($ipnurl, "rb");
        $status = stream_get_contents($fp, -1, -1);
        fclose($fp);

        if ($status == '') {

            // Get cURL resource
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $ipnurl,
                CURLOPT_USERAGENT => 'SBC call'
            ));
            // Send the request & save response to $resp
            $status = curl_exec($curl);
            // Close request to clear up some resources
            curl_close($curl);
        }

        $payment->type = $payment_method;
        $payment->gateway_id = $gateway->id;
        $payment->code = $posted_data['txncd'];
        $payment->receipt_no = $payment->receipt_no;

        switch ($status) {

            case 'aei7p7yrx4ae34':
            case 'eq3i7p5yt7645e':
            case 'dtfi4p7yty45wq':

                parent::savePaidAmount($payment, $required_amount, $paid_amount);

                if ($paid_amount >= $required_amount) {
                    parent::successfulTransaction($payment_id, $this->code);
                } else {
                    parent::failTransaction($payment_id);
                }

                break;
            case 'fe2707etr5s4wq':
            case 'cr5i3pgy9867e1':
                parent::failTransaction($payment_id);
                break;
            case 'bdi6p2yy76etrs':
                parent::pendingTransaction($payment_id);
                break;
        }
    }

    private function getIpayParams($payment_id) {

        $factory = new KazistFactory();


        //Setting information about the transaction
        $posted_data['payment_id'] = $payment_id;
        $posted_data['item_id'] = $this->request->query->get('id');
        $posted_data['status'] = $this->request->query->get('status');
        $posted_data['txncd'] = $this->code = $this->request->query->get('txncd');
        $posted_data['ivm'] = $this->request->query->get('ivm');
        $posted_data['qwh'] = $this->request->query->get('qwh');
        $posted_data['afd'] = $this->request->query->get('afd');
        $posted_data['poi'] = $this->request->query->get('poi');
        $posted_data['uyt'] = $this->request->query->get('uyt');
        $posted_data['ifd'] = $this->request->query->get('ifd');
        $posted_data['agd'] = $this->request->query->get('agd');

        //Setting the customer's information from the IPN post variables
        $posted_data['mc'] = $this->request->query->get('mc');
        $posted_data['p1'] = $this->request->query->get('p1');
        $posted_data['p2'] = $this->request->query->get('p2');
        $posted_data['p3'] = $this->request->query->get('p3');
        $posted_data['p4'] = $this->request->query->get('p4');

        $factory->saveRecordByEntity('#__finance_payments_ipay', $posted_data, array('fpi.txncd=:txncd'), array('txncd' => $posted_data['txncd']));

        return $posted_data;
    }

}
