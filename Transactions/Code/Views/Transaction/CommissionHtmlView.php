<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Finance\Transaction\Views\Transaction;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazicode\Service\KazistFactory;
use Kazicode\View\Table\TableHtmlView as GeneralTableHtmlView;

/**
 * News HTML view class for the application
 *
 * @since  1.0
 */
class CommissionHtmlView extends GeneralTableHtmlView {

    public function prepare() {

        parent::prepare();
        $this->setPageSeoParams();
    }

    public function setPageSeoParams() {
        
        $factory = new KazistFactory();
        $input = $factory ->getInput();
        $user_id = $input->get('user_id');

        $user = $factory ->getUserByParam($user_id, 'id');
        
        parent::setPageTitle('Commission List');
        parent::setPageHeaderExplanation('Commission List ' . $user->name);
        parent::prepare();
    }

}
