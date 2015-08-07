<?php

class Dura_Controller_Logout extends Dura_Abstract_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function main()
    {
        if (!Dura::user()->isUser()) {
            Dura::redirect();
        }

        $this->_default();
    }

    protected function _default()
    {
        session_destroy();

        Dura::redirect();
    }
}
