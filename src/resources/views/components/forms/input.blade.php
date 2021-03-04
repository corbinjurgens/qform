@if ($type == 'hidden')
<input type="{{ $type }}" value="{{ $value }}" name="{{ $name }}" id="input-{{ $id }}">
@else
@if ($surround)<div class="@if($inline && in_array($type, ['checkbox', 'radio'])) form-check-inline @else form-group @endif
	@if ($type == 'checkbox' && !is_array($variables)) form-check @endif">@endif

	

	@include('qform::labels')
	
	@if ($type == 'textarea')
	
		<label @include('qform::label-attr')>{{ $text }}@include('qform::label-postfix')</label>
		<textarea @include('qform::input-attr', ['alt_value' => false, 'class' => 'form-control'])>{{ $value }}</textarea>
		
		
	@elseif ($type == 'select')
		
		<label @include('qform::label-attr')>{{ $text }}@include('qform::label-postfix')</label>
		<select @include('qform::input-attr', ['alt_value' => false, 'class' => 'form-control'])>
			@php
				if(is_numeric($value)){
					$value = $value + 0;
				}
			@endphp
			
			@foreach ($variables as $key => $variable)
				<option value="{{ $key }}" @if ($key === ($value)) selected @endif>{{ $variable }}</option>
			@endforeach
		</select>
		
	@elseif ($type == 'checkbox' && is_array($variables))
	{{-- Value always 1, acting as boolean --}}
		<fieldset role="radiogroup" aria-labelledby="{{ $id }}-group">
		<legend class="col-form-label" id="{{ $id }}-group">{{ $text }}@include('qform::label-postfix')</legend>
			@php
				if(is_numeric($value)){
					$value = $value + 0;
				}
			@endphp
		
		@foreach($variables as $key => $variable)
			<div class="form-check form-check-inline">
				<input @if ( in_array($key, is_array($value) ? $value : []) ) checked="" @endif @include('qform::input-attr', ['alt_name' => $name . '[]', 'alt_value' => $key, 'aria_describedby' => $id . '-group', 'class' => 'form-check-input'])>
				<label class="form-check-label" @include('qform::label-attr')>{{ $variable ?? $key }}</label>
			</div>
		@endforeach
		</fieldset>
	@elseif ($type == 'checkbox')
	{{-- Value always 1, acting as boolean --}}
		<input @if ( $value ) checked="" @endif @include('qform::input-attr', ['alt_value' => 1, 'class' => 'form-check-input'])>
		<label class="form-check-label" @include('qform::label-attr')>{{ $text }}@include('qform::label-postfix')</label>
		
	@elseif ($type == 'radio' && is_array($variables))
		<fieldset role="radiogroup" aria-labelledby="{{ $id }}-group">
		<legend class="col-form-label" id="{{ $id }}-group">{{ $text }}@include('qform::label-postfix')</legend>
			@php
				if(is_numeric($value)){
					$value = $value + 0;
				}
			@endphp
		@foreach ($variables as $key => $variable)
			<div class="form-check">
				<input class="form-check-input" @if ( ($value) == $key ) checked="" @endif @include('qform::input-attr', ['alt_value' => $key, 'aria_describedby' => $id . '-group', 'class' => 'form-check-input'])>
				<label class="form-check-label" @include('qform::label-attr')>{{ $variable ?? $key }}</label>
			</div>
		@endforeach
		</fieldset>
	@elseif ($type == 'file')
		<label @include('qform::label-attr')>{{ $text }}@include('qform::label-postfix')</label>
		<input @include('qform::input-attr', ['alt_value' => false, 'class' => 'form-control-file'])>
	
	@elseif ($type == 'json')
		<label id="label-{{ $id }}">{{ $text }}@include('qform::label-postfix')</label>
		<small id="{{ $id }}-help" class="form-text text-muted"><x-qform-error :form="$form" :message="$error"/>{{ $guide }}</small>
		
		@include('qform::input-array')
	@else
		
		<label @include('qform::label-attr')>{{ $text }}@include('qform::label-postfix')</label>
		<input @include('qform::input-attr', ['class' => 'form-control'])>
		
	@endif
	
	@if ($type != 'json')<small id="{{ $id }}-help" class="form-text text-muted"><x-qform-error :message="$error"/>{!! $guide !!}</small>@endif
	
@if ($surround)</div>@endif
@endif