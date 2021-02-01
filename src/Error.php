<?php

namespace Corbinjurgens\QForm;

use Illuminate\View\Component;

use Corbinjurgens\QForm\ServiceProvider as S;

class Error extends Component
{    
	use Shared;
	/**
     * The alert type.
     *
     * @var string
     */
    public $type;

    /**
     * The alert message.
     *
     * @var string
     */
    public $message;
    public $block;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message, $type = 'danger', $block = False, $template = null)
    {
        $this->message = $message;
        $this->type = $type;
		$this->block = (bool) $block;
		
		$this->set_template($template);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view(S::$name . '::components.forms.input-error' . $this->template_suffix);
    }
}
