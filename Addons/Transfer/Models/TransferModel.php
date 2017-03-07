<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Addons\Transfer\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

class TransferModel {

    public function getInfo() {
        return 'Hello World!';
    }

    public function getTransfers() {

        $query = $this->getQuery();

        $query->setFirstResult(0);
        $query->setMaxResults(5);

        $records = $query->loadObjectList();


        return $records;
    }

    public function getTotalTransfers() {

        $query = new Query();

        $query->select('SUM(ft.amount) as total');
        $query->from('#__finance_transfers', 'ft');

        $record = $query->loadObject();

        return $record->total;
    }

    public function getQuery() {

        $query = new Query();

        $query->select('ft.*');
        $query->from('#__finance_transfers', 'ft');
        $query->orderBy('ft.id ', 'DESC');

        return $query;
    }

}
