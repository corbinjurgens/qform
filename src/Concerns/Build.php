<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

use Illuminate\Support\ViewErrorBag;

trait Build
{
	
	/** @var ViewErrorBag  */
	protected static $errors = NULL;
	
	/**
	 * Load errors from session, if empty set as an empty Error Bag
	 * In the same way template $errors variable works
	 *
	 *
	 */
	protected function loadErrors(){
		if (isset(static::$errors)){
			return;
		}
		static::$errors = session()->get('errors', app(ViewErrorBag::class));
	}

	
	/**
	 * Get single error for current input
	 *
	 */
	public function getError($key){
		$this->loadErrors();
		if (static::$errors){
			return static::$errors->first($key);
		}
		return NULL;
	}
	
	/**
	 * Get error array for current input
	 *
	 */
	public function getErrorArray($key){
		$this->loadErrors();
		$errors = static::$errors->get($key . '.*');
		return $errors ?? [];
		
	}
	
}
