<?php

namespace Corbinjurgens\QForm;

use Illuminate\View\Component;

use Corbinjurgens\QForm\ServiceProvider as S;

class Input extends Component
{
    /**
     * Requires the QForm extension (by CJ)
     *
     * @return void
     */
	public $form = NULL;
	public $type = NULL;
	public $text = NULL;
	public $alt_type = NULL;
	public $guide = NULL;
	public $variables = NULL;
	public $surround = NULL;
	public $hideValue = NULL;
	public $required = False;
	public $labels = [];
	public $json = False;
    public function __construct($form, $type = 'text', $text = NULL, $guide = NULL, $variables = NULL, $surround = True, $hideValue = False, $required = null, $labels = [], $json = false)
    {
		if ($form === null){
			$form = QForm::init();
		}
        $this->form = $form;
        $this->type = $type;
		$this->json = $json;
		if ($this->type == 'json' || $this->json === True ){
			$this->type = 'json';
			$this->alt_type = $type != 'json' ? $type : null;
			
			$this->form->array_type(true);
		}
        $this->text = $text ?? $form->text();
        $this->guide = $guide ?? $form->guide();
        $this->variables = $variables;
        $this->surround = $surround;
		$this->hideValue = $hideValue;
		$this->required = ($required !== NULL ? $required : $form->is_required());
		
		$process_label = [];
		foreach($labels as $label){
			if (is_array($label)){
				$process_label[] = $label;
			}else{
				$process_label[] = [
					'label' => $label,
					'class' => null,
				];
			}
		}
		$this->labels = $process_label;
		
		/**
		 * Guide
		 * <x-qform-input type="password" :hide-value="true" :form="$form->input('password')" />
		 * type: input type,
		 * form: send form data from database
		 * text: manually enter input text
		 * guide: manually enter guide text
		 * variables: send option or radio group variables as key value array
		 * hide-value: for when the form would access the form by defaultl but you dont want it to. Currently only affective for textarea, and normal input types
		 */
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view(($this->form->get_template() ? '' : S::$name . '::' ) . 'components.forms.input' . $this->form->get_template());
    }
}
