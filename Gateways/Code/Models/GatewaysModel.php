<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Gateways\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Database\Query;

/**
 * Description of QuestionModel
 *
 * @author sbc
 */
class GatewaysModel extends BaseModel {

    public function saveGatewayDetail($gateway_id, $form) {

        $payment_rates = $form['payment_rates'];
        $transfer_rates = $form['transfer_rates'];
        $withdraw_rates = $form['withdraw_rates'];
        $allowed_in = $form['allowed_in'];
        $disallowed_in = $form['disallowed_in'];
        $new_parameters = $form['parameter']['new'];
        $existing_parameters = $form['parameter']['exist'];

        $this->savePaymentRates($gateway_id, $payment_rates);
        $this->saveTransferRates($gateway_id, $transfer_rates);
        $this->saveWithdrawRates($gateway_id, $withdraw_rates);
        $this->saveAllowedIn($gateway_id, $allowed_in);
        $this->saveDisallowedIn($gateway_id, $disallowed_in);
        $this->saveParameters($gateway_id, $existing_parameters, 'exist');
        $this->saveParameters($gateway_id, $new_parameters, 'new');
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Save Allowed In
    public function saveParameters($gateway_id, $parameters, $type) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($parameters['name'])) {
            foreach ($parameters['name'] as $key => $param_name) {

                $where_arr = array();
                $parameter_arr = array();
                $param_is_private = $parameters['is_private'][$key];
                $param_value = $parameters['value'][$key];

                if ($type == 'exist') {
                    $where_arr = array('id=:id');
                    $parameter_arr = array('id' => $key);
                }

                $data_obj = new \stdClass();
                $data_obj->name = $param_name;
                $data_obj->value = $param_value;
                $data_obj->is_private = $param_is_private;
                $data_obj->gateway_id = $gateway_id;

                $existing_arr[] = $factory->saveRecord('#__finance_gateways_parameters', $data_obj, $where_arr, $parameter_arr);
            }

            if ($type == 'exist') {
                $this->deleteRemovedParameters($gateway_id, $existing_arr);
            }
        }
    }

    public function deleteRemovedParameters($gateway_id, $existing_arr) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->delete('#__finance_gateways_parameters');
        $query->where('gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);
        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
        //  print_r((string)$query); exit;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Save Allowed In
    public function saveAllowedIn($gateway_id, $allowed_in) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($allowed_in)) {
            foreach ($allowed_in as $country_id) {

                $data_obj = new \stdClass();
                $data_obj->country_id = $country_id;
                $data_obj->gateway_id = $gateway_id;

                $where_arr = array('country_id=:country_id', 'gateway_id=:gateway_id');
                $parameter_arr = (array) $data_obj;

                $existing_arr[] = $factory->saveRecord('#__finance_gateways_allowedin', $data_obj, $where_arr, $parameter_arr);
            }
        }

        $this->deleteRemovedAllowedIn($gateway_id, $existing_arr);
    }

    public function deleteRemovedAllowedIn($gateway_id, $existing_arr) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->delete('#__finance_gateways_allowedin');
        $query->where('gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Save Disallowed In
    public function saveDisallowedIn($gateway_id, $disallowed_in) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($disallowed_in)) {
            foreach ($disallowed_in as $country_id) {

                $data_obj = new \stdClass();
                $data_obj->country_id = $country_id;
                $data_obj->gateway_id = $gateway_id;

                $where_arr = array('country_id=:country_id', 'gateway_id=:gateway_id');
                $parameter_arr = (array) $data_obj;

                $existing_arr[] = $factory->saveRecord('#__finance_gateways_disallowedin', $data_obj, $where_arr, $parameter_arr);
            }
        }

        $this->deleteRemovedDisallowedIn($gateway_id, $existing_arr);
    }

    public function deleteRemovedDisallowedIn($gateway_id, $existing_arr) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->delete('#__finance_gateways_disallowedin');
        $query->where('gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);
        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Save Payment Rates
    public function savePaymentRates($gateway_id, $payment_rates) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($payment_rates)) {
            foreach ($payment_rates as $payment_rate) {

                $data_obj = new \stdClass();
                $data_obj->gateway_id = $gateway_id;
                $data_obj->rate_id = (string) $payment_rate;

                $where_arr = array('rate_id=:rate_id', 'gateway_id=:gateway_id');
                $parameter_arr = (array) $data_obj;

                $existing_arr[] = $factory->saveRecord('#__finance_gateways_paymentrates', $data_obj, $where_arr, $parameter_arr);
            }
        }

        $this->deleteRemovedPaymentRates($gateway_id, $existing_arr);
    }

    public function deleteRemovedPaymentRates($gateway_id, $existing_arr) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->delete('#__finance_gateways_paymentrates');

        $query->where('gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Save Transfer Rates
    public function saveTransferRates($gateway_id, $transfer_rates) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($transfer_rates)) {
            foreach ($transfer_rates as $transfer_rate) {

                $data_obj = new \stdClass();
                $data_obj->gateway_id = $gateway_id;
                $data_obj->rate_id = $transfer_rate;

                $where_arr = array('rate_id=:rate_id', 'gateway_id=:gateway_id');
                $parameter_arr = (array) $data_obj;

                $existing_arr[] = $factory->saveRecord('#__finance_gateways_transferrates', $data_obj, $where_arr, $parameter_arr);
            }
        }

        $this->deleteRemovedTransferRates($gateway_id, $existing_arr);
    }

    public function deleteRemovedTransferRates($gateway_id, $existing_arr) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->delete('#__finance_gateways_transferrates');

        $query->where('gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Save Withdraw Rates
    public function saveWithdrawRates($gateway_id, $withdraw_rates) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($withdraw_rates)) {
            foreach ($withdraw_rates as $withdraw_rate) {

                $data_obj = new \stdClass();
                $data_obj->gateway_id = $gateway_id;
                $data_obj->rate_id = $withdraw_rate;

                $where_arr = array('rate_id=:rate_id', 'gateway_id=:gateway_id');
                $parameter_arr = (array) $data_obj;

                $existing_arr[] = $factory->saveRecord('#__finance_gateways_withdrawrates', $data_obj, $where_arr, $parameter_arr);
            }
        }

        $this->deleteRemovedWithdrawRates($gateway_id, $existing_arr);
    }

    public function deleteRemovedWithdrawRates($gateway_id, $existing_arr) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->delete('#__finance_gateways_withdrawrates');

        $query->where('gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
    }

    //put your code here
    // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Gateway Payment Rates 

    public function getGatewayPaymentRates($gateway_id) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('fgp.*');
        $query->from('#__finance_gateways_paymentrates', 'fgp');
        $query->where('fgp.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getGatewayPaymentRatesArr($gateway_id) {

        $tmp_array = array();

        $records = $this->getGatewayPaymentRates($gateway_id);

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = $record->rate_id;
            }
        }

        return $tmp_array;
    }

    // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Gateway Withdraw Rates 
    public function getGatewayWithdrawRates($gateway_id) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('fgp.*');
        $query->from('#__finance_gateways_withdrawrates', 'fgp');
        $query->where('fgp.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getGatewayWithdrawRatesArr($gateway_id) {

        $tmp_array = array();

        $records = $this->getGatewayWithdrawRates($gateway_id);

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = $record->rate_id;
            }
        }

        return $tmp_array;
    }

    // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Gateway Tranfer Rates 
    public function getGatewayTransferRates($gateway_id) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('fgp.*');
        $query->from('#__finance_gateways_transferrates', 'fgp');
        $query->where('fgp.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getGatewayTransferRatesArr($gateway_id) {

        $tmp_array = array();

        $records = $this->getGatewayTransferRates($gateway_id);

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = $record->rate_id;
            }
        }

        return $tmp_array;
    }

    // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Gateway Country Allowed In 
    public function getGatewayCountryAllowedIn($gateway_id) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('fgp.*');
        $query->from('#__finance_gateways_allowedin', 'fgp');
        $query->where('fgp.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getGatewayCountryAllowedInArr($gateway_id) {

        $tmp_array = array();

        $records = $this->getGatewayCountryAllowedIn($gateway_id);

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = $record->country_id;
            }
        }

        return $tmp_array;
    }

    // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Gateway Country Disallowed In 
    public function getGatewayCountryDisallowedIn($gateway_id) {

        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('fgp.*');
        $query->from('#__finance_gateways_disallowedin', 'fgp');
        $query->where('fgp.gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getGatewayCountryDisallowedInArr($gateway_id) {

        $tmp_array = array();

        $records = $this->getGatewayCountryDisallowedIn($gateway_id);

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = $record->country_id;
            }
        }

        return $tmp_array;
    }

    // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Charges Rates

    public function getChargeRates() {


        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('fr.*');
        $query->from('#__finance_rates', 'fr');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getChargeRatesInput() {

        $tmp_array = array();

        $records = $this->getChargeRates();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->title);
            }
        }

        return $tmp_array;
    }

    // xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Gateway Parameters

    public function getParameters($gateway_id) {


        $factory = new KazistFactory();
        $db = $factory->getDatabase();

        $query = new Query();
        $query->select('fgp.*');
        $query->from('#__finance_gateways_parameters', 'fgp');
        $query->where('gateway_id=:gateway_id');
        $query->setParameter('gateway_id', $gateway_id);

        $records = $query->loadObjectList();

        return $records;
    }

}
