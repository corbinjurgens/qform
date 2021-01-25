<?php

namespace Corbinjurgens\QForm;

use Illuminate\View\Component;

use Corbinjurgens\QForm\ServiceProvider as S;

class Error extends Component
{    /**
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
    public $template;// suffix
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message, $type = 'danger', $block = False, $template = '')
    {
        $this->message = $message;
        $this->type = $type;
		$this->block = (bool) $block;
		$this->template = $template;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view(S::$name . '::components.forms.input-error' . $this->template);
    }
}
