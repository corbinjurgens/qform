<?php

namespace Corbinjurgens\QForm;
/**
 * Get the various form things easily. For the whole form, first set data and text if any with ::init. 
 * Then for each value set key with ::input. Then you can each each of the input things.
 * CJ 2020-11-26
 */
class QForm {
	/**
	 * Change the global templates used for the current script execution. Normally it will look for input.blade.php for example, but if you set a template like "alt" it will look for input_alt.blade.php
	 */
	protected $global_template = null;
	function set_global_template($suffix){
		$this->global_template = $suffix;
		return $this;
	}
	/**
	 * Set the templates used for the current class instance. Normally it will look for input.blade.php for example, but if you set a template like "alt" it will look for input_alt.blade.php
	 */
	protected $current_template = null;
	
	function get_template(){
		$suffix = $this->current_template;
		if ($suffix){
			return '_' . $suffix;
		}
		$suffix = $this->global_template;
		if ($suffix){
			return '_' . $suffix;
		}
		return '';
	}
	/**
	 * Model data
	 */
	protected $curr_data = NULL;
	protected $curr_data_exists = NULL;
	/**
	 * Points to the input key, or in other words database column
	 */
	protected $key = NULL;
	/**
	 * Points to translation file, defaulting to columns.php, it will look for key in that file.
	 * You can instead give other file or more deeper like columns.table1 and it will look for key
	 */
	protected $text = NULL;
	/**
	 * Errors from request
	 */
	protected $errors = NULL;
	function __construct($curr_data = NULL, $errors = NULL, $text = NULL, $template_suffix = null){
		$this->curr_data = $curr_data;
		$this->curr_data_exists = ($curr_data == True);
		$this->errors = $errors;
		$this->text = $text;
		$this->current_template = $template_suffix;
	}
	static function init($curr_data = NULL, $errors = NULL, $text = 'columns'){
		return new self($curr_data, $errors, $text);
	}
	/**
	 * Resolve to a particular input of the instance, and also clear any input specific settings such as input.
	 */
	function input($key){
		$this->default = NULL;
		$this->alt_text = NULL;
		$this->key = $key;
		return $this;
	}
	/**
	 * Set a default for the currently set input
	 */
	protected $value = NULL;
	function default($value){
		$this->default = $value;
		return $this;
	}
	// Alt text used to point to a different text directly as opposed to the parent array (and for guide automatically append _guide to the same key)
	protected $alt_text = NULL;
	function alt_text($key){
		$this->alt_text = $key;
		return $this;
	}
	
	private function _error(){
		if ($this->errors){
			return $this->errors->first($this->key);
		}
	}
	
	
	function id(){
		return $this->key;
		
	}
	function text(){
		if ($this->alt_text !== NULL){
			return __($this->alt_text);
		}else{
			return __($this->text . '.' . $this->key);
		}
		
		
	}
	function guide(){
		if ($this->alt_text !== NULL){
			return __n($this->alt_text . '_guide', $replace = [], $locale = null, $fallback = true, $return_key = false);
		}else{
			return __n($this->text . '.' . $this->key . '_guide', $replace = [], $locale = null, $fallback = true, $return_key = false);
			//if ( array_key_exists($this->key . '_guide', trans($this->text)) ){
			//	return __($this->text . '.' . $this->key . '_guide');
			//}
		}
		
	}
	
	function error(){
		$error = $this->_error();
		if ($error){
			return $error;
		}
		return NULL;
	}
	
	function value($default = NULL){
		$value = old($this->key, $this->curr_data ? $this->curr_data->{$this->key} : $this->default);
		return $value ?? $default ?? '';
	}
	


}