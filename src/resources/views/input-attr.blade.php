@if ((isset($loop) && $loop->first) || (!isset($loop)))
	@if ($required) required @endif
	aria-required="{{ $required ? 'true' : 'false' }}"
	aria-invalid="{{ $form->error() ? 'true' : 'false' }}"
@endif

@if(isset($loop) && is_array($variableAttributes))
	@foreach($variableAttributes as $d => $value)
		@if(isset($value[$key]) && $value[$key] !== false)
			{{ $d }}="{{ $value[$key] }}"
		@endif
	@endforeach
@endif

type="{{ $type }}"
name="{{ (isset($alt_name) ? $alt_name : $name)}}"
@if(($alt_value ?? null) !== false)value="{{ (isset($alt_value) ? $alt_value : $value)}}"@endif
@if(($aria_describedby ?? null) !== false)aria-describedby="{{ ($aria_describedby ?? null) ? $aria_describedby : $id . '-help' }}"@endif
id="input-{{ $id }}{{ isset($loop) ? '-' . $loop->iteration : '' }}" 
class="{{ $class ?? '' }}
input-{{$basename}} 
@if ($required) required @endif 
{{ $attributes->get('class') }}" 
{{ $attributes->except('class') }}