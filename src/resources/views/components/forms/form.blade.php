<form
	@if ($id) id="{{$id}}" @endif
	@if ($class) class="{{$class}}" @endif
	
	@if ($action) action="{{$action}}" @endif
	@if ($method) method="{{$method}}" @endif 
	@if ($attr) {{$attr}} @endif 
	
	@if ($enctype) enctype="{{$enctype}}" @endif 
>
	@if ($csrf)	@csrf @endif
	@method($_method)
	
	{!! $slot !!}
</form>