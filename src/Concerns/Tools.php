<?php

namespace Corbinjurgens\QForm\Concerns;

use Corbinjurgens\QForm\ServiceProvider as S;

trait Tools
{
	
	/**
	 * Gets the column text and returns array
	 * Looks in columns.php $table_path, and optionally form specific forms.php $form_path 
	 * This is not necessary when using Qform::init $text parameter as a string pointer such as 'forms.signup' combined with function guide('users'), but may be helpful for 
	 * retrieving validations column names
	 * However the result can be used for $text and $guides
	 *
	 * @param string $table_path Esentialy the table name
	 * @param string|null $form_path If you want to have more specific column text for a form, enter the form name of where to look here
	 * @param string $general_path Where $table_path will look in
	 * @param string $specific_path Where $form_path will look in
	 * 
	 */
	public static function langCombine($table_path, $form_path = null, $general_path = 'columns.', $specific_path = 'forms.'){
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
	 * Used for guide mostly, to only show translations that exists
	 * Doesnt work for json type transations, only php array key type
	 *
	 * @param string $path
	 */
	public static function transNull($path){
		$trans = __($path);
		return ($trans != $path) ? $trans : null;
	}
}
