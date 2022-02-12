<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

use Illuminate\Container\Container;

use Corbinjurgens\QForm\Concerns\ComponentTagCompiler;
use Illuminate\Support\Str;


trait Tools
{

	/**
	 * Single dimensionsional string array to valid array
	 * Allows you to retain the true values as raw php
	 */
	public static function arrayStringtoArray(string $string, $escape = "\\"){
		$exceptSplitQuotes = function($string, $compare, $escape){
			$res = [];
			$curr = '';
			$until = null;
			$escaping = false;
			$extra = mb_strlen($compare) - 1;
			

			$strings = mb_str_split($string);
			$skip = 0;
			foreach ($strings as $index => $char){
				if ($skip){
					$skip--;
					continue;
				}

				if ($escaping){
					$curr .= $char;
					$escaping = false;
					continue;
				}

				if ($until){
					if ($char === $escape){
						$escaping = true;
					}else if ($char === $until){
						$until = null;
					}
					$curr .= $char;
					continue;
				}

				if ($char === "'" || $char === "\""){
					$until = $char;
					$curr .= $char;
					continue;
				}

				$to_compare = $char;
				for ($i = 0; $i<$extra; $i++){
					$to_compare .= $strings[$index + $i + 1] ?? '';
				}
				if ($to_compare === $compare){
					$res[] = $curr;
					$curr = null;
					$skip = $extra;
					continue;
				}
			
				$curr .= $char;
			}

			if (isset($curr)){
				$res[] = $curr;
			}

			return $res;
		};

		if (stripos($string, 'array(') === 0){
			$string = mb_substr($string, 6, -1);
		}else if (strpos($string, '[') === 0){
			$string = mb_substr($string, 1, -1);
		}

		$pairs = $exceptSplitQuotes($string, ',', $escape);
		$results = [];
		foreach($pairs as $pair){
			[$key, $value] = $exceptSplitQuotes($pair, '=>', $escape);
			$key = trim($key);
			$value = trim($value);
			if (Str::startsWith($key, ['"', '\''])){
				$key = substr($key, 1, -1);
			}
			$results[$key] = $value;
		}
		return $results;

	}
}
