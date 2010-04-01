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
	protected $id   = null;
	protected $chat = null;
	protected $isAjax = null;
	protected $roomHandler = null;
	protected $roomModel   = null;

	public function __construct()
	{
		parent::__construct();

		$this->_validateUser();

		if ( Dura_Class_RoomSession::isCreated() )
		{
			$this->id = Dura_Class_RoomSession::get('id');
		}
		else
		{
			$this->id = Dura::post('id');
		}

		if ( !$this->id )
		{
			Dura::redirect('lounge');
		}

		$this->roomHandler = new Dura_Model_RoomHandler;
		$this->roomModel   = $this->roomHandler->load($this->id);

		if ( !$this->roomModel )
		{
			Dura_Class_RoomSession::delete();
			Dura::trans(t("Room not found.", 'lounge'));
		}
	}

	public function main()
	{
		if ( Dura::post('login') )
		{
			$this->_login();
		}

		if ( !$this->_isLogin() )
		{
			Dura_Class_RoomSession::delete();
			Dura::redirect('lounge');
		}

		if ( Dura::post('logout') )
		{
			$this->_logout();
		}
		elseif ( Dura::post('message') )
		{
			$this->_message();
		}

		$this->_default();
	}

	protected function _login()
	{
		if ( $this->_isLogin() )
		{
			return;
		}

		if ( count($this->roomModel->users) >= (int) $this->roomModel->limit )
		{
			Dura::trans(t("Room is full.", 'lounge'));
		}

		$userName = Dura::user()->getName();
		$userId   = Dura::user()->getId();

		$users = $this->roomModel->addChild('users');
		$users->addChild('name', $userName);
		$users->addChild('id', $userId);

		$talk = $this->roomModel->addChild('talks');
		$talk->addChild('id', md5(microtime().mt_rand()));
		$talk->addChild('uid', 0);
		$talk->addChild('name', 'NPC');
		$talk->addChild('message', t("{1} logged in.", $userName));
		$talk->addChild('icon', '');
		$talk->addChild('time', time());

		$this->roomHandler->save($this->id, $this->roomModel);

		Dura_Class_RoomSession::create($this->id);

		Dura::redirect('room');
	}

	protected function _logout()
	{
		$userName = Dura::user()->getName();
		$userId   = Dura::user()->getId();

		$userOffset = 0;

		foreach ( $this->roomModel->users as $user )
		{
			if ( $userId == (string) $user->id )
			{
				break;
			}

			$userOffset++;
		}

		unset($this->roomModel->users[$userOffset]);

		if ( count($this->roomModel->users) )
		{
			$talk = $this->roomModel->addChild('talks');
			$talk->addChild('id', md5(microtime().mt_rand()));
			$talk->addChild('uid', 0);
			$talk->addChild('name', 'NPC');
			$talk->addChild('message', t("{1} logged out.", $userName));
			$talk->addChild('icon', '');
			$talk->addChild('time', time());
	
			$this->roomHandler->save($this->id, $this->roomModel);
		}
		else
		{
			$this->roomHandler->delete($this->id);
		}

		Dura_Class_RoomSession::delete();

		Dura::redirect('lounge');
	}

	protected function _message()
	{
		$message = Dura::post('message');
		$message = preg_replace('/^[ 　]*(.*?)[ 　]*$/u', '$1', $message);
		$message = trim($message);

		if ( !$message ) return;

		if ( mb_strlen($message) > DURA_MESSAGE_MAX_LENGTH )
		{
			$message = mb_substr($message, 0, DURA_MESSAGE_MAX_LENGTH).'...';
		}

		$talk = $this->roomModel->addChild('talks');
		$talk->addChild('id', md5(microtime().mt_rand()));
		$talk->addChild('uid', Dura::user()->getId());
		$talk->addChild('name', Dura::user()->getName());
		$talk->addChild('message', $message);
		$talk->addChild('icon', Dura::user()->getIcon());
		$talk->addChild('time', time());


		while ( count($this->roomModel->talks) > DURA_LOG_LIMIT )
		{
			unset($this->roomModel->talks[0]);
		}

		$this->roomHandler->save($this->id, $this->roomModel);

		Dura::redirect('room');
	}

	protected function _default()
	{
		$room = $this->roomModel->asArray();

		$room['talks'] = array_reverse($room['talks']);

		$this->output['room'] = $room;

		$this->output['user'] = array(
			'id'   => Dura::user()->getId(),
			'name' => Dura::user()->getName(),
			'icon' => Dura::user()->getIcon(),
		);

		$this->_view();
	}

	protected function _isLogin()
	{
		$users = $this->roomModel->users;
		$id = Dura::user()->getId();

		foreach ( $users as $user )
		{
			if ( $id == (string) $user->id )
			{
				return true;
			}
		}

		return false;
	}
}

?>
