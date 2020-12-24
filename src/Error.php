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
    public $form;
    public $message;
    public $block;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($form, $message, $type = 'danger', $block = False)
    {
        $this->form = $form;
        $this->message = $message;
        $this->type = $type;
		$this->block = $block;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view(S::$name . '::components.forms.input-error' . $this->form->get_template());
    }
}
