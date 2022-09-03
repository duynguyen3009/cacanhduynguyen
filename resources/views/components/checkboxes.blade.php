@props(['list' => [], 'data' => ''])

@foreach($list as $k => $v)
    @php
        $id = str_replace(["[", "]"], ["_", ""], $attributes['name']) . "_$k";
    @endphp
    <div class="{{ $attributes->get('contain-class') }}">
    <input type="checkbox" value="{{ $k }}" id="{{$id}}"
            {!! in_array($k, $data) ? 'checked=checked' : '' !!}
            {!! $attributes->except(['contain-class', 'label-class']) !!}/>
    <label class="{{ $attributes->get('label-class') }}" for="{{$id}}">{{ $v }}</label>
    </div>
@endforeach