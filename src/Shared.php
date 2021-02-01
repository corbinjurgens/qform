<?php

namespace Corbinjurgens\QForm;

use Corbinjurgens\QForm\ServiceProvider as S;

trait Shared
{
    
	public $template = null;
	public $template_suffix = null;
	function set_template($template = null){
		$this->template = $template ?? QForm::$global_template;
		if ($this->template){
			$this->template_suffix = '_' . $this->template;
			return;
		}
		$this->template_suffix = '';
	}
}
