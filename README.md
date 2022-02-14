# Introduction

Confirmed working on Laravel 7.0.0, 8.0.0 and 8.73.2 projects

Created for personal use, feel free to use / extend

QForm for Laravel does a bunch of html form stuff, making use of Laravel components
- Create most types of html inputs with a single component
- Automatically get old() values and errors
- Make use of the additional functions to automatically prefix your input form name attributes
- Customizable templates
- In the provided base Bootstrap 4 template, it makes some attempts to provide labelling, error and other accesibility features.

```html
<x-qform-input type="text" name="name" title="This is the name input" />
<x-qform-input type="email" name="email" title="This is the email input" />
<x-qform-input type="select" name="level" title="This is the level input" :variables="[0 => 'User', '1' => 'Admin']" int />
```

- The input will automatically look for errors and old values based on value given by name
- The input will look to array or model given by @QFormData() or directly from the "data" attribute to get input value


## Warnings
For select and other variable input types, strict comparison is used. If your input value is an integer, and is declared as an integer in the variables array, you will need to set the "int" attribute so that it will be cast as an integer before comparison

# Setup
## Composer

composer require corbinjurgens/qform

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

Using components as is, and benefit purely from the template taking care of the html and css

Simple inputs
```html

<x-qform-input type="text" name="name" value="This is text" title="This is the column name" />

// $value = 'This is text';
<x-qform-input type="textarea" name="name1" :value="$value" :title="__('column.text1')" />

// $value = True; In this case the checkbox value will always be 1, and only be checked if value is true
<x-qform-input type="checkbox" name="name2" :value="$value" :title="__('column.text2')" />
```

Array input, ie. anything that has multiple options

You should be sure to set the int attribute if your variable keys are integer, so that the value can be compared correctly

```html
// $value = 1;
// $variables = [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3'];
<x-qform-input type="select" name="name" :value="$value" :title="__('column.text')" :variables="$variables" int />

// $value = 'option1';
// $variables = ['option1' => 'Option 1', 'option2' => 'Option 2', 'option3' => 'Option 3'];
<x-qform-input type="radio" name="name" :value="$value" :title="__('column.text')" :variables="$variables" />

// $value = [1, 2, 3]; If a checkbox has an array given for :variables, it will behave differently and expect :value to also be array
// $variables = [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3'];
<x-qform-input type="checkbox" name="name" :value="$value" :title="__('column.text')" :variables="$variables" int />
```

## Smartly

Using components with the QForm tools is the intended usage of this package

In your template set the target model or array. All inputs after this will use this data to retrieve values

```html
@QFormData(['test' => '1', 'test2' => 2])

{{-- The component parses the name property, and gets the 'test' column from the data --}}
<x-qform-input type="text" name="prefix[test]" />

{{-- If the required data column differs from the name, you can specifify it with 'column' --}}
<x-qform-input type="text" name="prefix[test_other]" column="test2"/>

```

You can also set data directly for each input

```html
<x-qform-input :data="['test' => '1', 'test2' => 2]" type="text" name="prefix[test_other]" column="test2"/>
```

## Bonus

You can create an input using a blade function

```html
@QFormInput(['type' => 'text', 'name' => 'country', 'data' => $user])
```

Is the alternative method to 

```html
<x-qform-input type="text" name="country" :data="$user"/>
```

## Other functions

Use the prefix function and pass an array (or null to clear), and it will automatically prefix the inputs name attribute

```html
@QFormData($user)

@QFormPrefix(['admin', 'data'])

<x-qform-input type="radio" name="level" :variables="$variables"/>
{{-- the inputs name attribute will be name="admin[data][level]"  --}}
```

You can also use prefixIn() and prefixOut() to add a set of prefixes, and then after back out again.
QForm::prefixReset() or @QFormPrefix with no parameter can be used to clear the prefix setting completely.

```html
@QFormPrefixIn(['admin', 'data'])

<x-qform-input type="radio" name="level" :variables="$variables"/>
{{-- name will be name="admin[data][name]" --}}

<x-qform-input type="text" name="address" />
{{-- name will be name="admin[data][address]" as prefix is retained between inputs--}}

@QFormPrefixIn(['preferences'])

<x-qform-input type="text" name="email" />
{{-- name will be name="admin[data][preferences][email]" --}}

@QFormPrefixOut()

<x-qform-input type="text" name="dob" />
{{-- name will be name="admin[data][dob]" so we have gone back to the previous prefix config --}}
```

# Customize template

Publish views via 
```
php artisan vendor:publish --tag="qform-views"
```

You can edit the files directly, or the component templates "x-qform-input", "x-qform-error" and "x-qform-submit" support taking a suffix.

Make a copy of the template, and add suffix for example `input.blade.php` becomes `input_bootstrap3.blade.php`, then you can set the suffix "bootstrap3" directly on the component like <x-qform-input template="bootstrap3" .../>, or globally via `QForm::setGlobalTemplate('bootstrap3')` or @QFormTemplate('bootstrap3')

# Other stuff

You can quickly create forms with the `<x-qform-form action="..." > <x-qform-form />` component. method will default to POST and also provide @csrf and @method when necessary

A bunch of features aren't documented here

# Notes

This code is not thoroughly and not to any standard. Provided as is.