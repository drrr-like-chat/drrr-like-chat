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

class Dura_Controller_Lounge extends Dura_Abstract_Controller
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
		$this->_redirectToRoom();

		$this->_rooms();

		$this->_profile();

		$this->output['create_room_url'] = Dura::url('create_room');

		$this->_view();
	}

	protected function _redirectToRoom()
	{
		if ( Dura_Class_RoomSession::isCreated() )
		{
			Dura::redirect('room');
		}
	}

	protected function _rooms()
	{
		$roomHandler = new Dura_Model_RoomHandler;
		$roomModels = $roomHandler->loadAll();

		$rooms = array();

		$roomExpire = time() - DURA_CHAT_ROOM_EXPIRE;

		foreach ( $roomModels as $id => $roomModel )
		{
			$room = $roomModel->asArray();

			if ( $room['update'] < $roomExpire )
			{
				$roomHandler->delete($id);
				continue;
			}

			$room['id']  = $id;
			$room['total'] = count($room['users']);
			$room['url'] = Dura::url('room');
			$rooms[] = $room;
		}

		unset($roomHandler, $roomModels, $roomModel, $room);

		$this->output['rooms'] = $rooms;
	}

	protected function _profile()
	{
		$user =& Dura::user();
		$icon = $user->getIcon();
		$icon = Dura_Class_Icon::getIconUrl($icon);

		$profile = array(
			'icon' => $icon,
			'name' => $user->getName(),
		);

		$this->output['profile'] = $profile;
	}
}

?>
