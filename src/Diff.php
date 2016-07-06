<?php

namespace Christophedlr\Diff;

/**
 * Calc differences in two strings or two files
 * @author Christophe Daloz - De Los Rios <christophedlr@gmail.com>
 * @version 1.0
 * @license http://cecill.info/licences/Licence_CeCILL_V2-en.html CeCILL V2
 * @copyright Christophe Daloz - De Los Rios, 2016
 */
class Diff {
	const ADD = 0x01;
	const MODIFIED = 0x02;
	const REMOVE = 0x03;
	
	public function __construct() {}
	
	/**
	 * Search differences in two strings
	 * @param string $new
	 * <div>New string</div>
	 * 
	 * @param string $old
	 * <div>Old string</div>
	 * 
	 * @return array
	 * <div>Array containing list of modifications of old string by new string</div>
	 */
	public function strings($new, $old) {
		$array = array();
		
		$explode_string = explode("\n", $new);
		$explode_string2 = explode("\n", $old);
		
		$explode_string = array_diff($explode_string, array(''));
		$explode_string2 = array_diff($explode_string2, array(''));
		
		foreach ($explode_string as $key => $val) {
			if ( !isset($explode_string2[$key]) ) {
				$array[$key] = array('type' => self::ADD, 'string' => $val);
			}	
			else if ( $val !== $explode_string2[$key] ) {
				$array[$key] = array('type' => self::MODIFIED, 'string' => $val);
			}
		}
		
		if ( count($explode_string) < count($explode_string2) ) {
			for ($i = count($explode_string); $i < count($explode_string2); $i++) {
				$array[$i] = array('type' => self::REMOVE, 'string' => $explode_string2[$i]);
			}
		}
		else {
			for ($i = count($explode_string2); $i < count($explode_string); $i++) {
				$array[$i] = array('type' => self::ADD, 'string' => $explode_string[$i]);
			}
		}
		
		return $array;
	}
	
	/**
	 * Search differences in two files
	 * @param string $new
	 * <div>Name of new file</div>
	 * 
	 * @param string $old
	 * <div>Name of old file</div>
	 * 
	 * @throws \Exception
	 * <div>Raise exception in new or old file not exist</div>
	 * 
	 * @return array
	 * <div>Array containing list of modifications of old file by new file</div>
	 */
	public function files($new, $old) {
		if ( !file_exists($new) ) {
			throw new \Exception('This file <b>'.$new.'</b> not exists');
		}
		
		if ( !file_exists($old) ) {
			throw new \Exception('This file <b>'.$old.'</b> not exists');
		}
		
		$content_new = file_get_contents($new);
		$content_old = file_get_contents($old);
		
		return $this->strings($content_new, $content_old);
	}
}
