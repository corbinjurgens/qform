<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

use Corbinjurgens\QForm\QForm;

trait Template
{
    
	/** @var null|string Template suffix string  */
	protected $template = null;
	
	/**
	 * Set the template used. Give only the suffix, eg 'bootstrap3' 
	 * and it will search for the template file with "_bootstrap3" appended to file name
	 *
	 * @param string|null $template
	 */
	public function template($template = null){
		$this->template = $template ?? self::$global_template ?? QForm::$global_template;
	}
	
	/**
	 * Get template with underscore ready to append to filename
	 *
	 *
	 */
	public function getTemplate(){
		if ($this->template){
			return '_' . $this->template;
		}
		return '';
	}
	
	/**
	 * Get template with underscore ready to append to filename
	 *
	 *
	 */
	public function getTemplateSuffix(){
		return $this->template;
	}
	
	/** @var null|string Globally set template suffix  */
	public static $global_template = null;
	
	/**
	 * Set template used globally. If a class instance does not have a template set,
	 * it will look to this value
	 */
	public static function setGlobalTemplate(string $suffix = null){
		self::$global_template = $suffix;
	}
}
