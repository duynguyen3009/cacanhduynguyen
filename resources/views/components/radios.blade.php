@props(['list' => [], 'data' => ''])

@foreach($list as $k => $v)
    @php
        $id = str_replace(["[", "]"], ["_", ""], $attributes['name']) . "_$k";
    @endphp
    <div class="{{ $attributes->get('contain-class') }}">
    <input type="radio" value="{{ $k }}" id="{{$id}}"
            {!! strval($data) === strval($k) ? 'checked=checked' : '' !!}
            {!! $attributes->except(['contain-class', 'label-class']) !!}/>
    <label class="{{ $attributes->get('label-class') }}" for="{{$id}}">{{ $v }}</label>
    </div>
@endforeach