<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;
use Illuminate\Support\Str;

use Illuminate\View\Compilers\BladeCompiler as Base;
use Corbinjurgens\QForm\Concerns\ComponentTagCompiler;


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
