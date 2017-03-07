<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of RatesController
 *
 * @author sbc
 */

namespace Finance\Rates\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Finance\Rates\Code\Models\RatesModel;

class RatesController extends BaseController {

    public function crongeneratefileAction() {

        $ratesModel = new RatesModel();
        $ratesModel->generateRatesFile();
    }

    public function completeprocessAction() {

        $ratesModel = new RatesModel();
        $ratesModel->completeProcess();
    }

}
