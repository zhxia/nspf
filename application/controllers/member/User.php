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
        $userModel=new UserModel();
//        $data=$userModel->getList();
        $data=array(
            'name'=>'zhxia',
            'age'=>110
        );
        $this->getView()->displayJson($data);
    }

}