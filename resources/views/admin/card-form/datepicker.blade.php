<div class="form-group has-feedback mb-05">
    <label for="{{ $column }}">{{ $label }}</label>
    <span class="text-danger font-weight-bold">*</span>
    <input type="text" name="{{ $column }}" value="{{ old($column, @$value) }}" class="form-control datepicker" id="{{ $column }}">
</div>
<span class="text-danger error" id="{{ $column }}_error" ></span>