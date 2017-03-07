<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of TokensController
 *
 * @author sbc
 */

namespace Finance\Payments\Tokens\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Finance\Payments\Tokens\Code\Models\TokensModel;
use Finance\Payments\Code\Controllers\PaymentsController;

class TokensController extends PaymentsController {

    public function notifyAction() {
        $payment_id = $this->request->query->get('payment_id');

        $this->model = new TokensModel();
        $this->model->notificationTransaction($payment_id);
        $payment_url = $this->model->getUrlByPaymentId($payment_id);

        return $this->redirect($payment_url);
    }

    public function returnAction() {

        $payment_id = $this->request->query->get('payment_id');

        $this->model = new TokensModel();
        $this->model->completeTransaction($payment_id);
        $payment_url = $this->model->getUrlByPaymentId($payment_id);

        return $this->redirect($payment_url);
    }

    public function cancelAction() {

        $payment_id = $this->request->query->get('payment_id');

        $this->model = new TokensModel();
        $this->model->cancelTransaction($payment_id);
        $payment_url = $this->model->getUrlByPaymentId($payment_id);

        return $this->redirect($payment_url);
    }

}
