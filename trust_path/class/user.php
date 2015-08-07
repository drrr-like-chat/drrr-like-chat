<?php

class Dura_Class_User
{
    protected $name = null;
    protected $icon = null;
    protected $id = null;
    protected $expire = null;
    protected $admin = false;
    protected $language = null;

    protected function __construct()
    {
    }

    public static function &getInstance()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new self();
        }

        return $instance;
    }

    public function login($name, $icon, $language, $admin = false)
    {
        $this->name = $name;
        $this->icon = $icon;
        $this->id = md5($name . getenv('REMOTE_ADDR'));
        $this->language = $language;
        $this->admin = $admin;

        $_SESSION['user'] = $this;
    }

    public function loadSession()
    {
        if (isset($_SESSION['user']) and $_SESSION['user'] instanceof self) {
            $user = $_SESSION['user'];
            $this->name = $user->name;
            $this->icon = $user->icon;
            $this->id = $user->id;
            $this->expire = $user->expire;
            $this->id = $user->id;
            $this->language = $user->language;
            $this->admin = $user->admin;
        }
    }

    public function isUser()
    {
        return ($this->id !== null);
    }

    public function isAdmin()
    {
        if ($this->isUser()) {
            return $this->admin;
        }

        return false;
    }

    public function getName()
    {
        if (!$this->isUser()) {
            return false;
        }

        return $this->name;
    }

    public function getIcon()
    {
        if (!$this->isUser()) {
            return false;
        }

        return $this->icon;
    }

    public function getId()
    {
        if (!$this->isUser()) {
            return false;
        }

        return $this->id;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getExpire()
    {
        if (!$this->isUser()) {
            return false;
        }

        return $this->expire;
    }

    public function updateExpire()
    {
        $this->expire = time() + DURA_TIMEOUT;

        if (isset($_SESSION['user']) and $_SESSION['user'] instanceof self) {
            $_SESSION['user']->expire = $this->expire;
        }
    }
}
