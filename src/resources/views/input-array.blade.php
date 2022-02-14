
{{-- Assumes youll be doing something with the json data eg with handlebars, so leaves an open input section 
You should make sure the value data coming in is an array (set manually with QForm set_value if necessary)
Passing a string here will work, and data-mode will will be 'string' and you can decide case by case if you want to JSON.parse
data-name raw name, useful if there is prefix used in normal name like name="hello[world]"
data-json json or string
data-mode 'array' or 'string'
--}}
<div id="json-{{ $attributes->get('id', $id_fallback) }}" class="json-input-element" data-errors="{{ json_encode($errors) }}" data-json="{{ is_object($value) ? $value->toJson() : json_encode($value) }}" data-mode="{{ is_string($value) ? 'string' : 'array' }}" >
	<div class="json-pre scope-container-pre"></div>
	<div class="json-content scope-item scope-max scope-container row" data-type="{{ $alt_type ?? $type }}" data-title="{{ $title }}" data-name="{{ $name }}" aria-labelledby="label-{{ $attributes->get('id', $id_fallback) }}" aria-describedby="{{ $attributes->get('id', $id_fallback) }}-help" role="group" data-required="{{ $attributes->get('required') ? '1' : ''}}"></div>
	<div class="json-post scope-container-post"></div>
	{{-- .json-input-element #json-$name use handlebars or other script to display content here --}}
</div>