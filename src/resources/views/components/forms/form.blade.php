<form
	{{ $attributes }}
	@if ($method) method="{{$method}}" @endif
	
	@if ($enctype) enctype="{{$enctype}}" @endif 
>
	@if($method != 'GET')
		@if ($csrf)	@csrf @endif
		@method($_method)
	@endif
	
	{!! $slot !!}
	
</form>