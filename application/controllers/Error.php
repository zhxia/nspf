<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 1/15/16
 * Time: 10:11 PM
 */

namespace Controllers;


use Spf\Core\Controller;

class ErrorController extends Controller
{
    public function execute()
    {
        $data = array(
            'title' => '标题'
        );
        $this->getView()->display('error/Error404', $data);
    }

}