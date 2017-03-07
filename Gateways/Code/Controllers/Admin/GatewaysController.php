<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of GatewaysController
 *
 * @author sbc
 */

namespace Finance\Gateways\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Finance\Gateways\Code\Models\GatewaysModel;

class GatewaysController extends BaseController {

    public function saveAction() {

        $factory = new KazistFactory();

        $form = $this->request->get('form');

        $this->model = new GatewaysModel();
        $gateway_id = $this->model->save($form);


        if ($gateway_id) {

            $this->model->saveGatewayDetail($gateway_id, $form);

            $msg = $form['title'] . ' Payment Gateway Save Successfully';
            $factory->enqueueMessage($msg, 'info');
            $redirect = $this->generateUrl('admin.finance.gateways.edit', array('id' => $gateway_id));
        } else {
            $msg = 'Payment Gateway Not added due to some errors;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('admin.finance.gateways');
        }

        return $this->redirect($redirect);
    }

}
