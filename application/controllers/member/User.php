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
use Spf\Libraries\Cache\CacheFactory;

class UserController extends Controller
{
    function init()
    {
        $this->getView()->setLayout('layout/default');
    }

    public function execute()
    {
        $userModel = new UserModel();
        $data=$userModel->getList();
//        print_r($data);
        $cnt=$userModel->getTotal();
//        var_dump($cnt);
        $ret=$userModel->updateUser();
//        var_dump($ret);
        /*$data = array();
        $count = 10000;
        while ($count != 0) {
            $data[] = array(
                'name' => '张三' . $count--
            );
        }
        $ar = $userModel->addUsers($data);
        var_dump($ar);*/
//        $id=$userModel->addUser(array('name'=>'刘辉'));
//        var_dump($id);
        /* $data=array(
             'name'=>'zhxia',
             'age'=>110
         );*/
//        $this->getView()->displayJson($data);
        $redis=CacheFactory::getInstance()->getRedis();
        $redis->set("name",'zhxia84');
        print_r($redis->get('name'));
        $redis->mset(array('a1'=>'a1','a2'=>'a2'));
        print_r($redis->mget(array('a1','a2')));
        $redis->incr('a');
        echo $redis->get('a');

        /*$memcached=CacheFactory::getInstance()->getMemcached();
        $memcached->set("a",'aaaaa');
        echo $memcached->get('aa');*/
    }

}