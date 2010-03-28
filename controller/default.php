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

class Dura_Controller_Default extends Dura_Abstract_Controller
{
	protected $error = null;
	protected $icons = array();

	public function __construct()
	{
		parent::__construct();
		$this->icons = Dura_Class_Icon::getIcons();
	}

	public function main()
	{
		if ( Dura::user()->isUser() )
		{
			Dura::redirect('room');
		}

		if ( Dura::post('login') )
		{
			try
			{
				$this->_login();
			}
			catch ( Exception $e )
			{
				$this->error = $e->getMessage();
			}
		}

		$this->_default();
	}

	protected function _login()
	{
		$name = Dura::post('name');
		$icon = Dura::post('icon');
		$name = trim($name);
		$icon = trim($icon);

		if ( $name === '' )
		{
			throw new Exception("名前を決めてください");
		}

		if ( mb_strlen($name) > 10 )
		{
			throw new Exception("名前は10文字以内にしてください。");
		}

		$token = Dura::post('token');

		if ( !Dura_Class_Ticket::check($token) )
		{
			throw new Exception("外部からログインを試みたなどの原因でエラーが発生しました。");
		}

		if ( !isset($this->icons[$icon]) )
		{
			$icons = array_keys($this->icons);
			$icon = reset($icons);
		}

		$user =& Dura_Class_User::getInstance();
		$user->login($name, $icon);

		Dura_Class_Online::add($user);

		Dura_Class_Ticket::destory();

		Dura::redirect('room');
	}

	protected function _default()
	{
		$this->output['icons'] = $this->icons;
		$this->output['error'] = $this->error;
		$this->output['token'] = Dura_Class_Ticket::issue();
		$this->_view();
	}
}

?>
