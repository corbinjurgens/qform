<?php

namespace Corbinjurgens\QForm\Components;

use Illuminate\View\Component;

use Corbinjurgens\QForm\Concerns;

class Form extends Component
{

	public $_method = null;
	
	public $method = null;
	
	public $attr = null;
	
	public $enctype = null;
	
	public $csrf = null;
	
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
		$method = 'POST',
		$attr = null,
		$enctype = 'multipart/form-data',
		$csrf = true
	)
    {

		// Form method, accepts put, patch, or delete too, and will automatically set form to post
		$this->_method = strtoupper($method);// true method
		$this->method = $this->_method;// form method
		if ( in_array($this->method, self::POST_METHODS) ){
			$this->method = 'POST';
		}
		
		$this->attr = $attr;
		
		if ($this->method == 'POST'){
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
