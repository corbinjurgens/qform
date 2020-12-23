@if ($type == 'hidden')
<input type="{{ $type }}" value="{{ $form->value() }}" name="{{ $form->id() }}" id="input-{{ $form->id() }}">
@else
@if ($surround)<div class="form-group @if ($type == 'checkbox') form-check @endif">@endif
	@if ($type == 'textarea')
	
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}</label>
		<textarea class="form-control" aria-describedby="{{ $form->id() }}-help" id="input-{{ $form->id() }}" name="{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif">@if ($hideValue === false){{ $form->value() }}@endif</textarea>
		
		
	@elseif ($type == 'select')
		
		@php ($value = $form->value())
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}</label>
		<select class="form-control" aria-describedby="{{ $form->id() }}-help" id="input-{{ $form->id() }}" name="{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif">
			@foreach ($variables as $key => $variable)
				<option value="{{ $key }}" @if ($key == ($value)) selected @endif>{{ $variable }}</option>
			@endforeach
		</select>
		
		
	@elseif ($type == 'checkbox')
	{{-- Value always 1, acting as boolean --}}
		
		<input type="checkbox" class="form-check-input" value="1" @if ( $form->value() ) checked="" @endif name="{{ $form->id() }}" id="input-{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif" aria-describedby="{{ $form->id() }}-help">
		<label class="form-check-label" for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}</label>
		
	@elseif ($type == 'radio' && is_array($variables))
		@php ($value = $form->value())
		<fieldset role="radiogroup" aria-labelledby="{{ $form->id() }}-group">
		<legend class="col-form-label" id="{{ $form->id() }}-group">{{ $text ?? $form->text() }}</legend>
		@foreach ($variables as $key => $variable)
			<div class="form-check">
				<input type="{{ $type }}" class="form-check-input" value="{{ $key }}" @if ( ($value) == $key ) checked="" @endif name="{{ $form->id() }}" id="input-{{ $form->id() }}-{{ $loop->iteration }}" @if ($loop->first)aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif" aria-describedby="{{ $form->id() }}-help" @endif>
				<label class="form-check-label" for="input-{{ $form->id() }}-{{ $loop->iteration }}">{{ $variable ?? $key }}</label>
			</div>
		@endforeach
		</fieldset>
	@else
		
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}</label>
		<input type="{{ $type }}" class="form-control" value="@if ($hideValue === false){{ $form->value() }}@endif" name="{{ $form->id() }}" id="input-{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif" aria-describedby="{{ $form->id() }}-help">
		
	@endif
	<small id="{{ $form->id() }}-help" class="form-text text-muted"><x-qform-error :message="$form->error()"/>{{ $guide ?? $form->guide() }}</small>
@if ($surround)</div>@endif
@endif