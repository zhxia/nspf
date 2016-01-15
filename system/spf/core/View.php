<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/14
 * Time: 10:27
 */

namespace Spf\Core;


class View
{
    private $_tplPath;
    private $_layoutFile;
    private $_tpl;
    private $_vars = array();
    private $_javascriptBlocks = array();
    private $_title;
    private $_meta = array();
    private $_styles = array();
    private $_javascript = array();

    function __construct($tplPath = null)
    {
        $this->_tplPath = $tplPath;
    }

    public function setPath($path)
    {
        $this->_tplPath = $path;
    }

    public function setLayout($tpl)
    {
        $this->_layoutFile = $tpl;
    }

    public function assign($name, $value)
    {
        $this->_vars[$name] = $value;
    }

    public function assignData($data)
    {
        if (is_array($data)) {
            $this->_vars = array_merge($this->_vars, $data);
        }
    }

    public function displayJson($data)
    {
        Application::getInstance()->getDispatcher()->getResponse()->setContentType('application/Json');
        echo json_encode($data);

    }

    public function displayJsonp($callback, $data)
    {
        Application::getInstance()->getDispatcher()->getResponse()->setContentType('application/javascript');
        echo '
            try{
                ' . $callback . '(' . json_encode($data) . ');
            }
            catch(e){
            }
        ';
    }

    public function render($tpl, $vars = array())
    {
        if ($vars) {
            $this->_vars = array_merge($this->_vars, $vars);
        }
        $this->_tpl = $tpl;
        if ($this->_layoutFile) {
            return $this->renderView($this->_layoutFile);
        }
        return $this->renderView($tpl);
    }

    public function display($tpl, $vars = array())
    {
        if ($vars) {
            $this->_vars = array_merge($this->_vars, $vars);
        }
        $this->_tpl = $tpl;
        if ($this->_layoutFile) {
            echo $this->renderView($this->_layoutFile);
        } else {
            echo $this->renderView($tpl);
        }
    }

    /**
     * 设置页面的title信息
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    public function getTitle()
    {
        if (empty($this->_title)) {
            $this->_title = '';
        }
        return $this->_title;
    }

    public function addMeta($meta)
    {
        if (is_array($meta)) {
            foreach ($meta as $val) {
                $this->_meta[] = $val;
            }
        } else {
            $this->_meta[] = strval($meta);
        }
        return $this;
    }

    public function getMeta()
    {
        return $this->_meta;
    }

    /**
     * 给页面添加外部样式文件
     * @param array $styleFiles
     * @return $this
     */
    public function addStyles($styleFiles = array())
    {
        if (is_array($styleFiles)) {
            $this->_styles = array_merge($this->_styles, $styleFiles);
        } else {
            $this->_styles[] = $styleFiles;
        }
        return $this;
    }

    public function getStyles()
    {
        return $this->_styles;
    }

    public function addJavascript($javascript = array(), $is_head = FALSE)
    {
        if ($is_head) {
            foreach ($javascript as $val) {
                if (!is_array($val)) {
                    $val = (array)$val;
                }
                $this->_javascript[1][] = $val;
            }
        } else {
            foreach ($javascript as $val) {
                if (!is_array($val)) {
                    $val = (array)$val;
                }
                $this->_javascript[2][] = $val;
            }
        }
        return $this;
    }

    public function getJavascript($is_head = FALSE)
    {
        $this->sortJsResource($this->_javascript);
        return $is_head ? (isset($this->_javascript[1]) ? $this->_javascript[1] : NULL) : (isset($this->_javascript[2]) ? $this->_javascript[2] : NULL);
    }

    /**
     *
     * 对资源进行排序
     * @param array $javascripts
     */
    protected function sortJsResource(&$javascripts)
    {
        foreach ($javascripts as &$javascript) {
            foreach ($javascript as &$val) {
                if (!isset($val[1])) {
                    $val[1] = 0;
                }
            }
            uasort($javascript, array($this, 'cmpPriority'));
        }
    }

    /**
     * 自定义排序方法
     * @param $arr_1
     * @param $arr_2
     * @return int
     */
    private function cmpPriority($arr_1, $arr_2)
    {
        if ($arr_1[1] > $arr_2[1]) {
            return -1;
        } elseif ($arr_1[1] == $arr_2[1]) {
            return 0;
        } else {
            return 1;
        }
    }

    protected function  sortScriptBlock(&$javascriptBlocks)
    {
        uasort($javascriptBlocks, array($this, 'cmpPriority'));
    }

    /**
     *
     * 获取页面块javascript
     */
    public function getJavascriptBlocks()
    {
        $this->sortScriptBlock($this->_javascriptBlocks);
        $content = array();
        foreach ($this->_javascriptBlocks as &$block) {
            $content[] = trim(preg_replace('/^\s*<script[^>]*>(.*)<\/script\s*>/ims', '$1', $block[0]));
        }
        return implode('', $content);
    }

    protected function scriptBlockBegin()
    {
        ob_start();
    }

    protected function scriptBlockEnd($priority = 0)
    {
        $js = ob_get_contents();
        $this->_javascriptBlocks[] = array($js, $priority);
        ob_end_clean();
    }

    protected function getSubView()
    {
        return $this->renderView($this->_tpl);
    }

    protected function renderView($tpl)
    {
        $viewFile = $this->getViewFile($tpl);
        ob_start();
        extract($this->_vars);
        include "{$viewFile}";
        $viewContent = ob_get_contents();
        ob_end_clean();
        return $viewContent;
    }

    protected function getViewFile($tpl)
    {
        if (!$this->_tplPath) {
            $this->_tplPath = APP_PATH . 'views' . DIRECTORY_SEPARATOR;
        }
        $viewFile = $this->_tplPath . $tpl . '.' . PAGE_EXT;
        if (!file_exists($viewFile)) {
            trigger_error('view file:"' . $viewFile . '" not exist!', E_USER_ERROR);
        }
        return $viewFile;
    }
}