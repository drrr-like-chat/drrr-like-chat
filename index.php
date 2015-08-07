<?php

if (file_exists('setting.php')) {
    require 'setting.php';
} else {
    require 'setting.dist.php';
}

require 'dura.php';

Dura::setup();
Dura::execute();
