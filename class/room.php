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

class Dura_Class_Room
{
	public static function getRooms()
	{
		static $rooms = null;

		if ( $rooms === null )
		{
			$rooms = array();

			for ( $i = 1; $i <= DURA_ROOMS; $i++ )
			{
				$room = array(
					'id'    => $i,
					'name'  => 'チャットルーム'.$i,
					'limit' => DURA_USER_MAX,
				);

				$rooms[$i] = $room;
			}
		}

		return $rooms;
	}
}

?>
