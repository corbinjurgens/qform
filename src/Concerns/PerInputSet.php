<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

trait PerInputSet
{
    
	/**
	 * Resolve to a particular input of the data/model instance, and also clear any input specific settings such as default.
	 * The inputs name will also be used as the given $key, unless you also set the name via function name() after setting input
	 *
	 * @param string $key
	 */
	public function input($key){
		// clear
		$this->reset();
		
		// set key
		$this->key = $key;
		return $this;
	}
	
	/** @var null|bool */
	protected $required = NULL;
	
	/**
	 * Set required
	 *
	 * @param bool|null $required
	 */
	public function required(bool $required = null){
		$this->required = $required;
		return $this;
	}
	
	/** @var string|null */
	protected $name = null;
	
	/**
	 * Set name for the input,
	 * otherwise $key given via fucntion input() will be used 
	 *
	 * @param string|null $name
	 */
	public function name($name = null){
		$this->name = $name;
		return $this;
	}
	
	/** @var bool */
	protected $value_forced = false;
	
	/** @var mixed */
	protected $value = NULL;
	
	/**
	 * By default, value is retrieved from the given data/Model if any based on input $key,
	 * but you can force the current input to use a different value
	 *
	 * @param mixed $value
	 */
	public function value($value = null){
		$this->value_forced = True;
		$this->value = $value;
		return $this;
	}
	
	/** @var null|mixed */
	protected $default = NULL;
	
	/**
	 * Set a default value for the currently set input
	 * It will only be used if the data/Model does not exist or is null/empty array
	 *
	 * @param mixed $value
	 */
	public function default($value = NULL){
		$this->default = $value;
		return $this;
	}
	
	/** @var bool Force text as is rather than look to trans() or array */
	protected $force_text_mode = false;
	
	/** @var null|string */
	protected $alt_text = NULL;
	
	/** @var null|string */
	protected $alt_text_base = NULL;
	
	/** @var null|string */
	protected $guide_text = NULL;
	
	/**
	 * By default, text will be retrieved from given text(), guides() and table() trans or array
	 * You can set text as is here for the current input.
	 * If you use this and do not give a $guide, guide will be empty
	 *
	 * @param string $text
	 * @param string|null $guide
	 */
	public function setText(string $text, string $guide = null){
		$this->force_text_mode = true;
		$this->alt_text = $text;
		$this->guide_text = $guide;
		return $this;
	}
	
	/**
	 * Look in same text location as given by function text() and table(), but look for different key
	 *
	 * @param null|string|array $key
	 */
	public function altText($key){
		$this->alt_text = $key;
		return $this;
	}
	
	/**
	 * Use same key given function input(), but look in a different place
	 * You can give array, or string pointing to translations
	 *
	 * @param null|string|array $key
	 */
	public function altTextBase($base){
		$this->alt_text_base = $base;
		return $this;
	}
	
}
