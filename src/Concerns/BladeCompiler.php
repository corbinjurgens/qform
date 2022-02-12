<?php

namespace Corbinjurgens\QForm\Concerns;

use Illuminate\View\Compilers\BladeCompiler as Base;


/**
 * Add string compile for custom string
 */
class BladeCompiler extends Base
{
	public function parseTokens($value)
    {
		$result = '';
        foreach (token_get_all($value) as $token) {
            $result .= is_array($token) ? $this->parseToken($token) : $token;
        }
		return $result;
    }
}
