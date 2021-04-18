# Introduction

Created for personal use, and is not well documented or tidy. Feel free to use / extend

QForm for Laravel does a bunch of html form stuff, making use of Laravel components
- Create most types of html inputs with a single component
- Make use of the QForm::init() set of tools either by itself or with a component to easily access a models data, create form name attributes with prefix etc, and deal with error display and old() for you.
- Customize template
- In the provided base Bootstrap 4 template, it makes some attempts to provide labelling, error and other accesibility features.

## Warnings
1. Doesn't play nice when array inputs such as select have muliple options that resolve to false (not strict)

2. QForm::init() expects that you will have your translations arranged in a certain way, using key translations,
  a columns.php translation file arranged with table name as key and array of column names as follows
```php
  'users' => [
    'name' => 'Name',
    'level' => 'User Level',
    'password' => 'Password',
  ],
  ... Other tables
```
and a forms.php (or any name you choose, this is optional) arranged in the same way but instead of table name, it is a form name, with column keys filled only where they differ from the columns.php as follows
```php
  'login' => [
    'password' => 'Enter your Password',
    'password_guide' => 'Your password must include alphabet and numbers', // a column name with '_guide' suffix will automatically appear as input extra text
  ],
  'signup' => [
    'password' => 'Enter a password',
    'pasword_confirmation' => 'Enter your password again',
  ],
  'permissions' => [
    'level' => 'Admin Level',
    'levels' => [
      0 => 'User',
      1 => 'Admin',
      2 => 'Super Admin',
    ]
  ],
  'admin' => [
    'notifications' => 'Receive Notification',
    'notifications_guide' => 'Select this and the user will receive admin notifications',
  ]
  ... Other forms
```

For example you have a signup form and a login form. Preparing a QForm via `$form = QForm::init(NULL, 'forms.login')->table('users')` means it will first look to forms.login for the column text, then columns.users.

	

# Setup
## Manual Installation

Copy the following to main composer.(in the case that the package is added to packages/corbinjurgens/qform)
```
 "autoload": {
	"psr-4": {
		"Corbinjurgens\\QForm\\": "packages/corbinjurgens/qform/src"
	},
},
```
and run 
```
composer dump-autoload
```


Add the following to config/app.php providers
```
Corbinjurgens\QForm\ServiceProvider::class,
```
Add alias to config/app.php alias
```
"QForm" => Corbinjurgens\QForm\Facade::class,
```

# Basic Usage
## Directly

Using components as is

Simple inputs
```html

<x-qform-input type="text" name="name" value="This is text" text="This is the column name" />

// $value = 'This is text';
<x-qform-input type="textarea" name="name1" :value="$value" :text="__('column.text1')" />

// $value = True; In this case the checkbox value will always be 1, and only be checked if value is true
<x-qform-input type="checkbox" name="name2" :value="$value" :text="__('column.text2')" />
```

Array input, ie. anything that has, or can have multiple options
```html
// $value = 1;
// $variables = [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3'];
<x-qform-input type="select" name="name" :value="$value" :text="__('column.text')" :variables="$variables" />

// $value = 1;
// $variables = [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3'];
<x-qform-input type="radio" name="name" :value="$value" :text="__('column.text')" :variables="$variables" />

// $value = [1, 2, 3]; If a checkbox has an array given for :variables, it will behave differently and expect :value to also be array
// $variables = [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3'];
<x-qform-input type="checkbox" name="name" :value="$value" :text="__('column.text')" :variables="$variables" />
```

## Smartly

Using components with the QForm tools is the intended usage of this package

### Basics

In your template or controller, create a form. See the warning section above for an example of the translation files that this would be pointing to
```html
@php
    // Initiate the $form either in your template, or from the controller
    // To edit an item, pass a model as the first parameter, or if your form will never edit, just pass null. Personally I usually share form between create and edit and pass a new model when creating
    // For second parameter point to a translation, pass an array of translations, or leave it empty. Pass table with ->table() to point to a table in tables.php translation file
    $form = QForm::init($user, 'forms.permissions')->table('users');
@endphp

{{-- Shift current column to 'name' and pass it to the form. The form will automatically look to the model for the 'name' value and fill the input, and look to translation files first for forms.permissions.name, then users.name --}}
<x-qform-input type="text" :form="$form->input('name')" />

@php( $form->input('level') )
{{-- In the previous input, I shifted current input as I passed it to :form, but you can also shift separately, then access.
Normally, $form->text() would look for the current inputs text, so first it would look to forms.permissions.level, then users.level. But because I passed a value to it, its looking to the same root, just different key to get :variables (See Introduction section for example of translation files)
--}}
<x-qform-input type="radio" :form="$form" :variables="$form->text('levels')"/>


{{-- 
  Normally this would look to forms.permissions.notifications, then users.notifications, 
  but by passing a translation pointer, or array of columns to text_shift() I am telling it to look elsewhere for the 'notifications' text (and guide text)
  WARNING: text_shift() and value_shift() functions persist between input shifts so you must either pass null again, or call hard_reset() to clear 
--}}
<x-qform-input type="checkbox" :form="$form->input('notifications')->text_shift('forms.admin')"/>
```

### Other functions

Use the prefix function and pass an array (or null to clear), and it will automatically prefix the inputs name attribute
WARNING prefix persists between input shifts, so you must clear it by passing null or calling hard_reset(). 
Be sure in situuations where you shift the input and also get some other value that depends on the input, that you set the input first, or set it separately. However, in this example it wouldn't matter even if the input wasn't shifted yet
```html
@php
  $form = QForm::init($user, 'forms.permissions')->table('users');
@endphp
<x-qform-input type="radio" :form="$form->input('level')->prefix(['admin', 'data'])" :variables="$form->text('levels')"/>
{{-- the inputs name attribute will be name="admin[data][level]"  --}}
```



*The following are functions that are set for each input and are cleared when shifting input:*

Call required($required = true) to make the current input required

Call set_name($name = null) to set a string name if it is different than the column as passed via input()

Call default($value = null) to set a value for when the model is null or doesn't exist

Call set_text($text, $guide = null) and pass the form label text, and optionally form guide text. This will use the passed string as is

Call alt_text($key) to look to the same translation file passed, but just look for a different column

Call alt_text_base($base) and pass an array, or a string pointing to translation path to shift where the text will be found. This is similar to shift_text() but does not persist between inputs

## Freely

You can use QForm tools without using a component. This makes creating your inputs id, name and value easy, saving you a lot of repition

```html
@php( $form = QForm::init($user, 'forms.permissions')->table('users') )

@php( $form->input('name') )
<label for="{{ $form->id() }}">{{ $form->text() }}<label>
<p>{{ $form->guide() }}</p>
<p>{{ $form->error() }}</p>
<input type="text" value="{{ $form->value() }}" name="{{ $form->name() }}" id="{{ $form->id() }}" />

```

# Customize template

Publish views via 
```
php artisan vendor:publish --tag="qform-views"
```

You can edit the files directly, or the component templates "x-qform-input", "x-qform-error" and "x-qform-submit" support taking a suffix.

Make a copy of the template, and add suffix for example `input.blade.php` becomes `input_bootstrap3.blade.php`, then you can set the suffix "bootstrap3" directly on the component like <x-qform-input template="bootstrap3" .../>, per form like `$form = QForm::init(NULL, 'forms.login', 'bootstrap3') or globally via `QForm::$global_template = 'bootstrap3'`

# Other stuff

You can quickly create forms with the <x-qform-form action="..." > <x-qform-form /> component. method will default to POST and also provide @csrf and @method when necessary

A bunch of features aren't documented here

# Notes

This code is untested and not to any standard. It has however been used extensively for my personal projects and most issues have been worked out.

## TODO
- PHPDocs would be nice but not important while I don't expect anyone else to use this code
- QForm functions are a bit all over the place (the function names aren't clear whether it is setting or getting)
- Untested in a fresh enviroment. I may have accidentally used a local function or other one of my private packages without realizing