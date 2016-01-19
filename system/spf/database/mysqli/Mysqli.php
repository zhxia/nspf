<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/18
 * Time: 17:08
 */

namespace Spf\Database\Mysqli;


use Spf\Core\Debugger;

class Mysqli extends \MySQLi
{
    function __construct($host, $username, $password, $database, $port = 3306, $socket = '')
    {
        parent::__construct($host, $username, $password, $database, $port, $socket);
        $message = sprintf('connect to mysql,host:%s,port:%s,username:%s,password:%s,database:%s', $host, $port, $username, $password, $database);
        Debugger::getInstance()->debug($message);
    }

    /**
     * @param string $query
     * @param int $resultMode
     * @return bool|\mysqli_result
     */
    public function query($query, $resultMode = MYSQLI_STORE_RESULT)
    {
        Debugger::getInstance()->debug('SQL:' . $query);
        return parent::query($query, $resultMode);
    }

    /**
     * @param string $query
     * @return Statement
     */
    public function prepare($query)
    {
        Debugger::getInstance()->debug('SQL:' . $query);
        return new Statement($this, $query);
    }

}