<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.0 or Upper version
 *
 * @package    Dura
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2010 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU GPL v3
 *
 */

if ( file_exists('setting.php') )
{
	require 'setting.php';
}

/*
if ( !in_array(getenv("REMOTE_ADDR"), array('::1')) )
{
	require 'mente.html';
	echo getenv("REMOTE_ADDR");
	die;
}
*/

require 'dura.php';

Dura::setup();
Dura::execute();



?>
