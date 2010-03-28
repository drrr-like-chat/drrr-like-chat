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

class Dura_Controller_Chat extends Dura_Abstract_Controller
{
	protected $id   = null;
	protected $chat = null;
	protected $isAjax = null;

	public function __construct()
	{
		parent::__construct();

		$this->isAjax = (bool) Dura::get('ajax');

		if ( $this->isAjax and !Dura::user()->isUser() )
		{
			$this->_redirectToRoom();
		}
		else
		{
			$this->_validateUser();
		}

		$this->id = (int) Dura::get('id');


		if ( !$this->id or $this->id < 1 or DURA_ROOMS < $this->id )
		{
			$this->_redirectToRoom();
		}

		$this->chat =& Dura_Class_Chat::getInstance($this->id);

		if ( !$this->_isLogin() and count($this->chat->getUsers()) >= DURA_USER_MAX )
		{
			$this->_redirectToRoom();
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
			$this->_deleteUpdateSession();
			$this->_redirectToRoom();
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

		$this->chat->addUser(Dura::user());
		$this->chat->addNpcLog(Dura::user()->getName()."さんが入室しました");

		$this->_createUpdateSession();

		if ( $this->isAjax )
		{
			return;
		}

		Dura::redirect('chat', null, array('id' => $this->id));
	}

	protected function _message()
	{
		$message = Dura::post('message');
		$message = preg_replace('/^[ 　]*(.*?)[ 　]*$/u', '$1', $message);
		$message = trim($message);

		if ( !$message ) return;

		if ( mb_strlen($message) > 140 )
		{
			$message = mb_substr($message, 0, 140).'...';
		}

		$this->chat->addUserLog(Dura::user(), $message);

		if ( $this->isAjax )
		{
			return;
		}

		Dura::redirect('chat', null, array('id' => $this->id));
	}

	protected function _logout()
	{
		$this->chat->removeUser(Dura::user());
		$this->chat->addNpcLog(Dura::user()->getName()."さんが退室しました");

		$this->_deleteUpdateSession();

		$this->_redirectToRoom();
	}

	protected function _default()
	{
		$logs = $this->chat->getLogs();

		$this->_sessionTimeout();
		$this->_rollCall();


		if ( $this->isAjax )
		{
			if ( $hash = Dura::get('hash') )
			{
				$hashFound = false;

				foreach ( $logs as $k => $log )
				{
					if ( $hashFound ) continue;

					if ( $log['hash'] == $hash )
					{
						$hashFound = true;
					}

					unset($logs[$k]);
				}
			}

			$this->_escapeHtml($logs);

			$logs = array_reverse(array_reverse($logs)); // what!?

			$result = array(
				'messages' => $logs,
			);

			$json = json_encode($result);
			echo $json;
			return;
		}

		$users = $this->chat->getUsers();
		$userNames = array();

		foreach ( $users as $user )
		{
			$userNames[] = $user->getName();
		}

		sort($userNames);
		$logs = array_reverse($logs);

		$this->output['logs']  = $logs;
		$this->output['users'] = $userNames;

		$this->output['action'] = Dura::url('chat', null, array('id' => $this->id));

		$this->_view();
	}

	protected function _isLogin()
	{
		$users = $this->chat->getUsers();
		$id = Dura::user()->getId();

		return ( isset($users[$id]) );
	}

	protected function _getUpdateSession()
	{
		return $_SESSION['chat'.$this->id]['next_update'];
	}

	protected function _createUpdateSession()
	{
		$_SESSION['chat'.$this->id]['next_update'] = time() + DURA_TIMEOUT;
	}

	protected function _deleteUpdateSession()
	{
		unset($_SESSION['chat'.$this->id]);
	}

	protected function _sessionTimeout()
	{
		if ( Dura::user()->getExpire() < time() )
		{
			$this->chat->removeUser(Dura::user());
			$this->chat->addNpcLog(Dura::user()->getName()."さんの接続が切れました");

			$this->_deleteUpdateSession();

			$this->_redirectToRoom();
		}
	}

	protected function _rollCall()
	{
		if ( $this->_getUpdateSession() < time() )
		{
			$this->_createUpdateSession();
			$this->chat->removeUser(Dura::user());
			$this->chat->addUser(Dura::user());
			$expires = $this->chat->removeExpires();

			foreach ( $expires as $expire )
			{
				$this->chat->addNpcLog($expire."さんの接続が切れました");
			}
		}
	}

	protected function _redirectToRoom()
	{
		if ( $this->isAjax )
		{
			echo json_encode(false);
			die;
		}
		else
		{
			Dura::redirect('room');
		}
	}
}

?>
