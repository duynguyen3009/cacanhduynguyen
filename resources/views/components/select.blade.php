@props(['list' => [], 'data' => ''])

<select {!! $attributes !!} class="form-control">
    {!! $slot !!}
    @foreach($list as $k => $v)
        <option value="{{ $k }}"
                {!! strval($data) === strval($k) ? 'selected=selected' : '' !!}>{!! $v !!}</option>
    @endforeach
</select>
