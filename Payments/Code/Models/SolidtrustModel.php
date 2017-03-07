<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Payments\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\ BaseModel;
use Kazist\KazistFactory;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class SolidtrustModel extends BaseModel {

    public function notificationTransaction($payment_id) {

        $factory = new KazistFactory();
        $input = $factory->getInput();

        //Setting information about the transaction
        $posted_data['hash'] = $this->request->request->get('hash');
        $posted_data['merchantAccount'] = $this->request->request->get('merchantAccount');
        $posted_data['payerAccount'] = $this->request->request->get('payerAccount');
        $posted_data['subscription'] = $this->request->request->get('subscription');
        $posted_data['item_id'] = $this->request->request->get('item_id');
        $posted_data['amount'] = $this->request->request->get('amount');
        $posted_data['testmode'] = $this->request->request->get('testmode');
        $posted_data['subscriber'] = $this->request->request->get('Subscriber');
        $posted_data['tr_id'] = $this->request->request->get('tr_id');
        $posted_data['status'] = $this->request->request->get('status');

//Setting the customer's information from the IPN post variables
        $posted_data['user1'] = $this->request->request->get('user1');
        $posted_data['user2'] = $this->request->request->get('user2');
        $posted_data['user3'] = $this->request->request->get('user3');
        $posted_data['user4'] = $this->request->request->get('user4');
        $posted_data['user5'] = $this->request->request->get('user5');
        $posted_data['user6'] = $this->request->request->get('user6');
        $posted_data['user7'] = $this->request->request->get('user7');
        $posted_data['user8'] = $this->request->request->get('user8');
        $posted_data['user9'] = $this->request->request->get('user9');
        $posted_data['user10'] = $this->request->request->get('user10');

        $secondary_password = Phpfox::getParam('mlmfinance.secondary_password');
        $secondary_password = md5($secondary_password . 's+E_a*');

//encryption for db
        $hash_received = MD5($posted_data['tr_id'] . ":" . MD5($secondary_password) . ":" . $posted_data['amount'] . ":" . $posted_data['merchantAccount'] . ":" . $posted_data['payerAccount']);
        if ($hash_received == $posted_data['hash']) {
            if ($posted_data['status'] == "COMPLETE") {
                parent::successfulTransaction($payment_id);
            } elseif ($posted_data['status'] == "CANCELED") {
                parent::invalidTransaction($payment_id);
            } else {
                parent::pendingTransaction($payment_id);
            }
        }
        parent::notificationTransaction($payment_id);
    }

    public function completeTransaction($payment_id) {
        $this->completeTransaction($payment_id);
    }

    public function cancelTransaction($payment_id) {
        parent::cancelTransaction($payment_id);
    }

}
