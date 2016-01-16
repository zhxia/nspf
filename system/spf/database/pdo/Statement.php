<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 11:00
 */

namespace Spf\Database\Pdo;


class Statement extends \PDOStatement
{
    /**
     * @var
     */
    private $_pdo;

    /**
     *
     * 此处不能使用public类型的构造方法
     * @param $pdo
     */
    protected function __construct($pdo)
    {
        $this->_pdo = $pdo;
    }

    public function execute($input_parameters = array())
    {
        $ret = parent::execute($input_parameters);
        Spf_Debugger::get_instance()->debug('SQL:' . $this->queryString . ';params:' . var_export($input_parameters, true));
        if (!$ret) {
            $error_info = parent::errorInfo();
            if (parent::errorCode() != '00000') { // 执行成功时返回五个零
                Spf_Log_LoggerFactory::get_logger()->error('PDO executed failed,errors:' . var_export($error_info, true));
                trigger_error($this->queryString, E_USER_ERROR);
            }
        }
        return $ret;
    }
}