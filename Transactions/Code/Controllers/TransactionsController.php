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

namespace Finance\Transactions\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;

class TransactionsController extends BaseController {

    public $model_class = 'Finance\Transactions\Code\Models\TransactionsModel';

    public function indexAction($offset = 0, $limit = 10) {

        $view = $this->request->query->get('view');

        $this->data_arr['levelsinput'] = $this->model->getLevelsInput();
        $this->data_arr['typesinput'] = $this->model->getTypesInput();
        
        if ($view == 'commission') {
            $this->data_arr['params'] = array('view' => 'commission');
        }
        return parent::indexAction($offset, $limit);
    }

}
