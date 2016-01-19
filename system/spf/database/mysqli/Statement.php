<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/18
 * Time: 17:09
 */

namespace Spf\Database\Mysqli;


use Spf\Core\Debugger;

class Statement extends \MySQLi_STMT
{
    /**
     * @param \mysqli $mysqli
     * @param string $query
     */
    function __construct($mysqli, $query)
    {
        parent::__construct($mysqli, $query);
    }

    public function prepare($query)
    {
        Debugger::getInstance()->debug('SQL:' . $query);
        return parent::prepare($query);
    }

    public function execute()
    {
        return parent::execute();
    }
}