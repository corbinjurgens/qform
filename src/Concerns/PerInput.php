<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

trait PerInput
{
    
	/*
	 | --------------------------
	 | The following are 
	 | settings that are to be set between each input
	 | When you call function input(), they will
	 | all be reset
	 */
	 
	use PerInputGet;
	use PerInputSet;
	
	/**
	 * Clear values between inputs
	 *
	 * @return void
	 */
	public function reset(){
		$this->default = NULL;
		
		$this->force_text_mode = false;
		$this->alt_text = NULL;
		$this->guide_text = NULL;
		
		$this->alt_text_base = NULL;
		
		$this->required = NULL;
		
		$this->name = null;
		$this->value_forced = False;
		$this->value = NULL;
		
		$this->array_type = False;
	 }
	
}
