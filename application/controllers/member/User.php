<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 1/17/16
 * Time: 10:07 AM
 */

namespace Controllers\Member;


use Models\UserModel;
use Spf\Core\Controller;

class UserController extends Controller
{
    public function execute()
    {
        $userModel = new UserModel();
        /*$data=$userModel->getList();
        print_r($data);
        $cnt=$userModel->getTotal();
        var_dump($cnt);
        $ret=$userModel->updateUser();
        var_dump($ret);*/
        $ar = $userModel->addUsers(array(
            array('name' => '王五'),
            array('name'=>'钱琦'),
        ));
        var_dump($ar);
//        $id=$userModel->addUser(array('name'=>'刘辉'));
//        var_dump($id);
        /* $data=array(
             'name'=>'zhxia',
             'age'=>110
         );*/
//        $this->getView()->displayJson($data);
    }

}