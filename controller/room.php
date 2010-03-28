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

class Dura_Controller_Room extends Dura_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		$this->_validateUser();

		$this->_default();
	}

	protected function _default()
	{
		$rooms = Dura_Class_Room::getRooms();

		foreach ( $rooms as &$room )
		{
			$chat =& Dura_Class_Chat::getInstance($room['id']);
			$room['total'] = count($chat->getUsers());

			$param = array('id' => $room['id']);
			$room['url'] = Dura::url('chat', null, $param);
		}

		$user =& Dura::user();
		$icon = $user->getIcon();
		$icon = Dura_Class_Icon::getIconUrl($icon);

		$profile = array(
			'icon' => $icon,
			'name' => $user->getName(),
		);

		$this->output['profile'] = $profile;
		$this->output['rooms'] = $rooms;

		$this->_view();
	}
}

?>
