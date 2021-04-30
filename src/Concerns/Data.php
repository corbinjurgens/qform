<?php

namespace Corbinjurgens\QForm\Concerns;

use Illuminate\Database\Eloquent\Model;

use Corbinjurgens\QForm\ServiceProvider as S;

trait Data
{    
	
	/** @var null|array|\Illuminate\Database\Eloquent\Model  */
	protected $curr_data = null;
	
	/** @var bool Whether the given model/data exists */
	protected $curr_data_exists = false;
	
	
	/**
	 * Set the data (array or Model) and by setting the input by function input($key) it will automatically
	 * look in the data to fill the input value
	 *
	 * @param null|array|\Illuminate\Database\Eloquent\Model $data
	 */
	public function data($data = null){
		$this->curr_data = $data;
		$this->curr_data_exists = 
			(
				( is_array($data) && !empty($data) ) || ( $data instanceof Model && $data->exists )
			);
		return $this;
	}
	
	/**
	 * Alias for data
	 *
	 * @param null|array|\Illuminate\Database\Eloquent\Model $data
	 */
	public function model($data = null){
		return $this->data($data);
	}
	
}
