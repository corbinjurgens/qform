<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

trait PerInputGet
{
    
	
	/**
	 * Check if prefix is currently being used
	 *
	 * 
	 */
	protected function _usingPrefix(){
		return (is_array($this->prefix));
	}
	
	/**
	 * Get prefixes array
	 *
	 * 
	 */
	protected function _getPrefix(){
		
		if (!is_array($this->prefix)){
			return null;
		}
		
		$prefixes = [];
		
		foreach($this->prefix as $level){
			foreach($level as $prefix){
				$prefixes[] = $prefix;
			}
		}
		
		return $prefixes;
	}
	
	/**
	 * Get various datas
	 *
	 *
	 */
	public function getRequired(){
		return ($this->required === true);
	}
	
	/**
	 * Get just the name even if it has prefix
	 */
	public function getBasename(){
		return $this->name ?? $this->key;
	}
	
	/**
	 * Get form input id attribute
	 *
	 * 
	 */
	public function getId(){
		$id = $this->getBasename();
		
		if ($this->_usingPrefix()){
			$prefix = $this->_getPrefix();
			$prefix[] = $id;
			return join('-', $prefix);
		}
		
		return $id;
	}
	
	/**
	 * Build prefix based on current prefixes in use.
	 * You may manually call this function in a template 
	 *
	 * @param bool If true, current input key will be included in built prefix string
	 */
	public function buildPrefix($append_key = false){
		if (!$this->_usingPrefix()){
			return $append_key ? $this->getBasename() : '';
		}
		
		$name_path = '';
		$prefix = $this->_getPrefix();
		if ($append_key === true) $prefix[] = $this->getBasename();
		$first = true;
		foreach($prefix as $part){
			if ($first === true){
				$first = false;
				$name_path .= $part;
			}else{
				$name_path .= '['.$part.']';
			}
		}
		return $name_path;
	}
	
	
	/**
	 * TODO later I may need to add a way to separate input name from database key
	 *
	 */
	public function getName(){
		if ($this->_usingPrefix()){
			return $this->build_prefix(true);
		}
		return $this->getBasename();
		
	}
	
	/**
	 * Like name but used for getting old() and errors so it points with prefix included like 'prefix.name'
	 * 
	 */
	public function getNameDot(){
		$name = $this->getBasename();
		if ($this->_usingPrefix()){
			$prefix = $this->_getPrefix();
			$prefix[] = $name;
			return join('.', $prefix);
		}
		return $name;
	}
	
	protected function _getTextTable($key, $guide = false){
		$function = $guide ? '__' : [self::class, 'transNull'];
		return (
			$this->table !== null
			? call_user_func( $function, 'columns.' . $this->table . '.'. $key . ($guide ? '_guide' : '') )
			: ($guide ? '' : $key)
		);
	}
	
	/**
	 * Get current input text
	 * Optionally you can pass a key if you don't want to use key given via funcion input()
	 *
	 * @param null|string $force_key
	 */
	public function getText($force_key = null){
		if ($this->force_text_mode === true){
			return $this->alt_text;
		}
		
		$key = $force_key ?? $this->alt_text ?? $this->key;
		$text = $this->alt_text_base ?? $this->shift_text ?? $this->text;
		
		if (is_array($text)){
			return isset($text[$key]) ? $text[$key] : $this->_getTextTable($key);
		}
		
		if (is_string($text)){
			return self::transNull($text . '.' . $key) ?? $this->_getTextTable($key);
		}
		
		return $this->_getTextTable($key);
	}
	
	/**
	 * Get current input guide text
	 * Optionally you can pass a key if you don't want to use key given via funcion input()
	 *
	 * @param null|string $force_key
	 */
	public function getGuide($force_key = null){
		if ($this->force_text_mode === true){
			return $this->guide_text;
		}
		
		$key = $force_key ?? $this->alt_text ?? $this->key;
		$pointer = $key;
		$target = $this->guides;
		
		// If no guides, it means look to normal text
		// instead with _guide suffix on key
		if ($target === null){
			$target = $this->shift_text ?? $this->alt_text_base ?? $this->text;
			$pointer .= '_guide';
		}
		
		if (is_array($target)){
			return isset($target[$pointer]) ? $target[$pointer] : $this->_getTextTable($key, true);
		}
		
		if (is_string($target)){
			$path = $target . '.' . $pointer;
			return self::transNull($path) ?? $this->_getTextTable($key, true);
		}
		
		return $this->_getTextTable($key, true);
		
		
	}
	
	/**
	 * Get single error for current input
	 *
	 */
	public function getError(){
		if ($this->errors){
			return $this->errors->first($this->getNameDot());
		}
	}
	
	/**
	 * Get error array for current input
	 *
	 */
	public function getErrorArray(){
		if ($this->array_type === true){
			$errors = $this->errors->get($this->getNameDot() . '.*');
		}
		return $errors ?? [];
		
	}
	
	/**
	 * Get current inputs value, optionally pass a $default
	 *
	 * @param null|mixed $default Only used if the data/Model does not exist
	 *
	 */
	public function getValue($default = null){
		if (is_null($this->key)){
			return null;
		}
		
		$target_data = $this->shift_data ?? $this->curr_data;
		$fallback = 
		(	
			$this->value_forced === True ? $this->value : 
			(
				$this->curr_data_exists && isset($target_data[$this->key]) ? @$target_data[$this->key] :
				($default ?? $this->default)
			)
		);
		$value = old($this->getNameDot(), $fallback);
		return $value;
	}
	
	
}
