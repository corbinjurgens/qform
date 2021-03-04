<?php

namespace Corbinjurgens\QForm;

use Illuminate\View\Component;

use Corbinjurgens\QForm\ServiceProvider as S;

class Input extends Component
{
	use Shared;
    /**
     * Requires the QForm extension (by CJ)
     *
     * @return void
     */
	public $form = NULL;
	public $type = NULL;
	public $id = NULL;
	public $name = NULL;
	public $value = NULL;
	public $text = NULL;
	public $alt_type = NULL;
	public $guide = NULL;
	public $variables = NULL;
	public $surround = NULL;
	public $hideValue = NULL;
	public $required = False;
	public $labels = [];
	public $json = False;
	public $error = null;
	public $errors = null;
	public $inline = false;
	
	public $basename = NULL;
    public function __construct($form = null, $type = 'text', $id = null, $name = null, $value = null, $text = NULL, $guide = NULL, $variables = NULL, $surround = True, $hideValue = False, $required = null, $labels = [], $json = false, $template = null, $error = null, $errors = null, $inline = false)
    {
		$form_null = false;
		if ($form === null){
			$form_null = true;
			$form = QForm::init();
		}
        $this->form = $form;
        $this->type = $type;
		$this->json = $json;
		if ($this->type == 'json' || $this->json == True ){
			$this->type = 'json';
			$this->alt_type = $type != 'json' ? $type : null;
			
			$this->form->array_type(true);
		}
		$this->error = $error ?? $form->error();
		$this->errors = $errors ?? $form->errors_array();
        $this->text = $text ?? $form->text();
        $this->guide = $guide ?? $form->guide();
        $this->id = $id ?? ($form_null ? $name : $form->id());
        $this->name = $name ?? $form->name();
		$this->basename = !is_null($name) ? $this->strip_name($name) : $form->basename();// in the case that $name is prefixed, such as 'user[email]' then this will get just the email part

		$this->hideValue = $hideValue;
		if ($this->hideValue == false){
			$this->value = $value ?? $form->value();
		}
		$this->set_template($template ?? $form->template);
		
		$this->required = ($required !== NULL ? $required : $form->is_required());
		
        $this->variables = $variables;
        $this->surround = $surround;
		
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
        $this->inline = $inline;
		
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
	public static function strip_name($name){
		$start = strrpos($name, '[',);
		if ($start === false){
			return $name;
		}
		$stripped = substr($name, $start + 1, -1);
		if ($stripped) return $stripped;
		return $name;
	}
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {	
        return view( S::$name . '::' . 'components.forms.input' . $this->template_suffix);
    }
}
