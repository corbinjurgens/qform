<button type="submit" class="@if ($class) {{$class}} @else btn btn-primary @endif" id="{{$id}}" @if ($name) name="{{ $name }}" @endif>{{ $text }}</button>