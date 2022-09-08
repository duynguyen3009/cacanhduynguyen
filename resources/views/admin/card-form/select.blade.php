<div class="form-group mb-05">
    <label for="{{ $column }}">{{ $label }}</label>
    <span class="text-danger font-weight-bold">*</span>
    <select class="form-control" name="{{ $column }}">
        @foreach ($items as $key => $item)
            <option value="{{ $key }}" {{ (old($column) == $key) || (@$value == $key) ? "selected" :""}}>
                {{ $item }}
            </option>
        @endforeach
    </select>
</div>
<span class="text-danger error" id="{{ $column }}_error" ></span>