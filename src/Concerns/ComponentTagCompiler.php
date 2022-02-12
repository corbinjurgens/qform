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
		$this->setBoundAttributes([]);
		$attributes = $this->customAttributePrepare($attributes);

		$res = $this->componentString($alias, $attributes)."\n@endComponentClass";
		$this->appendEnd($res);
		return $res;
	}

	public function customAttributePrepare(array $attributes){
		return collect($attributes)->mapWithKeys(function ($value, $attribute) {

            if (is_null($value)) {
                $value = 'true';
            }

			$this->setBoundAttributes($attribute, true);
            return [$attribute => $value];
        })->toArray();
	}

	public function appendEnd($value){
		if (version_compare(app()->version(), '8.23.0') >= 0){
			$value .= "##END-COMPONENT-CLASS#";
		}
		return $value;
	}

	public function setBoundAttributes(){
		if (version_compare(app()->version(), '7.1.2') < 0){
			return;
		}
		$args = func_get_args();
		if (count($args) > 1){
			list($attribute, $value) = $args;
			$this->boundAttributes[$attribute] = $value;
		}else{
			$value = $args[0] ?? null;
			$this->boundAttributes = $value;
		}
	}
}
