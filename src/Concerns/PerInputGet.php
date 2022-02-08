<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;
use Illuminate\Support\Arr;

trait PerInputGet
{
	public static function resolveValue($data, $column, $name = null, $default = null) {
		return old($name, static::pullValue($data, $column, $default));
	}

	public static function pullValue($data, $column, $default){
		if ($data instanceof Model && !$data->exists){
			// Model is not yet created so should pass default
			return $default;
		}
		return Arr::get($data, $column, $default);
	}
	
	/**
	 * If the name is passed directly, we will need to check it is not an array,
	 * and if it is, get the basename
	 *
	 * @param string|null $name
	 */
	public static function stripName($name){
		$prefix = static::preparePrefix();
		if (is_array($name)){
			return array_merge($prefix, $name);
		}
		if (strpos($name, '[') === false){
			$prefix[] = $name;
			return $prefix;
		}
		$mode = "CHR";
		$started = false;
		$characters = mb_str_split($name);
		$parts = [];
		$curr = "";
		foreach($characters as $character){
			if ($mode == "ESC"){
				$curr .= $character;
				$mode = "CHR";
				continue;
			}
			if ($character === "\\"){
				$mode = "ESC";
				continue;
			}

			if ($character = "["){
				if (!$started){
					$parts[] = $curr;
					$started = true;
				}
				$curr = "";
			}else if ($character = "]"){
				$parts[] = $curr;
			}else{
				$curr .= $character;
			}
		}
		return array_merge($prefix, $parts);
	}

	public static function buildName($names){
		$names = array_map(function($name){
			return str_replace(['[', ']'], ['\\[', '\\]'], $name);
		},$names);

		$build = array_shift($names);
		if ($names){
			$build .= '[' . join('][', $names) . ']';
		}
		return $build;
	}

	public static function buildDot($names){
		return join('.', $names);
	}

	
	/**
	 * Check if prefix is currently being used
	 *
	 * 
	 */
	public static function usingPrefix(){
		return (!empty(static::$prefix));
	}
	
	/**
	 * Get prefixes array
	 *
	 * 
	 */
	public static function preparePrefix(){
		$prefixes = [];
		
		foreach(static::getPrefix() as $level){
			foreach($level as $prefix){
				$prefixes[] = $prefix;
			}
		}
		
		return $prefixes;
	}
	
}
