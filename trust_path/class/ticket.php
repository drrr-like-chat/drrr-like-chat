<?php

class Dura_Class_Ticket
{
    protected static $sessionName = 'dura_tickets';

    public static function issue($timeout = 180)
    {
        $expire = time() + intval($timeout);
        $token = md5(uniqid() . mt_rand());

        if (isset($_SESSION[self::$sessionName]) and is_array($_SESSION[self::$sessionName])) {
            if (count($_SESSION[self::$sessionName]) >= 5) {
                asort($_SESSION[self::$sessionName]);
                $_SESSION[self::$sessionName] = array_slice($_SESSION[self::$sessionName], -4, 4);
            }

            $_SESSION[self::$sessionName][$token] = $expire;
        } else {
            $_SESSION[self::$sessionName] = array($token => $expire);
        }

        return $token;
    }

    public static function check($stub)
    {
        if (!isset($_SESSION[self::$sessionName][$stub])) {
            return false;
        }
        if (time() >= $_SESSION[self::$sessionName][$stub]) {
            return false;
        }

        unset($_SESSION[self::$sessionName][$stub]);

        return true;
    }

    public static function destory()
    {
        unset($_SESSION[self::$sessionName]);
    }
}
