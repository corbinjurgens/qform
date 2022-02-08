<?php

namespace Corbinjurgens\QForm\Components;

use Illuminate\View\Component;
use Illuminate\Support\Arr;

use Corbinjurgens\QForm\ServiceProvider as S;

use Corbinjurgens\QForm\Concerns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Corbinjurgens\QForm\QForm;


class Input extends Component
{
	use Concerns\Template;

	public $data = null;
	public $value = null;
	public $basename = null;
	public $type = null;
	public $name = null;
	public $column = null;// $name is used if column not provied. For when the data key differs from name
	public $id_fallback = null;
	public $default = null;
	public $title = null;
	public $subtitle = null;
	public $hide = null;// hide value, eg in the case of editing a password which would be a hash anyway
	public $hide_error = null;// hide error which is automatically fetched
	public $variables = NULL;
	public $variableAttributes = NULL;// Pass array of attributes specific to the variable keys such as ['disabled' => [1 => true]] and itll display as is, anything false or null will be ignored

	public $error = NULL;
	public $errors = NULL;

	public $int = null;
	public $group = null;

    public function __construct($data = null, $value = null, $type = 'text', $name = null, $column = null, $default = null, $title = null, $subtitle = null, $hide = false, $hide_error = false, $variables = null, $variableAttributes = null, $template = null, $int = false, $group = false)
    {

		$this->data = $data ?? QForm::getData();
		$names = QForm::stripName($name);
		$this->basename = end($names);
		reset($names);
		$this->column = $column ?? $this->basename;
		$this->id_fallback = join('-', $names);

		$dot = QForm::buildDot($names);
		$this->value = $value ?? QForm::resolveValue($this->data, $this->column, $dot, $default);
		$this->int = (bool) $int;
		if ($this->int && is_numeric($this->value)){
			$this->value = (int) $this->value;
		}
		
		$this->type = $type;
		$this->name = QForm::buildName($names);
		$this->default = $default;
		$this->title = $title ?? Str::title($this->basename);
		$this->subtitle = $subtitle;
		$this->hide = (bool) $hide;
		$this->hide_error = (bool) $hide_error;
		$this->variables = $variables;
		$this->variableAttributes = $variableAttributes;

		$this->template($template);

		$this->error = QForm::getError($dot);
		$this->errors = QForm::getErrorArray($dot);
		$this->group = (bool) $group;

		


		
		/**
		 * Guide TO UPDATE
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
        return view( S::$name . '::' . 'components.forms.input' . $this->getTemplate());
    }
}
