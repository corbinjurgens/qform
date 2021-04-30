<?php

namespace Corbinjurgens\QForm;

use ReflectionClass;

use Corbinjurgens\QForm\Input;

use Str;

class QForm {
	
	use Concerns\Template;
	use Concerns\Data;
	use Concerns\Tools;
	use Concerns\Build;
	use Concerns\CrossInput;
	use Concerns\PerInput;

	
	/**
	 * Construct and new form
	 *
	 * @param null|array|\Illuminate\Database\Eloquent\Model $curr_data The model or array used for the form
	 * @param null|string $template_suffix Suffix for if you want to use a template other than the default one
	 */
	public function __construct($curr_data = NULL, $template_suffix = NULL){
		$this->data($curr_data);
		$this->template($template_suffix);
		
		$this->loadErrors();
		
		
	}
	
	/**
	 * Create and return new form
	 *
	 * @param null|array|\Illuminate\Database\Eloquent\Model $curr_data The model or array used for the form
	 * @param null|string $template_suffix Suffix for if you want to use a template other than the default one
	 */
	static public function new($curr_data = NULL, $template_suffix = NULL){
		return new self($curr_data, $template_suffix);
	}
	
	protected function getComponentArray(){
		return [
			'form' => $this
		];
	}
	
	public function render($extra = []){
		$constructor = (new ReflectionClass(Input::class))->getConstructor();
		
        $parameters = $constructor
                    ? collect($constructor->getParameters())->mapWithKeys(function($item){
						return [$item->getName() => $item->getDefaultValue()];
					})->all()
                    : [];
		
		$attributes = [];
		
		$process_order = [
			$this->getComponentArray(),
			$extra
		];
		
		foreach($process_order as $process){
			foreach($process as $key => $value){
				if ( array_key_exists( Str::camel($key), $parameters ) ){
					$parameters[ Str::camel($key) ] = $value;
				}else{
					$attributes[ Str::kebab($key) ] = $value;
				}
			}
		}
		
		$component = ( new Input( ...array_values($parameters) ) )->withAttributes($attributes)->withName('qform-input');
		return $component->render()->with($component->data());
	}

}