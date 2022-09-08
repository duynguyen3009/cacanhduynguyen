<div class="form-group mb-05">
    <label for="{{ $column }}">{{ $label }}</label>
    <span class="text-danger font-weight-bold">*</span>
    <textarea id="{{ $column }}" name="{{ $column }}" class="form-control" rows="4">{{ old($column, @$value) }}</textarea>
</div>
<span class="text-danger error" id="{{ $column }}_error" ></span>