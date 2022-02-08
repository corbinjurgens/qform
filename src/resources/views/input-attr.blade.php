@if ((isset($loop) && $loop->first) || (!isset($loop)))
	@if ($attributes->has('required')) required @endif
	aria-required="{{ $attributes->has('required') ? 'true' : 'false' }}"
	aria-invalid="{{ $error ? 'true' : 'false' }}"
@endif

@if(isset($loop) && is_array($variableAttributes))
	@foreach($variableAttributes as $d => $value)
		@if(isset($value[$key]) && $value[$key] !== false)
			{{ $d }}="{{ $value[$key] }}"
		@endif
	@endforeach
@endif

type="{{ $type }}"
name="{{ $name }}"
{{-- Alt value used when including loops etc. Or textarea is set as false meaning dont show value at all --}}
@if(!$hide)value="{{ $value }}"@endif
@if(($aria_describedby ?? null) !== false)aria-describedby="{{ ($aria_describedby ?? null) ? $aria_describedby : $attributes->get('id', $id_fallback) }}-help"@endif

{{ $attributes->class(['input-' . $basename, 'required' => $attributes->has('required'), $class ?? '' => isset($class)])->merge(['id' => 'input-' . $id_fallback]) }}