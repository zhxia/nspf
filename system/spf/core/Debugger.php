<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 1/17/16
 * Time: 1:12 PM
 */

namespace Spf\Core;


class Debugger
{
    const DEFAULT_BENCHMARK = 'SPF';
    const MESSAGE_TIME = 't';
    const MESSAGE_CONTENT = 'c';
    const MESSAGE_MEMORY = 'm';
    const BENCHMARK_BEGIN = 'b';
    const BENCHMARK_END = 'e';
    const BENCHMARK_BEGIN_MEMORY = 'bm';
    const BENCHMARK_END_MEMORY = 'em';
    private $_benchmarks = array();
    private $_messages = array();
    private static $instance = null;
    private $_enabled = true;

    private function __construct()
    {
        $this->benchmarkBegin(self::DEFAULT_BENCHMARK);
        Application::getInstance()->registerShutdownFunctions(array($this,'shutdown'));
    }

    /**
     * @return null|Debugger
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setEnabled($enabled)
    {
        $this->_enabled = $enabled;
    }

    public function shutdown()
    {
        $this->benchmarkEnd(self::DEFAULT_BENCHMARK);
        if ($this->_enabled) {
            $this->showDebugInfo();
        }
    }

    public function debug($message)
    {
        if (!$this->_enabled) {
            return false;
        }
        $this->_messages[] = array(
            self::MESSAGE_TIME => microtime(true) - $this->_benchmarks[self::DEFAULT_BENCHMARK][self::BENCHMARK_BEGIN],
            self::MESSAGE_CONTENT => $message,
            self::MESSAGE_MEMORY => $this->getMemoryUsage(),
        );
    }

    public function benchmarkBegin($name)
    {
        $this->_benchmarks[$name][self::BENCHMARK_BEGIN] = microtime(true);
        $this->_benchmarks[$name][self::BENCHMARK_BEGIN_MEMORY] = $this->getMemoryUsage();
    }

    public function benchmarkEnd($name)
    {
        $this->_benchmarks[$name][self::BENCHMARK_END] = microtime(true);
        $this->_benchmarks[$name][self::BENCHMARK_END_MEMORY] = $this->getMemoryUsage();
    }


    protected function getMemoryUsage()
    {
        return function_exists('memory_get_usage') ? memory_get_usage() : 0;
    }

    protected function showDebugInfo()
    {
        //显示benchmark
        $arr_table[] = '<table style="border-collapse:collapse" border="1" cellpadding="5" cellspacing="0">';
        $arr_table[] = '<caption>BENCHMARKS</caption>';
        $arr_table[] = '<thead><tr><th>NAME</th><th>TIME</th><th>MEMORY (byte)</th></tr></thead>';
        $arr_table[] = '<tbody>';
        if ($this->_benchmarks) {
            foreach ($this->_benchmarks as $name => $benchmark) {
                $time_cost = $benchmark[self::BENCHMARK_END] - $benchmark[self::BENCHMARK_BEGIN];
                $mem_cost = $benchmark[self::BENCHMARK_END_MEMORY] - $benchmark[self::BENCHMARK_BEGIN_MEMORY];
                $arr_table[] = '<tr><td>' . $name . '</td><td>' . number_format($time_cost, 3) . '</td><td>' . number_format($mem_cost) . '</td></tr>';
            }
        }
        $arr_table[] = '</tbody></table>';
        //显示debug
        $arr_table[] = '<table style="border-collapse:collapse" border="1" cellpadding="5" cellspacing="0">';
        $arr_table[] = '<caption>DEBUG INFO</caption>';
        $arr_table[] = '<thead><tr><th>TIME</th><th>MEMORY (byte)</th><th>MESSAGE</th></tr></thead>';
        $arr_table[] = '<tbody>';
        if ($this->_messages) {
            foreach ($this->_messages as $message) {
                $arr_table[] = '<tr><td>' . $message[self::MESSAGE_TIME] . '</td><td>' . number_format($message[self::MESSAGE_MEMORY], 3) . '</td><td>' . $message[self::MESSAGE_CONTENT] . '</td></tr>';
            }
        }
        $arr_table[] = '</tbody></table>';
        echo implode('', $arr_table);
    }
}