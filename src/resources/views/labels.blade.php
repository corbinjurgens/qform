@if ($labels)
	<p class="mb-0 mt-3">
		@foreach($labels as $label)
			<span class="{{!empty($label['class']) ? $label['class'] : 'badge badge-primary'}}">{{ $label['label'] }}</span>
		@endforeach
	</p>
@endif