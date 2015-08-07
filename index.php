<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.0 or Upper version
 *
 * @package    Dura
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2010 Hidehito NOZAWA
 
 *
 */

if ( file_exists('setting.php') )
{
	require 'setting.php';
}
else
{
	require 'setting.dist.php';
}

require 'dura.php';

Dura::setup();
Dura::execute();

?>
