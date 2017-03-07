<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Addons\Withdraw\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class WithdrawModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getWithdraws() {

        $query = $this->getQuery();

        $query->setFirstResult(0);
        $query->setMaxResults(5);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getTotalWithdraws() {

        $query = new Query();

        $query->select('SUM(fw.amount) as total');
        $query->from('#__finance_withdraws', 'fw');

        $record = $query->loadObject();

        return $record->total;
    }

    public function getQuery() {

        $query = new Query();

        $query->select('fw.*');
        $query->from('#__finance_withdraws', 'fw');
        $query->orderBy('fw.id ', 'DESC');

        return $query;
    }

}
