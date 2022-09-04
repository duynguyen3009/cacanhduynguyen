@php
    $status = config('params.status');
    $titleForm = empty($record) ? config('params.action.add') . ' Slider' : config('params.action.edit') . ' Slider';
    $blade = [
        'inputs' => require(app_path('Helpers/Form/config/slider.php'))
    ];
@endphp
@extends('layouts.master')
@section('main')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <a href="{{ route('admin.slider.index', @$request['transform_search']) }}" class="btn btn-outline-info">&laquo; Trang trước</a>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('admin.slider.store') }}" method="post" id="formAdd">
            @csrf
            @if (!empty($record))
                <input type="hidden" name="id" value="{{ $record->id }}">
            @endif
            <!-- transform_search -->
            @if (isset($request['transform_search']))
                @foreach ($request['transform_search'] as $column => $v)
                    @if ($v != null || $v != 0)
                        <input type="hidden" name="transform_search[{{$column}}]" value="{{ $v }}" /> 
                    @endif
                @endforeach
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase">{{ $titleForm }}</h3>
    
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach ($blade['inputs'] as $column => $config)
                                @switch($config['type'])
                                    @case('input')
                                        <div class="form-group">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <input type="text" id="{{ $column }}" name="{{ $column }}" value="{{ old($column, @$record->$column) }}" class="form-control @error($column) is-invalid @enderror">
                                        </div>
                                        @error($column)
                                            <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                                        @enderror
                                        <br />
                                    @break
                                    @case('datepicker')
                                        <div class="form-group has-feedback">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <input type="text" name="{{ $column }}" value="{{ old($column, @$record->$column) }}" class="form-control datepicker @error($column) is-invalid @enderror" id="{{ $column }}">
                                        </div>
                                        @error($column)
                                            <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                                        @enderror
                                        <br />
                                    @break
                                    @case('textarea')
                                        <div class="form-group">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <textarea id="{{ $column }}" name="{{ $column }}" class="form-control @error($column) is-invalid @enderror" rows="4">{{ old($column, @$record->$column) }}</textarea>
                                        </div>
                                        @error($column)
                                            <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                                        @enderror
                                        <br />
                                    @break
                                    @case('select')
                                        <div class="form-group">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <select class="form-control @error($column) is-invalid @enderror" name="{{ $column }}">
                                                @foreach ($status as $key => $v)
                                                    <option value="{{ $key }}" {{ (old($column) == $key) || (@$record->$column == $key) ? "selected" :""}}>
                                                        {{ $v }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error($column)
                                            <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                                        @enderror
                                        <br />
                                    @break
                                @endswitch
                            @endforeach

                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title text-uppercase">{{ $titleForm }}</h3>
    
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Hình ảnh</label>
                                <input type="file" name="file_image" id="image" class="form-control-file" accept="image/*">
                                <input type="hidden" name="image" value="{{ !empty($record) ? $record->image : '' }}">
                            </div>
                            <div class="form-group" id="preview-image">
                                @if (!empty($record))
                                     <img src="{{ asset("storage/sliders") . '/' .$record->image }}" width="200" height="100"/>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
            <div class="row">
                <div class="col-6">
                    <input type="submit" value="Lưu lại" class="btn btn-primary float-right col-4">
                </div>
            </div>
        </form>
    </section>
    <br />
    <!-- /.content -->

@push('js')
    <script type="text/javascript">
      $("input[type=file][name=file_image]").on('change', function() {
        event.preventDefault();
        var prvImg    = $('div#preview-image');
        var formData  = new FormData($('#formAdd')[0]);
        var url       = '{{ route("admin.slider.uploadImage") }}';
        $.ajax({
          type: "POST",
          url: url,
          data: formData,
          // enctype: 'multipart/form-data',
          contentType: false,
          processData: false,
          success: function (res) {
            if (res.error) {
              alert(res.msg);
              return
            }
            var src = '{{ asset("storage/sliders") }}' + '/' + res.file_name;
            var img = `<img src="${src}" width="200" height="100"/>`;
            prvImg.empty();
            prvImg.append(img);
            $('input[name=image]').val(res.file_name);
          }
        });
      });
      
    </script>
@endpush
@endsection 
 
 