<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/13
 * Time: 17:47
 */

namespace Controllers;

use Models\User;
use Spf\Core;

class Index extends Core\Controller
{
    function init()
    {
        $this->getView()->setLayout('layout/default');
    }

    public function execute()
    {
        $userModel=new User();
        $data=$userModel->getList();
//        $this->view->displayJson(array('name'=>'zhxia'));
//        $this->getView()->display('index');
        $this->getView()->assign('data',$data);
        return 'index';
    }

}