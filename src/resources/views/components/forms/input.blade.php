@if ($type == 'hidden')
<input type="{{ $type }}" value="{{ $form->value() }}" name="{{ $form->id() }}" id="input-{{ $form->id() }}">
@else
@if ($surround)<div class="form-group @if ($type == 'checkbox') form-check @endif">@endif
	@if ($type == 'textarea')
	
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}@if ($required) <span class="text-danger">*</span> @endif</label>
		<textarea @if ($required) required @endif class="form-control" aria-describedby="{{ $form->id() }}-help" id="input-{{ $form->id() }}" name="{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif">@if ($hideValue === false){{ $form->value() }}@endif</textarea>
		
		
	@elseif ($type == 'select')
		
		@php ($value = $form->value())
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}@if ($required) <span class="text-danger">*</span> @endif</label>
		<select @if ($required) required @endif class="form-control" aria-describedby="{{ $form->id() }}-help" id="input-{{ $form->id() }}" name="{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif">
			@foreach ($variables as $key => $variable)
				<option value="{{ $key }}" @if ($key == ($value)) selected @endif>{{ $variable }}</option>
			@endforeach
		</select>
		
		
	@elseif ($type == 'checkbox')
	{{-- Value always 1, acting as boolean --}}
		
		<input @if ($required) required @endif type="checkbox" class="form-check-input" value="1" @if ( $form->value() ) checked="" @endif name="{{ $form->id() }}" id="input-{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif" aria-describedby="{{ $form->id() }}-help">
		<label class="form-check-label" for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}@if ($required) <span class="text-danger">*</span> @endif</label>
		
	@elseif ($type == 'radio' && is_array($variables))
		@php ($value = $form->value())
		<fieldset role="radiogroup" aria-labelledby="{{ $form->id() }}-group">
		<legend class="col-form-label" id="{{ $form->id() }}-group">{{ $text ?? $form->text() }}@if ($required) <span class="text-danger">*</span> @endif</legend>
		@foreach ($variables as $key => $variable)
			<div class="form-check">
				<input type="{{ $type }}" class="form-check-input" value="{{ $key }}" @if ( ($value) == $key ) checked="" @endif name="{{ $form->id() }}" id="input-{{ $form->id() }}-{{ $loop->iteration }}" @if ($loop->first) @if ($required) required @endif aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif" aria-describedby="{{ $form->id() }}-help" @endif>
				<label class="form-check-label" for="input-{{ $form->id() }}-{{ $loop->iteration }}">{{ $variable ?? $key }}</label>
			</div>
		@endforeach
		</fieldset>
	@elseif ($type == 'file')
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}@if ($required) <span class="text-danger">*</span> @endif</label>
		<input @if ($required) required @endif type="{{ $type }}" class="form-control-file" name="{{ $form->id() }}" id="input-{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif" aria-describedby="{{ $form->id() }}-help">
	
	@elseif ($type == 'json')
		{{-- Assumes youll be doing something with the json data eg with handlebars, so leaves an open input section 
		You should make sure the data coming in is an array (set manually with QForm set_value if necessary)
		Passing a string here will work, and data-mode will will be 'string'
		data-name raw name, useful if there is prefix used in normal name like name="hello[world]"
		data-json json or string
		data-mode array/string
		--}}
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}@if ($required) <span class="text-danger">*</span> @endif</label>
		<small id="{{ $form->id() }}-help" class="form-text text-muted"><x-qform-error :form="$form" :message="$form->error()"/>{{ $guide ?? $form->guide() }}</small>
		<div id="json-{{ $form->id() }}" class="json-input-element" data-name="{{ $form->id() }}" data-json="{{ is_string($form->value()) ? $form->value() : json_encode($form->value()) }}" data-mode="{{ is_string($form->value()) ? 'string' : 'array' }}" aria-labelledby="input-{{ $form->id() }}" aria-describedby="{{ $form->id() }}-help" role="group">
			<div class="json-pre"></div>
			<div class="json-content"></div>
			<div class="json-post"></div>
			{{-- .json-input-element #json-$name use handlebars or other script to display content here --}}
		</div>
	@else
		
		<label for="input-{{ $form->id() }}">{{ $text ?? $form->text() }}@if ($required) <span class="text-danger">*</span> @endif</label>
		<input @if ($required) required @endif type="{{ $type }}" class="form-control" value="@if ($hideValue === false){{ $form->value() }}@endif" name="{{ $form->id() }}" id="input-{{ $form->id() }}" aria-invalid="@if ($form->error()){{'true'}}@else{{'false'}} @endif" aria-describedby="{{ $form->id() }}-help">
		
	@endif
	@if ($type != 'json')<small id="{{ $form->id() }}-help" class="form-text text-muted"><x-qform-error :form="$form" :message="$form->error()"/>{{ $guide ?? $form->guide() }}</small>@endif
@if ($surround)</div>@endif
@endif