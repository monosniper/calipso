@foreach(request()->all() as $field => $value)
    @if(isset($except))
        @if(!in_array($field, $except))
            <input value="{{ is_array($value) ? implode(',', $value) : $value }}" type="hidden" name="{{$field}}">
        @endif
    @else
        <input value="{{ is_array($value) ? implode(',', $value) : $value }}" type="hidden" name="{{$field}}">
    @endif
@endforeach
