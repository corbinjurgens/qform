<?php

namespace Corbinjurgens\QForm;

use Illuminate\View\Component;

use Corbinjurgens\QForm\ServiceProvider as S;

class Input extends Component
{
	use Concerns\Template;
	
	public $form = NULL;
	public $type = NULL;
	public $id = NULL;
	public $name = NULL;
	public $value = NULL;
	public $text = NULL;
	public $alt_type = NULL;
	public $guide = NULL;
	public $variables = NULL;
	public $variableAttributes = NULL;// Pass array of attributes specific to the variable keys such as ['disabled' => [1 => true]] and itll display as is, anything false or null will be ignored
	public $surround = NULL;
	public $hideValue = NULL;
	public $required = False;
	public $labels = [];
	public $json = False;
	public $error = null;
	public $errors = null;
	public $inline = false;
	
	public $basename = NULL;
    public function __construct($form = null, $type = 'text', $id = null, $name = null, $value = null, $text = NULL, $guide = NULL, $variables = NULL, $variableAttributes = NULL, $surround = True, $hideValue = False, $required = null, $labels = [], $json = false, $template = null, $error = null, $errors = null, $inline = false)
    {
		$form_null = false;
		if ($form === null){
			$form_null = true;
			$form = QForm::new();
		}
        $this->form = $form;
        $this->type = $type;
		$this->json = $json;
		if ($this->type == 'json' || $this->json == True ){
			$this->type = 'json';
			$this->alt_type = $type != 'json' ? $type : null;
			
			$this->form->arrayType(true);
		}
		$this->error = $error ?? $form->getError();
		$this->errors = $errors ?? $form->getErrorArray();
        $this->text = $text ?? $form->getText();
        $this->guide = $guide ?? $form->getGuide();
        $this->id = $id ?? ($form_null ? $name : $form->getId());
        $this->name = $name ?? $form->getName();
		$this->basename = !is_null($name) ? $this->stripName($name) : $form->getBasename();// in the case that $name is prefixed, such as 'user[email]' then this will get just the email part

		$this->hideValue = $hideValue;
		if ($this->hideValue == false){
			$this->value = $value ?? $form->getValue();
		}
		$this->template($template ?? $form->getTemplateSuffix());
		
		$this->required = ($required ?? $form->getRequired());
		
        $this->variables = $variables;
        $this->variableAttributes = $variableAttributes;
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
	
	/**
	 * If the name is passed directly, we will need to check it is not an array,
	 * and if it is, get the basename
	 *
	 * TODO use "/.*(?<!\\)\[(.+?)(?<!\\)\]/" to get last 
	 *
	 * @param string|null $name
	 */
	public static function stripName($name){
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
        return view( S::$name . '::' . 'components.forms.input' . $this->getTemplate());
    }
}
