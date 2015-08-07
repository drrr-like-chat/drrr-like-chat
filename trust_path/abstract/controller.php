<?php

abstract class Dura_Abstract_Controller
{
    protected $output = array();
    protected $template = null;

    public function __construct()
    {
    }

    public function main()
    {
    }

    protected function _view()
    {
        if (!$this->template) {
            $this->template = DURA_TEMPLATE_PATH . '/' . Dura::$controller . '.' . Dura::$action . '.php';
        }

        $this->_escapeHtml($this->output);

        ob_start();
        $this->_display($this->output);
        $content = ob_get_contents();
        ob_end_clean();

        $this->_render($content);
    }

    protected function _display($dura)
    {
        require $this->template;
    }

    protected function _render($content)
    {
        require DURA_TEMPLATE_PATH . '/theme.php';
    }

    protected function _validateUser()
    {
        if (!Dura::user()->isUser()) {
            Dura::redirect();
        }
    }

    protected function _validateAdmin()
    {
        if (!Dura::user()->isAdmin()) {
            Dura::redirect();
        }
    }

    protected function _escapeHtml(&$vars)
    {
        foreach ($vars as &$var) {
            if (is_array($var)) {
                $this->_escapeHtml($var);
            } elseif (!is_object($var)) {
                $var = Dura::escapeHtml($var);
            }
        }
    }
}
