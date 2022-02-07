<?php

namespace Corbinjurgens\QForm\Components;

use Illuminate\View\Component;
use Illuminate\Support\Arr;

use Corbinjurgens\QForm\ServiceProvider as S;

use Corbinjurgens\QForm\Concerns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function __construct($data = null, $value = null, $type = 'text', $name = null, $column = null, $default = null, $title = null, $subtitle = null, $hide = NULL, $hide_error = false, $variables = null, $variableAttributes = null, $template = null, $int = false)
    {

		$names = static::stripName($name);
		$this->basename = end($names);
		reset($names);
		$this->column = $column ?? $this->basename;
		$this->id_fallback = join('-', $names);

		$dot = static::buildDot($names);
		$this->value = $hide ? $default : ($value ?? static::resolveValue($data, $this->column, $dot, $default, $hide));
		$this->int = $int;
		if ($this->int && is_numeric($this->value)){
			$this->value = (int) $this->value;
		}
		
		$this->data = $data;
		$this->type = $type;
		$this->name = static::buildName($names);
		$this->default = $default;
		$this->title = $title ?? Str::title($this->basename);
		$this->subtitle = $subtitle;
		$this->hide = $hide;
		$this->hide_error = $hide_error;
		$this->variables = $variables;
		$this->variableAttributes = $variableAttributes;

		$this->template($template);

		$this->error = \QForm::getError($dot);
		$this->errors = \QForm::getErrorArray($dot);

		


		
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

	public static function resolveValue($data, $column, $name = null, $default = null) {
		return old($name, static::pullValue($data, $column, $default));
	}

	public static function pullValue($data, $column, $default){
		if ($data instanceof Model && !$data->exists){
			// Model is not yet created so should pass default
			return $default;
		}
		return Arr::get($data, $column, $default);
	}
	
	/**
	 * If the name is passed directly, we will need to check it is not an array,
	 * and if it is, get the basename
	 *
	 * @param string|null $name
	 */
	public static function stripName($name){
		if (strpos($name, '[') === false){
			return [$name];
		}
		$mode = "CHR";
		$started = false;
		$characters = mb_str_split($name);
		$parts = [];
		$curr = "";
		foreach($characters as $character){
			if ($mode == "ESC"){
				$curr .= $character;
				$mode = "CHR";
				continue;
			}
			if ($character === "\\"){
				$mode = "ESC";
				continue;
			}

			if ($character = "["){
				if (!$started){
					$parts[] = $curr;
					$started = true;
				}
				$curr = "";
			}else if ($character = "]"){
				$parts[] = $curr;
			}else{
				$curr .= $character;
			}
		}
		return $parts;
	}

	public static function buildName($names){
		$names = array_map(function($name){
			return str_replace(['[', ']'], ['\\[', '\\]'], $name);
		},$names);

		$build = array_shift($names);
		if ($names){
			$build .= '[' . join('][', $names) . ']';
		}
		return $build;
	}

	public static function buildDot($names){
		return join('.', $names);
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
