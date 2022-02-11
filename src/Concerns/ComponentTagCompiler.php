<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

use Illuminate\View\Compilers\ComponentTagCompiler as Base;
use Illuminate\Support\Str;


/**
 * Compile template attributes from the custom directives
 */
class ComponentTagCompiler extends Base
{
	public function customCompile($alias, array $attributes){
		$this->boundAttributes = [];

		$attributes = $this->customAttributePrepare($attributes);

		return $this->componentString($alias, $attributes)."\n@endComponentClass##END-COMPONENT-CLASS##";
	}

	public function customAttributePrepare(array $attributes){
		return collect($attributes)->mapWithKeys(function ($value, $attribute) {

            if (is_null($value)) {
                $value = 'true';
            }

            $this->boundAttributes[$attribute] = true;

            return [$attribute => $value];
        })->toArray();
	}
}
