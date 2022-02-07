<?php

namespace Corbinjurgens\QForm\Components;

use Illuminate\View\Component;

use Corbinjurgens\QForm\ServiceProvider as S;

use Corbinjurgens\QForm\Concerns;

class Error extends Component
{    
	use Concerns\Template;
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

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message, $type = 'danger', $template = null)
    {
        $this->message = $message;
        $this->type = $type;
		
		$this->template($template);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view(S::$name . '::components.forms.input-error' . $this->getTemplate());
    }
}
