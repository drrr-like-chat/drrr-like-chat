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

class Dura_Class_Log
{
	public static function write($file, $data)
	{
		$path = self::getFilePath($file);
		$data = serialize($data);

		return file_put_contents($path, $data, LOCK_EX);
	}

	public static function read($file)
	{
		$path = self::getFilePath($file);

		if ( !file_exists($path) )
		{
			touch($path);
		}

		$data = file_get_contents($path);

		return unserialize($data);
	}

	public static function getFilePath($file)
	{
		return DURA_LOG_PATH.'/'.$file;
	}
}

?>
