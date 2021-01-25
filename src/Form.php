<?php

namespace Corbinjurgens\QForm;

use Illuminate\View\Component;

class Form extends Component
{
	public $id = null;
	public $class = null;
	public $_method = null;
	public $action = null;
	
	public $attr = null;
	
	public $enctype = null;
	
	public $csrf = null;
	
	public $method = null;
	
	const POST_METHODS = [
		'PUT', 'PATCH', 'DELETE'
	];
    /**
     * Create a new component instance.
     *
     * @return void
     */
	
    public function __construct
	(
		$id = null, $class = null, $method = 'POST', $action = null,
		$attr = null,
		$enctype = 'multipart/form-data',
		$csrf = true
	)
    {
		$this->id = $id;
		$this->class = $class;
		$this->_method = $method;
		$this->action = $action;
		
		$this->attr = $attr;
		
		
		/**
		 * Auto set method to POST if its not supported by HTML
		 */
		if ( in_array($method, self::POST_METHODS) ){
			$method = 'POST';
		}
		$this->method = $method;
		
		if ($method == 'POST'){
			$this->enctype = $enctype;
		}
		
		
		
		$this->csrf = $csrf;
		
		
		
		
		
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('qform::components.forms.form');
    }
}
