<?php

namespace Corbinjurgens\QForm;

use Illuminate\View\Component;

use Corbinjurgens\QForm\ServiceProvider as S;

class Submit extends Component
{
	use Concerns\Template;
    /**
     * Requires the QForm extension (by CJ)
     *
     * @return void
     */
	public $form = NULL;
	public $name = NULL;
	public $text = NULL;
	public $id = NULL;
	public $class = NULL;
    public function __construct($form = null, $name = null, $text = 'Submit', $class = null, $template = null)
    {
        $this->form = $form;
		
        $this->name = $name;
        $this->text = $text;
        $this->class = $class;
		$this->id = 'submit' . ($this->name ? '-' . $this->name : '');
		
		$this->template($template ?? ($form ? $form->template : null));
		
		/**
		 * Guide
		 * <x-qform-submit name="downlad" :text="__(common.submit)" />
		 */
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view(S::$name . '::components.forms.submit' . $this->getTemplate());
    }
}
