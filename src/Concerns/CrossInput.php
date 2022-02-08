<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

trait CrossInput
{

	protected static $prefix = [];
	
	/**
	 * Set prefix so that name of inputs will return
	 * You should use a single string like 'options' to become options[key]
	 * OR array like ['options', 0] to become options[0][key]
	 *
	 * @param null|string|array $prefix
	 */
	public static function prefix($prefix = null){
		
		static::prefixReset();

		if (isset($prefix)){
			if (!is_array($prefix)){
				$prefix = [$prefix];
			}
			static::$prefix[] = $prefix;
		}
	}

	public static function prefixReset(){
		static::$prefix = [];
	}
	
	/**
	 * Prefix deeper
	 */
	public static function prefixIn($prefix){
		if (!is_array($prefix)){
			$prefix = [$prefix];
		}
		
		static::$prefix[] = $prefix;
	}
	
	/**
	 * Prefix back
	 */
	public static function prefixOut(){
		
		if (!empty(static::$prefix)){
			array_pop(static::$prefix);
		}
	}

	public static function getPrefix(){
		return static::$prefix;
	}
	
	/**
	 * Use a model of array across multiple inputs
	 */
	protected static $data = null;
	
	public static function data($data = null){
		static::$data = $data;
	}
	
	public static function dataReset(){
		static::$data = null;
	}

	public static function getData($fallback = null){
		return static::$data ?? $fallback;
	}
	
	/**
	 * Clear all the above functions
	 */
	public static function hardReset(){
		static::prefixReset();
		static::dataReset();
	}
	
}
