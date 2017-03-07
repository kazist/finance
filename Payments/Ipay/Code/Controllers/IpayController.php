<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of IpayController
 *
 * @author sbc
 */

namespace Finance\Payments\Ipay\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Finance\Payments\Ipay\Code\Models\IpayModel;
use Finance\Payments\Code\Controllers\PaymentsController;

class IpayController extends PaymentsController {

    public function cancelAction() {

        $payment_id = $this->request->query->get('id');

        $this->model = new IpayModel();
        $this->model->cancelTransaction($payment_id);
        $payment_url = $this->model->getUrlByPaymentId($payment_id);

        return $this->redirect($payment_url);
    }

    public function returnAction() {

        $payment_id = $this->request->query->get('id');

        $this->model = new IpayModel();
        $this->model->completeTransaction($payment_id);
        $payment_url = $this->model->getUrlByPaymentId($payment_id);

        return $this->redirect($payment_url);
    }

    public function notifyAction() {

        $payment_id = $this->request->query->get('id');

        $this->model = new IpayModel();
        $this->model->processIpay($payment_id);
        $this->model->notificationTransaction($payment_id);
        $payment_url = $this->model->getUrlByPaymentId($payment_id);

        return $this->redirect($payment_url);
    }

}
