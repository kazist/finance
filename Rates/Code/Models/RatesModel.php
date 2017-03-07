<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Rates\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Subscriptions\Subscriptions\Code\Classes\Subscriber;
use Kazist\Service\Email\Email;
use Cron\CronExpression;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class RatesModel extends BaseModel {

//put your code here
    public $file_is_unique = true;
    public $relative_path = '';
    public $web_path = '';
    public $absolute_path = '';
    public $file_path = '';
    public $next_effected_on_date = '';
    public $effected_on = '';
    public $amount_limit = 0;
    public $has_valid_record = false;

    public function completeProcess() {

        $factory = new KazistFactory();



        $token = $this->request->request->get('token');

        $rate_query = new Query();
        $rate_query->update('#__finance_rates_file');
        $rate_query->set('is_processed', '1');
        $rate_query->where('token=:token');
        $rate_query->setParameter('token', $token);
        $db->execute();

        $transaction_query = new Query();
        $transaction_query->update('#__finance_transactions');
        $transaction_query->set('is_processed', '1');
        $transaction_query->where('token=:token');
        $transaction_query->setParameter('token', $token);
        $db->execute();
    }

    public function generateRatesFile() {

        $rates = $this->getChargeRates();

        if (!empty($rates)) {
            foreach ($rates as $key => $rate) {
                $this->processRateFile($rate);
            }
        }
    }

    public function processRateFile($rate) {

        $factory = new KazistFactory();

        $this->next_effected_on_date = $rate->next_effected_on_date;
        $this->effected_on = $rate->effected_on;
        $this->amount_limit = $rate->amount_limit;

        $total_rates = $this->countTransactionsRates($rate->id);

        if ($total_rates) {

            $transaction_rates = $this->getTransactionsRates($rate->id, $rate->file_limit);

            $unique_id = substr(uniqid(), -7);
            $this->prepareFile($unique_id, $rate->short_name, $rate->file_type);
            file_put_contents($this->file_path, $rate->file_prefix, FILE_APPEND | LOCK_EX);

            foreach ($transaction_rates as $key => $transaction_rate) {
                $this->processMergedTransactionRate($rate, $transaction_rate, $unique_id);
            }

            if ($this->has_valid_record) {
                file_put_contents($this->file_path, $rate->file_suffix, FILE_APPEND | LOCK_EX);
                $rate_file_id = $this->saveTransactionRate($rate, $unique_id);

                $this->sendEmail($rate, $rate_file_id);
            } else {
                unlink($this->file_path);
            }

            if ($total_rates > $rate->file_limit) {
                $this->processTransactionRate($rate);
            }
        } else {

            $data_obj = new \stdClass();
            $data_obj->id = $rate->id;
            $data_obj->next_effected_on_date = $this->getNextEffectedOnDate();
            $factory->saveRecord('#__finance_rates', $data_obj);
        }
    }

    public function sendEmail($rate, $rate_file_id) {

        $email = new Email();
        $factory = new KazistFactory();

        $rate_file = $factory->getRecord('#__finance_rates_file', 'frf', array('id=:id'), array('id' => $rate_file_id));
        $users = $factory->getRecords('#__users_users', 'uu', array('is_admin=:is_admin'), array('is_admin' => 1));
        $attachments = array($this->file_path);

        $tmp_array['rate'] = $rate;
        $tmp_array['rate_file'] = $rate_file;

        $email->sendDefinedLayoutEmail('finance.rates.generatefile', $users, $tmp_array, $attachments);
    }

    public function saveTransactionRate($rate, $unique_id) {

        $factory = new KazistFactory();

        $year = date('Y');
        $month = date('M');
        $date = date('d');

        $data_obj = new \stdClass();
        $data_obj->year = $year;
        $data_obj->month = $month;
        $data_obj->date = $date;
        $data_obj->type = $rate->short_name;
        $data_obj->token = $unique_id;
        $data_obj->max_limit = $rate->file_limit;

        return $factory->saveRecord('#__finance_rates_file', $data_obj);
    }

    public function processMergedTransactionRate($rate, $transaction_rate, $unique_id) {


        $factory = new KazistFactory();
        $subscriber = new Subscriber();

        $tmp_array = array();
        $rate_id = $rate->id;
        $rate_type = $rate->type;
        $rate_amount_limit = $rate->amount_limit;
        $structure = $rate->file_structure;
        $transaction_amount = $transaction_rate->$rate_type;

        $is_valid = ($transaction_amount > $rate_amount_limit) ? true : false;

        $this->updateTransactionRate($rate_id, $transaction_rate->user_id, $unique_id, $is_valid);

        if ($is_valid) {

            if (!$this->has_valid_record && $is_valid) {
                $this->has_valid_record = true;
            }

            $subscriber_obj = $subscriber->getUserSubscriptionInfo($transaction_rate->user_id);

            $user = $factory->getQueryBuilder('#__users_users', 'uu', array('id=:id'), array('id' => $transaction_rate->user_id));

            $tmp_array['transaction_rate'] = $transaction_rate;
            $tmp_array['user'] = $user;
            $tmp_array['subscription'] = $subscriber_obj;

            $transaction_rate_str = $factory->renderString($tmp_array, $structure);

            file_put_contents($this->file_path, $transaction_rate_str, FILE_APPEND | LOCK_EX);
        }
    }

    public function updateTransactionRate($rate_id, $user_id, $unique_id, $is_valid = false) {


        $factory = new KazistFactory();


        $query = $this->getTransactionsRateQuery($rate_id);
        $query->select('ft.*');
        $query->where('ft.user_id=' . $user_id);

        $records = $query->loadObjectList();

        foreach ($records as $record) {

            $data_obj = new \stdClass();

            if (!$is_valid) {
                $this->processRefund($record);
                $data_obj->is_processed = 1;
            }

            $data_obj->id = $record->id;
            $data_obj->token = $unique_id;

            $factory->saveRecord('#__finance_transactions', $data_obj);
        }
    }

    public function processRefund($record) {

        $factory = new KazistFactory();
        $data_obj = new \stdClass();

        $data_obj->user_id = $record->user_id;
        $data_obj->behalf_user_id = $record->user_id;
        $data_obj->created_by = $record->user_id;
        $data_obj->item_id = $record->item_id;
        $data_obj->payment_id = $record->payment_id;
        $data_obj->source = $record->source;
        $data_obj->type = 'refund';
        $data_obj->description = 'REFUND: ' . $record->description . '[Txn:' . $record->id . ']';

        if ($record->debit) {
            $data_obj->credit = $record->debit;
        } elseif ($record->credit) {
            $data_obj->debit = $record->credit;
        }

        $factory->saveRecord('#__finance_transactions', $data_obj);
    }

    public function countTransactionsRates($rate_id) {

        $factory = new KazistFactory();


        $query = $this->getTransactionsRateQuery($rate_id);
        $query->select(' COUNT(DISTINCT ft.user_id) as total');

        $total = $query->loadResult();

        return $total;
    }

    public function getTransactionsRates($rate_id, $limit) {

        $factory = new KazistFactory();


        $limit = ($limit) ? $limit : 2000;

        $query = $this->getTransactionsRateQuery($rate_id);
        $query->select('ft.user_id, SUM(credit) as credit, SUM(debit) as debit');
        $query->group('user_id');

        $query->setFirstResult(0);
        $query->setMaxResults($limit);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getTransactionsRateQuery($rate_id) {

        $factory = new KazistFactory();


        $end_date = $this->next_effected_on_date;

        switch ($this->effected_on) {
            case 'weekly':
                $date = strtotime("-7 day", strtotime($end_date));
                break;
            case 'monthly':
                $date = strtotime("-1 month", strtotime($end_date));

                break;
            case 'yearly':
                $date = strtotime("-1 year", strtotime($end_date));
        }

        $start_date = date('Y-m-d 00:00:00', $date);

        $query = new Query();
        $query->from('#__finance_transactions', 'ft');

        if ($rate_id && $this->next_effected_on_date <> '') {
            $query->where('ft.date_created BETWEEN :start_date AND :end_date');
            $query->andWhere('ft.rate_id=:rate_id');
            $query->andWhere('ft.token=\'\' OR ft.token IS NULL');
            $query->setParameter('rate_id', $rate_id);
            $query->setParameter('start_date', $start_date);
            $query->setParameter('end_date', $end_date);
        } else {
            $query->where('1=-1');
        }


        return $query;
    }

    public function getChargeRates() {

        $where_arr = array();
        $where_arr[] = 'fr.effected_on=:weekly';
        $where_arr[] = 'fr.effected_on=:monthly';
        $where_arr[] = 'fr.effected_on=:yearly';

        $query = new Query();
        $query->select('fr.*');
        $query->from('#__finance_rates', 'fr');
        $query->where(implode(' OR ', $where_arr));
        $query->andWhere('fr.next_effected_on_date < NOW()');
        $query->setParameter('weekly', 'weekly');
        $query->setParameter('monthly', 'monthly');
        $query->setParameter('yearly', 'yearly');

        $records = $query->loadObjectList();


        return $records;
    }

    public function prepareFile($unique_id, $file_name, $file_ext = 'txt') {

        $factory = new KazistFactory();

        $unique_id = ($this->file_is_unique ) ? '-' . $unique_id : '';
        $file_ext = ($file_ext == '') ? $file_ext : 'txt';

        $year = date('Y');
        $month = date('M');
        $date = date('d');

        $this->relative_path = 'uploads/finance/rates/files/' . $year . '/' . $month . '/' . $date . '/';
        $this->web_path = $this->relative_path . $file_name . $unique_id . '.' . $file_ext;
        $this->absolute_path = rtrim(JPATH_ROOT, '/') . '/' . $this->relative_path;
        $this->file_path = rtrim(JPATH_ROOT, '/') . '/' . $this->web_path;

        $factory->makeDir($this->absolute_path);

        return $this->web_path;
    }

    private function getNextEffectedOnDate() {


        switch ($this->effected_on) {
            case 'weekly':
                $cron_str = '0 0 * * 0,7';
                break;
            case 'monthly':
                $cron_str = '0 0 1 * *';

                break;
            case 'yearly':
                $cron_str = '0 0 1 1 *';
        }

        $cron = CronExpression::factory($cron_str);
        $next_run_time = $cron->getNextRunDate()->format('Y-m-d H:i:s');


        return $next_run_time;
    }

}
