<button {{$attributes}} type="submit" class="mr-2 @if ($class) {{$class}} @else btn btn-primary @endif" id="{{$id}}" @if ($name) name="{{ $name }}" @endif>{{ $text }}</button>