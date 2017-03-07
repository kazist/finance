<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of TransactionsController
 *
 * @author sbc
 */

namespace Finance\Transactions\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;

class TransactionsController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $view_name = $this->request->get('view');

        $this->data_arr['levelsinput'] = $this->model->getLevelsInput();
        $this->data_arr['typesinput'] = $this->model->getTypesInput();
        $this->data_arr['user_id'] = $this->request->get('user_id');
        $this->data_arr['show_action'] = false;
        $this->data_arr['show_search'] = true;
        $this->data_arr['show_messages'] = true;

        if ($view_name == 'commission') {
            $this->data_arr['action_type'] = 'commission';
        }

        return parent::indexAction($offset, $limit);
    }

    public function addAction() {

        $user_id = $this->request->query->get('user_id');

        $user = $this->model->getUserById($user_id);

        $this->data_arr['user'] = $user;

        return parent::addAction();
    }



}
