<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 1/17/16
 * Time: 10:07 AM
 */

namespace Controllers\Member;


use Spf\Core\Controller;

class User extends Controller
{
    public function execute()
    {
        $data = array(
            'name' => 'zhxia',
            'age' => 31,
        );
        $this->getView()->displayJson($data);
    }

}