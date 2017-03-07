<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Tokens\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class TokensModel extends BaseModel {

    public $unique_name = '';

    public function appendSearchQuery($query) {
        $document = $this->container->get('document');
        $search = $document->search;

        $query = parent:: appendSearchQuery($query);

        if ($search['from']) {
            $query->andWhere('ft.used_by=:used_by');
            $query->setParameter('used_by', $this->getUserIdByUsername($search['from']));
        }



        return $query;
    }

    public function getUserIdByUsername($username) {

        $query = $this->getQueryBuilder('#__users_users', 'uu');
        $query->andWhere('uu.username = :username');
        $query->setParameter('username', $username);
        $user = $query->loadObject();

        return $user->id;
    }

    public function getUniqueTokenGroups() {

        $query = $this->
                getQueryBuilder('#__finance_tokens', 'ft');

        $query->select('ft.unique_name, count(*) AS total');
        $query->where('ft.used = 0');
        $query->addGroupBy('ft.unique_name');
        $query->having('total > 0');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getUniqueGroupTokens($unique_name) {

        $query = $this->getQueryBuilder('#__finance_tokens', 'ft');

        $query->select('ft.*');
        $query->where('ft.used = :used');

        $query->
                setParameter('used', 0);
        if ($unique_name <> '') {
            $query->setParameter('unique_name', $unique_name);
        } else {
            $query->orWhere('ft.unique_name IS NULL OR ft.unique_name=""');
        }

        $records = $query->loadObjectList();


        return $records;
    }

    public function getTypes() {

        $tmp_array = array();
        $factory = new KazistFactory();


        $query = $factory->getQueryBuilder('#__finance_tokens_types', 'ftt');

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->amount);
            }
        }

        return $tmp_array;
    }

    public function getType($id) {

        $tmp_array = array();
        $factory = new KazistFactory();
        $query = $factory->getQueryBuilder('#__finance_tokens_types');

        $query->where('id=' . $id);

        $record = $query->loadObject();

        return $record;
    }

    public function deleteToken() {

        $factory = new KazistFactory();

        $cid = $this->request->request->get('cid', null, 'raw');

        $records = $this->getTokenToDelete($cid);
        if (!empty($records)) {
            foreach ($records as $key => $record) {
                if ($record->used) {
                    $factory->enqueueMessage('You can not delete Used Token ' . $record->token, 'error');
                } else {
                    $factory->deleteRecords('#__finance_tokens', array
                        ('id=:id'), array('id' => $record->id));
                }
            }
        }
    }

    public function getTokenToDelete($cid) {

        $factory = new KazistFactory();


        $query = $factory->getQueryBuilder('financetokens', 'tt');
        $query->where('tt.id IN (' . implode(',', $cid) . ')');

        $records = $query->loadObjectList();

        return $records;
    }

    public function generateToken() {

        $factory = new KazistFactory ( );


        $form = $this->request->request->get('form');

        //$amount = $form['amount'];
        $total = $form['total'];
        $type_id = $form['type_id'];

        $type = $this->getType($type_id);

        $amount = $type->amount;

        $this->unique_name = 'token_' . date('Y') . '_' . date('m') . '_' . date('d') . '_' . date('H') . '_' . date('i') . '_' . date('s');
        if ((int) $total) {
            for ($x = 0; $x < $total; $x++) {
                $this->generateTokenNSave($amount, $type_id);
            }
        }
    }

    public function generateTokenNSave($amount, $type_id) {

        $factory = new KazistFactory();

        $unique_number = rand(10000000, 100000000) . str_pad($amount, 2, "0", STR_PAD_LEFT);

        if ($this->tokenExist($unique_number)) {
            $this->generateTokenNSave($amount);
        } else {

            $data_obj = new \stdClass();

            $data_obj->unique_name = $this->unique_name;
            $data_obj->token = $unique_number;
            $data_obj->amount = $amount;
            $data_obj->type_id = $type_id;


            $factory->saveRecord('#__finance_tokens', $data_obj);
        }
    }

    public function tokenExist($unique_number) {

        $factory = new KazistFactory();



        $query = new Query();
        $query->select('ft.*');
        $query->from('#__finance_tokens', 'ft');
        $query->where('ft.token=:token');
        $query->setParameter('token', $unique_number);

        $record = $query->loadObject();

        if (is_object($record)) {
            return true;
        } else {
            return false;
        }

        return $record;
    }

}
