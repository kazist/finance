<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResponseEvent
 *
 * @author sbc
 */

namespace Finance\Payments\Code\Events;

defined('KAZIST') or exit('Not Kazist Framework');

use Symfony\Component\EventDispatcher\Event;
use Kazist\KazistFactory;

class PaymentEvent extends Event {

    private $payment;
    private $subset;

    public function __construct($payment) {
        $this->payment = $payment;
        $this->subset = $this->getSubsetDetail();
    }

    public function getPayment() {
        return $this->payment;
    }

    public function getSubset() {
        return $this->subset;
    }

    public function getSubsetDetail() {

        $factory = new KazistFactory();

        $record = $factory->getRecord('#__system_subsets', 'ss', array('ss.id=:id'), array('id' => $this->payment->subset_id));


        return $record;
    }

}

/**
 * Some of Events to be fired
 * - payment.notification
 * - payment.successful
 * - payment.fail
 * - payment.invalid
 * - payment.pending
 * - payment.complete
 * - payment.cancel
 * 
 */