<div class="form-group mb-05">
    <label for="{{ $column }}">{{ $label }}</label>
    <span class="text-danger font-weight-bold">*</span>
    <input type="text" id="{{ $column }}" name="{{ $column }}" value="{{ old($column, $value) }}" class="form-control">
</div>
<span class="text-danger error" id="{{ $column }}_error" ></span>