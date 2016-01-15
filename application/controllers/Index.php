<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:47
 */

namespace Controllers;

use Spf\Core;

class index extends Core\Controller
{
    function init()
    {
        $this->getView()->setLayout('layout/default');
    }

    public function execute()
    {
//        $this->view->displayJson(array('name'=>'zhxia'));
        $this->getView()->display('index');
    }

}