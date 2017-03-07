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

namespace Finance\Payments\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Finance\Payments\Code\Models\PaymentsModel;

class PaymentsController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $this->data_arr['gatewaysinput'] = $this->model->getGatewaysInput();
        $this->data_arr['show_action'] = false;
        $this->data_arr['show_search'] = true;
        $this->data_arr['show_messages'] = true;

        return parent::indexAction($offset, $limit);
    }

    public function reversepaymentAction() {

        $this->model = new PaymentsModel();
        $this->model->reversePayment();

        return $this->redirectToRoute('admin.finance.payments');
    }

}
