<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of PaymentsController
 *
 * @author sbc
 */

namespace Finance\Payments\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Finance\Payments\Code\Models\PaymentsModel;

class PaymentsController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $this->data_arr['gatewaysinput'] = $this->model->getGatewaysInput();

        return parent::indexAction($offset, $limit);
    }

    public function cancelAction() {
        
    }

    public function returnAction() {
        
    }

    public function notifyAction() {
        
    }

    public function ajaxsavejsonAction() {

        $payment_id = $this->request->get('payment_id');
        $payment_params = $this->request->get('payment_params');
        $payment_type = $this->request->get('payment_type');
        $payment_url = $this->request->get('payment_url');
        $token = $this->request->get('_token');
        $form = $this->request->request->all();

        $this->model = new PaymentsModel();
        $this->model->savePaymentJson($payment_id, $payment_params, $payment_type);

        unset($form['payment_url']);
        unset($form['payment_id']);
        unset($form['payment_params']);
        unset($form['payment_type']);

        if ($payment_url != '') {

            $html .= "<form action=\"" . $payment_url . "\" method='post' name='frm'>";

            foreach ($form as $a => $b) {
                $html .= "<input type='hidden' name='" . htmlentities($a) . "' value='" . htmlentities($b) . "'>";
            }
                $html .= "<input type='hidden' name='_token' value='" . $token . "'>";

            $html .= "</form>";
            $html .= "<script language=\"JavaScript\">";
            $html .= "document.frm.submit();";
            $html .= "</script>";

            print_r($html);
            exit;
        }
    }

    public function payAction() {

        $this->model = new PaymentsModel();

        $payment_id = $this->request->get('payment_id');

        $user = $this->model->getUser();
        $converter = $this->model->getConverter();
        $gateways = $this->model->getPaymentGateways($payment_id);
        $payment = $this->model->getPayment($payment_id);

        $gateways_arr = json_decode(json_encode($gateways), true);
        $converter_arr = json_decode(json_encode($converter), true);
        
        $data_arr['gateways'] = $gateways_arr;
        $data_arr['payment'] = $payment;
        $data_arr['user'] = $user;
        $data_arr['converter'] = $converter_arr;

        $this->html = $this->render('Finance:Payments:Code:views:pay.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function newpaymentAction() {

        $factory = new KazistFactory();
        $this->model = new PaymentsModel();

        $subset_id = $this->request->query->get('subset_id');
        $item_id = $this->request->query->get('item_id');

        $paymentModel = new PaymentsModel();
        $payment = $paymentModel->getPayment();

        if ($payment->id) {
            $redirect = $this->generateUrl('finance.payments.pay', array('payment_id' => $payment->id));
        } else {
            $msg = 'Initiating Payment Process Failed.';
            $factory->enqueueMessage($msg, 'error');

            $redirect = $paymentModel->getRedirectUrl($subset_id, $item_id);
        }

        return $this->redirect($redirect);
    }

}
