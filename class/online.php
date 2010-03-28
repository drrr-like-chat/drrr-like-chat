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

class Dura_Class_Online
{
	protected static $onlines = null;

	public static function add($user)
	{
		self::load();
		$id = $user->getId();
		self::$onlines[$id] = $user;
		self::update();
	}

	public static function remove($user)
	{
		self::load();

		$id = $user->getId();

		if ( isset(self::$onlines[$id]) )
		{
			unset(self::$onlines[$id]);
		}

		self::update();
	}

	public static function getOnlines()
	{
		self::load();
		return self::$onlines;
	}

	public static function update()
	{
		Dura_Class_Log::write('online', self::$onlines);
	}

	public static function load()
	{
		self::$onlines = array();

		if ( self::$onlines === null )
		{
			$onlines = Dura_Class_Log::read('online');

			if ( !is_array($onlines) ) return;

			self::$onlines = $onlines;
		}
	}
}

?>
