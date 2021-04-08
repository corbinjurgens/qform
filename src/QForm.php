<?php

namespace Corbinjurgens\QForm;
use Illuminate\Support\ViewErrorBag;
/**
 * Get the various form things easily. For the whole form, first set data and text if any with ::init. 
 * Then for each value set key with ::input. Then you can each each of the input things.
 * CJ 2020-11-26
 */
class QForm {
	
	use Shared;
	/**
	 * Tool Functions
	 * --------------
	 */
	 
	/**
	 * Change the global templates used for the current script execution. Normally it will look for input.blade.php for example, but if you set a template like "alt" it will look for input_alt.blade.php
	 * You could set this in a Service provider, or in a route controller
	 */
	public static $global_template = null;
	static function set_global_template($suffix){
		self::$global_template = $suffix;
	}
	
	/**
	 * Gets the column text and returns array
	 * Looks in columns.php $table_path, and optionally form specific forms.php $form_path 
	 * This is not necessary when using Qform::init $text parameter as a string pointer such as 'forms.signup' combined with function guide('users'), but may be helpful for 
	 * retrieving validations column names
	 * However the result can be used for $text and $guides
	 */
	static function lang_combine($table_path, $form_path = null, $general_path = 'columns.', $specific_path = 'forms.'){
		$table_array = __($general_path.$table_path);
		if (!is_array($table_array)){
			$table_array = [];
		}
		if ($form_path === null) return \Arr::dot($table_array);
		$form_array = __($specific_path.$form_path);
		if (!is_array($form_array)){
			$form_array = [];
		}
		return \Arr::dot(array_replace($table_array, $form_array));
		
	}
	/**
	 * Form functions
	 * -----------------
	 */
	/**
	 * Set the templates used for the current class instance. Normally it will look for input.blade.php for example, but if you set a template like "alt" it will look for input_alt.blade.php
	 */
	// now in Shared
	/**
	 * Model data
	 * NULL is ok
	 * $curr_data_exists checks if Model is true and $model->exists so it must be a Eloquent instance
	 */
	protected $curr_data = NULL;
	protected $curr_data_exists = false;
	/**
	 * Points to the input key, or in other words database column.
	 * By default it is also used for ->id() and ->name()
	 */
	protected $key = NULL;
	/**
	 * String points to translation file, defaulting to forms.php, it will look for input $key in that file.
	 * You can instead give other file or more deeper like forms.signup and it will look for key inside there
	 * If you use an array, text and guide will look for the key from there instead
	 * When paried with function guide() you can separate out the text and guide 
	 * @param NULL|string|array $text
	 */
	protected $text = NULL;
	/**
	 * Errors from request
	 */
	protected $errors = NULL;
	function __construct($curr_data = NULL, $text = NULL, $template_suffix = NULL){
		$this->curr_data = $curr_data;
		$this->curr_data_exists = ($curr_data == True && $curr_data->exists);
		
		$this->text = $text;
		$this->set_template($template_suffix);
		
		$this->errors = session()->get('errors', app(ViewErrorBag::class));
	}
	static function init($curr_data = NULL, $text = 'forms', $template_suffix = NULL){
		return new self($curr_data, $text, $template_suffix);
	}
	/**
	 * By default, function guide() will look to trans or array of $text with '_guide' appended to $key
	 * Instead you can add either guides array, or string to translaton path to search directly within that
	 * without '_guide' suffix
	 * @param null|string|array $guides
	 */
	protected $guides = null;
	function guides($guides = []){
		$this->guides = $guides;
		return $this;
	}
	/**
	 * If key is not found in $text and $table has been set, 
	 * It will look to the columns.php file for the table, and key eg 
	 * __('columns.user.password')
	 * This is a great way to specify only the columns that are different via $text such as 'forms.signup',
	 * and for the rest use shared values found in columns table such as table('users')
	 */
	protected $table = null;
	function table($table){
		$this->table = $table;
		if ($this->text === null){
			// If setting table and text is empty, point to default trans
			$this->text = 'forms.' . $this->table;
		}
		return $this;
	}
	/**
	 * To be used between inputs, changes are retained unless manualy setting back to null
	 * or using hard_reset();
	 */
	 
	/**
	 * Set prefix so that id() and name() will return 
	 * Does not get reset across multiple inputs, so 
	 * you must set back to null
	 * You should use a single string like 'options' to become options[key]
	 * OR array like ['options', 0] to become options[0][key]
	 */
	protected $prefix = null;
	function prefix($prefix = null){
		if ($prefix !== null){
			if (!is_array($prefix)){
				$prefix = [$prefix];
			}
		}
		$this->prefix = $prefix;
		return $this;
	}
	/**
	 * Change the current data of the form for multiple inputs.
	 * Such as pointing to a data column array, then accessing it
	 * Should be an array or object
	 */
	protected $shift_data = null;
	function value_shift($data){
		$this->shift_data = $data;
	}
	function value_reset(){
		$this->shift_data = null;
	}
	/**
	 * Change text base for longer than just one input, array or string
	 */
	protected $shift_text = null;
	function text_shift($data){
		$this->shift_text = $data;
	}
	function text_reset(){
		$this->shift_text = null;
	}
	
	/**
	 * The above functions such as text_shift dont get reset between inputs.
	 * You can reset all here
	 */
	function hard_reset(){
		$this->prefix = null;
		$this->shift_data = null;
		$this->shift_text = null;
	}
	
	/**
	 * Form init ends
	 * --------------
	 * The rest are functions per input,
	 * Such as setting current input, and 
	 * getting values for it
	 */
	
	/**
	 * Clear values between inputs
	 */
	 function reset(){
		$this->default = NULL;
		
		$this->force_text_mode = false;
		$this->alt_text = NULL;
		$this->guide_text = NULL;
		$this->alt_text_base = NULL;
		
		$this->required = NULL;
		
		$this->name = null;
		$this->value_forced = False;
		$this->value = NULL;
		
		$this->array_type = False;
	 }
	 
	/**
	 * Resolve to a particular input of the instance, and also clear any input specific settings such as default.
	 */
	function input($key){
		// clear
		$this->reset();
		
		// set key
		$this->key = $key;
		return $this;
	}
	/**
	 * Set required
	 */
	protected $required = NULL;
	function required($required = True){
		$this->required = $required;
		return $this;
	}
	/**
	 * Set name (othrwise key is used)
	 */
	protected $name = null;
	function set_name($name = NULL){
		$this->name = $name;
		return $this;
	}
	/**
	 * Set value
	 */
	protected $value_forced = False;
	protected $value = NULL;
	function set_value($value = NULL){
		$this->value_forced = True;
		$this->value = $value;
		return $this;
	}
	/**
	 * Set a default for the currently set input
	 */
	protected $default = NULL;
	function default($value = NULL){
		$this->default = $value;
		return $this;
	}
	// Set text used to force text as is (rather than looking to trans() or array)
	protected $force_text_mode = false;
	protected $alt_text = NULL;
	protected $alt_text_base = NULL;
	protected $guide_text = NULL;
	function set_text($text, $guide = null){
		$this->force_text_mode = true;
		$this->alt_text = $text;
		$this->guide_text = $guide;
		return $this;
	}
	// Look in same text location as in init $text, but look for different key
	function alt_text($key){
		$this->alt_text = $key;
		return $this;
	}
	// Alt text base used to point to an entirely different base eg not the one given in init $text
	function alt_text_base($key){
		$this->alt_text_base = $key;
		return $this;
	}
	/**
	 * When the error should return array as https://laravel.com/docs/8.x/validation#retrieving-all-error-messages-for-a-field
	 * For example when using a multi dimentional form
	 */
	protected $array_type = false;
	function array_type($array_type = false){
		$this->array_type = $array_type;
		return $this;
	}
	
	
	/**
	 * Get various datas
	 *
	 *
	 */
	function is_required(){
		return ($this->required === true);
	}
	/**
	 * Get ust the name even if it has prefix
	 */
	function basename(){
		return $this->name ?? $this->key;
	}
	function id(){
		$id = $this->basename();
		if (is_array($this->prefix)){
			$prefix = $this->prefix;
			$prefix[] = $id;
			return join('-', $prefix);
		}
		return $id;
		
		
	}
	function build_prefix($append_key = false){
		$name_path = '';
		$prefix = $this->prefix;
		if ($append_key === true) $prefix[] = $this->basename();
		$first = true;
		foreach($prefix as $part){
			if ($first === true){
				$first = false;
				$name_path .= $part;
			}else{
				$name_path .= '['.$part.']';
			}
		}
		return $name_path;
	}
	// TODO later I may need to add a way to separate input name from database key
	function name(){
		if (is_array($this->prefix)){
			return $this->build_prefix(true);
		}
		return $this->basename();
		
	}
	// Like name but used for getting old() so it points with prefix included like 'prefix.name'
	function name_path(){
		$name = $this->basename();
		if (is_array($this->prefix)){
			$prefix = $this->prefix;
			$prefix[] = $name;
			return join('.', $prefix);
		}
		return $name;
	}
	function text($force_key = null){
		if ($this->force_text_mode === true){
			return $this->alt_text;
		}
		$key = $force_key ?? $this->alt_text ?? $this->key;
		$text = $this->alt_text_base ?? $this->shift_text ?? $this->text;
		if (is_array($text)){
			return isset($text[$key]) ? $text[$key] : 
			($this->table !== null ? __('columns.' . $this->table . '.'. $key) : null)
			;
		}
		return $this->trans_null($text . '.' . $key) ?? 
					($this->table !== null ? __('columns.' . $this->table . '.'. $key) : null)
		;
	}
	
	function guide($force_key = null){
		if ($this->force_text_mode === true){
			return $this->guide_text;
		}
		$key = $force_key ?? $this->alt_text ?? $this->key;
		$pointer = $key;
		$target = $this->guides;
		if ($target === null){
			$target = $this->shift_text ?? $this->alt_text_base ?? $this->text;
			$pointer .= '_guide';
		}
		if (is_array($target)){
			return isset($target[$pointer]) ? $target[$pointer] : 
			// not found in array, will look in columns and expect always to have _guide so using $key and adding _guide
			($this->table !== null ? $this->trans_null('columns.' . $this->table . '.'. $key . '_guide') : null);
			;
		}
		$path = $target . '.' . $pointer;
		return $this->trans_null($path) ?? 
					($this->table !== null ? $this->trans_null('columns.' . $this->table . '.'. $key . '_guide') : null)
		;
		
	}
	/**
	 * Used for guide mostly, to only show translations that exists
	 * Doesnt work for json type transations, only php array key type
	 */
	function trans_null($path){
		$trans = __($path);
		return ($trans != $path) ? $trans : null;
	}
	
	function error(){
		if ($this->errors){
			return $this->errors->first($this->name_path());
		}
	}
	function errors_array(){
		if ($this->array_type === true){
			$errors = $this->errors->get($this->name_path() . '.*');
		}
		return $errors ?? [];
		
	}
	
	function value($default = null){
		$target_data = $this->shift_data ?? $this->curr_data;
		$fallback = 
		(	
			$this->value_forced === True ? $this->value : 
			(
				$this->curr_data_exists && isset($target_data[$this->key]) ? @$target_data[$this->key] :
				($default ?? $this->default)
			)
		);
		$value = old($this->name_path(), $fallback);
		return $value;
	}
	


}