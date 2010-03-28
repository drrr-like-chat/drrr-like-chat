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

class Dura_Class_Chat
{
	protected $id    = null;
	protected $logs  = array();
	protected $users = array();

	protected function __construct($id)
	{
		$this->id = $id;

		$data = Dura_Class_Log::read('chat'.$this->id);

		if ( !is_object($data) )
		{
			return;
		}

		$this->logs  = $data->getLogs();
		$this->users = $data->getUsers();
	}

	public static function &getInstance($id)
	{
		static $chats = array();

		if ( !isset($chats[$id]) )
		{
			$chats[$id] = new self($id);
		}

		return $chats[$id];
	}

	public function getLogs()
	{
		return $this->logs;
	}

	public function getUsers()
	{
		return $this->users;
	}

	public function addUser($user)
	{
		$id = $user->getId();
		$this->users[$id] = $user;
		$this->_saveLog();
	}

	public function removeUser($user)
	{
		$id = $user->getId();

		if ( isset($this->users[$id]) )
		{
			unset($this->users[$id]);
			$this->_saveLog();
		}
	}

	public function addNpcLog($message)
	{
		$this->addLog(0, 'NPC', null, $message);
	}

	public function addUserLog($user, $message)
	{
		$this->addLog($user->getId(), $user->getName(), $user->getIcon(), $message);
	}

	public function addLog($id, $name, $icon, $message)
	{
		$log = array(
			'id'      => $id,
			'name'    => $name,
			'icon'    => $icon,
			'message' => $message,
			'time'    => time(),
			'hash'    => md5(mt_rand()),
		);

		$this->logs[] = $log;

		$this->logs = array_slice($this->logs, -1 * DURA_LOG_MAX);

		$this->_saveLog();
	}

	public function removeExpires()
	{
		$expires = array();

		foreach ( $this->users as $user )
		{
			if ( $user->getExpire() < time() )
			{
				$id = $user->getId();
		
				if ( isset($this->users[$id]) )
				{
					unset($this->users[$id]);
				}

				$expires[] = $user->getName();
			}
		}

		if ( count($expires) > 0 )
		{
			$this->_saveLog();
		}

		return $expires;
	}

	protected function _saveLog()
	{
		Dura_Class_Log::write('chat'.$this->id, $this);
	}
}

?>
