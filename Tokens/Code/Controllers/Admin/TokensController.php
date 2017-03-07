<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of TokensController
 *
 * @author sbc
 */

namespace Finance\Tokens\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Finance\Tokens\Code\Models\TokensModel;

class TokensController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $usedinput_arr = array();
        $usedinput_arr[] = array('value' => 1, 'text' => 'Yes');
        $usedinput_arr[] = array('value' => 0, 'text' => 'No');

        $this->data_arr['usedinput'] =$usedinput_arr;
        $this->data_arr['show_action'] = false;
        $this->data_arr['show_search'] = true;
        $this->data_arr['show_messages'] = true;

        return parent::indexAction($offset, $limit);
    }

    public function addAction() {

        $this->model = new TokensModel();

        $types = $this->model->getTypes();
        $item = $this->model->getRecord();


        $data_arr['types'] = $types;
        $data_arr['action_type'] = 'add';
        $data_arr['item'] = $item;
        $data_arr['show_action'] = true;
        $data_arr['show_message'] = true;

        $this->html = $this->render('Finance:Tokens:Code:views:admin:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function tokengroupAction() {

        $this->model = new TokensModel();

        $tokens = $this->model->getUniqueGroupTokens();

        $data_arr['tokens'] = $tokens;

        $this->html = $this->render('Finance:Tokens:Code:views:admin:unique_name_token.index.twig', $data_arr);


        $response = $this->response($this->html);


        print_r($response->getContent());
        exit;

        return $response;
    }

    public function editAction() {

        $factory = new KazistFactory();
        $factory->enqueueMessage('Token can not be edited');

        return $this->redirectToRoute('admin.finance.tokens');
    }

    function saveAction() {

        $factory = new KazistFactory();
        $factory->enqueueMessage('Token can not be edited');

        $this->model = new TokensModel();
        $this->model->generateToken();

        return $this->redirectToRoute('admin.finance.tokens');
    }

    function deleteAction() {

        $this->model = new TokensModel();
        $this->model->deleteToken();
    }

}
