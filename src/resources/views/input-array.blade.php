
{{-- Assumes youll be doing something with the json data eg with handlebars, so leaves an open input section 
You should make sure the value data coming in is an array (set manually with QForm set_value if necessary)
Passing a string here will work, and data-mode will will be 'string' and you can decide case by case if you want to JSON.parse
data-name raw name, useful if there is prefix used in normal name like name="hello[world]"
data-json json or string
data-mode 'array' or 'string'
--}}
<div id="json-{{ $id }}" class="json-input-element" data-errors="{{ json_encode($errors) }}" data-json="{{ is_string($value) ? $value : (is_object($value) ? $value->toJson() : json_encode($value)) }}" data-mode="{{ is_string($value) ? 'string' : 'array' }}" >
	<div class="json-pre scope-container-pre"></div>
	<div class="json-content scope-item scope-max scope-container row" data-type="{{ $alt_type }}" data-title="{{ $text }}" data-name="{{ $name }}" aria-labelledby="label-{{ $id }}" aria-describedby="{{ $id }}-help" role="group" data-required="{{$required ? '1' : ''}}"></div>
	<div class="json-post scope-container-post"></div>
	{{-- .json-input-element #json-$name use handlebars or other script to display content here --}}
</div>