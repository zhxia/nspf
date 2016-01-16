<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:13
 */

namespace Spf\Core\Logger;


/**
 * Interface ILogger
 * @package Spf\Core\Logger
 */
interface ILogger
{
    function __construct($priority);

    function log();

    function debug();

    function info();

    function notice();

    function warn();

    function error();

    function fatal();
}