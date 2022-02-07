@if ($type == 'hidden')
	<input type="{{ $type }}" value="{{ $value }}" name="{{ $name }}" {{ $attributes->merge(['id' => 'input-' . $id_fallback]) }}>
@else
	@if ($surround ?? false)<div class="@if($inline && in_array($type, ['checkbox', 'radio'])) form-check-inline @else form-group @endif
		@if ($type == 'checkbox' && !is_array($variables)) form-check @endif">@endif

		@include('qform::labels')
		
		@if ($type == 'textarea')
			<label @include('qform::label-attr')>{{ $title }}@include('qform::label-postfix')</label>
			<textarea @include('qform::input-attr', ['alt_value' => false, 'class' => 'form-control'])>{{ $value }}</textarea>
			
			
		@elseif ($type == 'select')
			<label @include('qform::label-attr')>{{ $title }}@include('qform::label-postfix')</label>
			<select @include('qform::input-attr', ['alt_value' => false, 'class' => 'form-control'])>
				@foreach ($variables as $key => $variable)
					<option value="{{ $key }}" @if ($key === ($value)) selected @endif>{{ $variable }}</option>
				@endforeach
			</select>
			
		@elseif ($type == 'checkbox' && is_array($variables))
		{{-- Value always 1, acting as boolean --}}
			<fieldset role="radiogroup" aria-labelledby="{{ $attributes->get('id', $id_fallback) }}-group">
			<legend class="col-form-label" id="{{ $attributes->get('id', $id_fallback) }}-group">{{ $title }}@include('qform::label-postfix')</legend>
			@foreach($variables as $key => $variable)
				@php
					$attributes['id'] = $attributes->get('id', 'input-' . $id_fallback) . (isset($loop) ? '-' . $loop->iteration : '');
				@endphp
				<div class="form-check form-check-inline">
					<input @if ( in_array($key, is_array($value) ? $value : [], true) ) checked="" @endif @include('qform::input-attr', ['name' => $name . '[]', 'alt_value' => $key, 'aria_describedby' => $attributes->get('id', $id_fallback) . '-group', 'class' => 'form-check-input'])>
					<label class="form-check-label" @include('qform::label-attr')>{{ $variable ?? $key }}</label>
				</div>
			@endforeach
			</fieldset>
		@elseif ($type == 'checkbox')
		{{-- Value always 1, acting as boolean --}}
			<input @if ( $value ) checked="" @endif @include('qform::input-attr', ['alt_value' => 1, 'class' => 'form-check-input'])>
			<label class="form-check-label" @include('qform::label-attr')>{{ $title }}@include('qform::label-postfix')</label>
			
		@elseif ($type == 'radio' && is_array($variables))
			<fieldset role="radiogroup" aria-labelledby="{{ $attributes->get('id', $id_fallback) }}-group">
			<legend class="col-form-label" id="{{ $attributes->get('id', $id_fallback) }}-group">{{ $title }}@include('qform::label-postfix')</legend>
			@foreach ($variables as $key => $variable)
				@php
					$attributes['id'] = $attributes->get('id', 'input-' . $id_fallback) . (isset($loop) ? '-' . $loop->iteration : '');
				@endphp
				<div class="form-check">
					<input class="form-check-input" @if ( ($value) === $key ) checked="" @endif @include('qform::input-attr', ['alt_value' => $key, 'aria_describedby' => $attributes->get('id', $id_fallback) . '-group', 'class' => 'form-check-input'])>
					<label class="form-check-label" @include('qform::label-attr')>{{ $variable ?? $key }}</label>
				</div>
			@endforeach
			</fieldset>
		@elseif ($type == 'file')
			<label @include('qform::label-attr')>{{ $title }}@include('qform::label-postfix')</label>
			<input @include('qform::input-attr', ['alt_value' => false, 'class' => 'form-control-file'])>
		
		@elseif ($type == 'json')
			<label id="label-{{ $attributes->get('id', $id_fallback) }}">{{ $title }}@include('qform::label-postfix')</label>
			<small id="{{ $attributes->get('id', $id_fallback) }}-help" class="form-text text-muted"><x-qform-error :message="$error"/>{{ $subtitle }}</small>
			
			@include('qform::input-array')
		@else
			<label @include('qform::label-attr')>{{ $title }}@include('qform::label-postfix')</label>
			<input @include('qform::input-attr', ['class' => 'form-control'])>
			
		@endif
		
		@if ($type != 'json')<small id="{{ $attributes->get('id', $id_fallback) }}-help" class="form-text text-muted"><x-qform-error :message="$error"/>{!! $subtitle !!}</small>@endif
		
	@if ($surround ?? false)</div>@endif
@endif