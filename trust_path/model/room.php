<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.0 or Upper version
 *
 * @package    Dura
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2010 Hidehito NOZAWA

 *
 */

class Dura_Model_Room extends Dura_Class_Xml
{
	public function asArray()
	{
		$result = array();

		$result['name']   = (string) $this->name;
		$result['update'] = (int) $this->update;
		$result['limit']  = (int) $this->limit;
		$result['host']   = (string) $this->host;
		$result['language'] = (string) $this->language;

		if ( isset($this->talks) )
		{
			foreach ( $this->talks as $talk )
			{
				$result['talks'][] = (array) $talk;
			}
		}

		foreach ( $this->users as $user )
		{
			$result['users'][] = (array) $user;
		}

		return $result;
	}
}

?>
