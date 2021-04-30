<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

use Illuminate\Support\ViewErrorBag;

trait Build
{
    
	/** @var bool  */
	protected $array_type = false;
	
	/**
	 * When the error should return array as https://laravel.com/docs/8.x/validation#retrieving-all-error-messages-for-a-field
	 * For example when using a multi dimentional form
	 * 
	 * @param bool $array_type
	 */
	public function arrayType($array_type = false){
		$this->array_type = $array_type;
		return $this;
	}
	
	/** @var ViewErrorBag  */
	protected $errors = NULL;
	
	/**
	 * Load errors from session, if empty set as an empty Error Bag
	 * In the same way template $errors variable works
	 *
	 *
	 */
	protected function loadErrors(){
		$this->errors = session()->get('errors', app(ViewErrorBag::class));
	}
	
	/**
	 * Points to the input key, or in other words database column.
	 * By default it is also used for ->id() and ->name()
	 *
	 * @var string
	 */
	protected $key = NULL;
	
	
	/**
	 * String points to translation file, defaulting to forms.php, it will look for input $key in that file.
	 * You can instead give other file or more deeper like forms.signup and it will look for key inside there
	 * If you use an array, text and guide will look for the key from there instead
	 * When paried with function guide() you can separate out the text and guide 
	 *
	 * @var null|string|array
	 */
	protected $text = NULL;
	
	/**
	 * Set text used for the form.
	 * Can be an array of translations, or a string pointing to where in your key translations the text is found
	 *
	 * @param null|string|array $text
	 */
	public function text($text = null){
		$this->text = $text;
		return $this;
	}
	
	/**
	 * By default, function guide() will look to trans or array of $text with '_guide' appended to $key
	 * Instead you can add either guides array, or string to translaton path to search directly within that
	 * without '_guide' suffix
	 *
	 * @param null|string|array $guides
	 */
	protected $guides = null;
	
	public function guides($guides = []){
		$this->guides = $guides;
		return $this;
	}
	
	/** @var string|null  */
	protected $table = null;
	
	/**
	 * Set forms table
	 * If key is not found in $text and $table has been set, 
	 * It will look to the columns.php file for the table, and key eg 
	 * __('columns.user.password')
	 * This is a great way to specify only the columns that are different via $text such as 'forms.signup',
	 * and for the rest use shared values found in columns table such as table('users')
	 *
	 * @param null|string $table
	 */
	public function table(string $table = null){
		$this->table = $table;
		return $this;
	}
	
}
