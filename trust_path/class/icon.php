<?php

class Dura_Class_Icon
{
    public static function &getIcons()
    {
        static $icons = null;

        if ($icons === null) {
            $icons = array();
            $iconDir = DURA_PATH . '/css';

            if ($dir = opendir($iconDir)) {
                while (($file = readdir($dir)) !== false) {
                    if (preg_match('/^icon_(.+)\.png$/', $file, $match)) {
                        list($dummy, $icon) = $match;
                        $icons[$icon] = $file;
                    }
                }

                closedir($dir);
            }
        }

        return $icons;
    }

    public static function getIconUrl($icon)
    {
        $url = DURA_URL . '/css/icon_' . $icon . '.png';
        return $url;
    }
}
