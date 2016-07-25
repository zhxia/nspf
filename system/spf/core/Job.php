<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/29
 * Time: 14:40
 */

namespace Spf\Core;

set_time_limit(0);

abstract class Job
{

    private $lockEnabled = false;
    private $fp = null;

    /**
     * @param boolean $lockEnabled
     */
    public function setLockEnabled($lockEnabled)
    {
        $this->lockEnabled = $lockEnabled;
    }


    function __construct()
    {
        if ($this->lockEnabled) {
            $className = get_class($this);
            $lockFile = '/tmp/' . $className . '.lock';
            $this->fp = fopen($lockFile, 'c');
            if (!$this->fp) {
                $this->log('lock:"' . $lockFile . '" created failed!');
                exit();
            }
            if (!flock($this->fp, LOCK_EX | LOCK_NB)) {
                $this->log('get lock file failed!maybe another process is running...');
                fclose($this->fp);
                exit();
            }
        }
    }

    function __destruct()
    {
        if ($this->lockEnabled && $this->fp) {
            flock($this->fp, LOCK_UN);
        }
        if ($this->fp) {
            fclose($this->fp);
        }
    }

    protected function log($message)
    {
        echo '[' . date('Y-m-d H:i:s') . ']' . $message . PHP_EOL;
    }

    public function run($args)
    {
        $className = get_class($this);
        $this->log('Job:' . $className . ' is Running...');
        try {
            call_user_func(arrar($this, 'execute'), $args);
        } catch (Exception $e) {
            $this->log('Exception:' . $e->getMessage());
        }
        $this->log('Job:' . $className . ' is Finished!');
    }

    abstract protected function execute();
}

