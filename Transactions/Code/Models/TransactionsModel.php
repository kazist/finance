<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Transactions\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Service\Items\Pagination;
use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Subscriptions\Subscriptions\Code\Classes\Subscriber;
use Subscriptions\Subscriptions\Code\Classes\SyncRegistered;
use Subscriptions\Subscriptions\Code\Classes\Rank;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class TransactionsModel extends BaseModel {

    //put your code here
    //public function getQueryBuilder() {
    public function appendSearchQuery($query) {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        $view_name = $this->request->get('view');
        $document = $this->container->get('document');
        $search = $document->search;

        if (WEB_IS_ADMIN) {
            $user_id = $this->request->get('user_id');
        } else {
            $user_id = $user->id;
        }

        $query = parent:: appendSearchQuery($query);

        $query->leftJoin('ft', '#__users_users', 'ft_uu', 'ft_uu.id = ft.behalf_user_id');
        $query->leftJoin('ft', '#__users_users', 'ft_own', 'ft_own.id = ft.user_id');

        if ($user_id) {
            $query->andWhere('ft.user_id=:user_id');
            $query->setParameter('user_id', $user_id);
        }

        if ($search['from']) {
            $query->andWhere('ft.behalf_user_id=:behalf_user_id');
            $query->setParameter('behalf_user_id', $this->getUserIdByUsername($search['from']));
        }

        if ($search['to']) {
            $query->andWhere('ft.user_id=:user_id');
            $query->setParameter('user_id', $this->getUserIdByUsername($search['to']));
        }

        if ($view_name == 'commission') {
            $query->andWhere('ft.source=:source');
            $query->setParameter('source', 'subscription');
        }

        $query->addSelect('ft_uu.username AS trans_username, ft_uu.name AS trans_name, ft_uu.email AS trans_email');
        $query->addSelect('ft_own.username AS own_username, ft_own.name AS own_name, ft_own.email AS own_email');




        return $query;
    }

    public function save($form_data = '') {

        $form_data = $this->request->get('form');
        
        $form_data['behalf_user_id'] = $form_data['user_id'];
        $form_data['type'] = 'admin';
        if ($form_data['amount'] > 0) {
            $form_data['credit'] = $form_data['amount'];
        } else {
            $form_data['debit'] = abs($form_data['amount']);
        }

        parent::save($form_data);
    }

    public function reverseTransaction($id, $type) {

        $factory = new KazistFactory();

        $check_arr = array('item_id' => $id, 'source' => $type);

        $records = $factory->getRecords('#__finance_transactions', 'ft', array('item_id=:item_id', 'source=:source'), $check_arr);

        if (!empty($records)) {
            foreach ($records as $record) {

                $credit = $record->credit;
                $debit = $record->debit;

                $record->source = 'reversed';
                $record->credit = $debit;
                $record->debit = $credit;
                $record->description = 'Reversed for :- ' . $record->description . '[Txn:' . $record->id . ']';

                unset($record->id);
                unset($record->is_capped);
                unset($record->token);
                unset($record->is_processed);
                unset($record->created_by);
                unset($record->modified_by);
                unset($record->date_created);
                unset($record->date_modified);

                $factory->saveRecord('#__finance_transactions', $record);
            }
        }
    }

    public function getUserIdByUsername($username) {

        $query = $this->getQueryBuilder('#__users_users', 'uu');
        $query->andWhere('uu.username = :username');
        $query->setParameter('username', $username);
        $user = $query->loadObject();

        return $user->id;
    }

    public function getUserById($user_id) {

        $query = $this->getQueryBuilder('#__users_users', 'uu');
        $query->andWhere('uu.id = :id');
        $query->setParameter('id', $user_id);
        $user = $query->loadObject();

        return $user;
    }

    public function getLevelsInput() {

        $tmp_array = array();

        $tmp_array[] = array('value' => '1', 'text' => '1');
        $tmp_array[] = array('value' => '2', 'text' => '2');
        $tmp_array[] = array('value' => '3', 'text' => '3');
        $tmp_array[] = array('value' => '4', 'text' => '4');
        $tmp_array[] = array('value' => '5', 'text' => '5');

        return $tmp_array;
    }

    public function getTypesInput() {

        $tmp_array = array();
        $view_name = $this->request->get('view');

        $factory = new KazistFactory();
        $user = $factory->getUser();

        $query = $this->getQueryBuilder('#__finance_transactions', 'ft');
        $query->select('DISTINCT ft.type');
        $query->andWhere('ft.user_id =' . (int) $user->id);
        if ($view_name == 'commission') {
            $query->andWhere('ft.source=:source');
            $query->setParameter('source', 'subscription');
        }

        $types = $query->loadObjectList();

        if (!empty($types)) {
            foreach ($types as $key => $type) {
                $tmp_array[] = array('value' => $type->type, 'text' => $type->type);
            }
        }


        return $tmp_array;
    }

}
