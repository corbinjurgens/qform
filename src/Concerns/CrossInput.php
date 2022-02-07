<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

trait CrossInput
{
    
	/*
	 | --------------------------
	 | The following are 
	 | settings that persist between input shifts.
	 | Even when you call function input(), they will
	 | remain set until you either change them again,
	 | or call hardReset()
	 */
	 
	 
	/** null|array If prefix is being used, they are stored as array of arrays */
	protected $prefix = null;
	
	/**
	 * Set prefix so that id() and name() will return 
	 * Does not get reset across multiple inputs, so 
	 * you must set back to null
	 * You should use a single string like 'options' to become options[key]
	 * OR array like ['options', 0] to become options[0][key]
	 *
	 * @param null|string|array $prefix
	 */
	public function prefix($prefix = null){
		
		if ($prefix !== null){
			if (!is_array($this->prefix)){
				$this->prefix = [];
			}
			
			if (!is_array($prefix)){
				$prefix = [$prefix];
			}
			$this->prefix[] = $prefix;
		}else{
			$this->prefix = $prefix;
		}
		
		
		return $this;
	}
	
	public function prefixIn($prefix){
		if (!is_array($this->prefix)){
			$this->prefix = [];
		}
		
		
		if (!is_array($prefix)){
			$prefix = [$prefix];
		}
		
		$this->prefix[] = $prefix;
		
		
	}
	
	public function prefixOut(){
		
		if (!is_array($this->prefix)){
			return;
		}
		
		array_pop($this->prefix);
		if (empty($this->prefix)){
			$this->prefix = null;
		}
		
	}
	
	/**
	 * Change the current data of the form for multiple inputs.
	 * Such as pointing to a data column array, then accessing it
	 * Should be an array or Model object
	 */
	protected $shift_data = null;
	
	public function dataShift($data = null){
		$this->shift_data = $data;
	}
	
	public function dataReset(){
		$this->shift_data = null;
	}
	
	/**
	 * Change text base for longer than just one input, array or string
	 */
	protected $shift_text = null;
	
	public function textShift($data){
		$this->shift_text = $data;
	}
	public function textReset(){
		$this->shift_text = null;
	}
	
	/**
	 * The above functions such as text_shift dont get reset between inputs.
	 * You can reset all here
	 */
	public function hardReset(){
		$this->prefix = null;
		$this->shift_data = null;
		$this->shift_text = null;
	}
	
}
