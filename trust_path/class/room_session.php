<?php

class Dura_Class_RoomSession
{
    public static function isCreated()
    {
        return isset($_SESSION['room']);
    }

    public static function get($var = null)
    {
        if ($var) {
            return $_SESSION['room'][$var];
        }

        return $_SESSION['room'];
    }

    public static function create($id)
    {
        $_SESSION['room']['id'] = $id;
    }

    public static function delete()
    {
        unset($_SESSION['room']);
    }
}
