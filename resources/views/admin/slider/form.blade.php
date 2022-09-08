@php
    $status     = config('params.status');
    $titleForm  = empty($record) ? config('params.action.add') . ' Slider' : config('params.action.edit') . ' Slider';
    $blade = [
        'inputs' => require(app_path('Helpers/Form/config/slider.php'))
    ];
@endphp
@extends('admin.layouts.master')
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
        <form action="{{ route('admin.slider.store') }}" method="post" id="formAdd" enctype="multipart/form-data">
            @csrf
            @if (!empty($record))
                <input type="hidden" name="id" value="{{ $record->id }}">
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
                            <div class="form-group">
                                <label for="image">Hình ảnh</label>
                                <span class="text-danger font-weight-bold">*</span>
                                <input type="file" name="image" class="form-control-file" accept="image/*">
                                @if (!empty($record))
                                    <input type="hidden" name="old_image" value="{{ $record->image }}" >
                                @endif
                            </div>
                            <div class="form-group" id="preview-image">
                                @if (!empty($record))
                                     <img src="{{ asset("storage/sliders") . '/' .$record->image }}" width="200" height="100"/>
                                @endif
                            </div>
                            <span class="text-danger error" id="image_error" ></span>
                            <br />
                            <br />
                            @foreach ($blade['inputs'] as $column => $config)
                                @switch($config['type'])
                                    @case('input')
                                        @include('admin.card-form.input', [
                                            'label'     => $config['label'],
                                            'column'    => $column,
                                            'value'     => @$record->$column
                                        ])
                                        <br />
                                        <br />
                                    @break
                                    @case('datepicker')
                                        @include('admin.card-form.datepicker', [
                                                'label'     => $config['label'],
                                                'column'    => $column,
                                                'value'     => @$record->$column
                                        ])
                                        <br />
                                        <br />
                                    @break
                                    @case('textarea')
                                        @include('admin.card-form.textarea', [
                                                'label'     => $config['label'],
                                                'column'    => $column,
                                                'value'     => @$record->$column
                                        ])
                                        <br />
                                        <br />
                                    @break
                                    @case('select')
                                    @include('admin.card-form.select', [
                                            'label'     => $config['label'],
                                            'column'    => $column,
                                            'items'     => $status,
                                            'value'     => @$record->$column,
                                        ])
                                        <br />
                                        <br />
                                    @break
                                @endswitch
                            @endforeach
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

        <form action="#" method="get" id="tmpForm">
          <!-- transform_search -->
          @if (isset($request['transform_search']))
            @foreach ($request['transform_search'] as $column => $v)
                @if ($v != null || $v != 0)
                    <input type="hidden" name="{{$column}}" value="{{ $v }}" /> 
                @endif
            @endforeach
          @endif
        </form>
    </section>
    <br />
    <!-- /.content -->

@push('css')
  <style>
    .mb-05 {
      margin-bottom: 0.5rem;
    }
  </style>
@endpush
@push('js')
    <script type="text/javascript">
      //Reset input file
      $('input[type="file"][name="image"]').val('');

      //Image preview
      $('input[type="file"][name="image"]').on('change', function(){
          var img_path    = $(this)[0].value;
          var previewImg  = $('#preview-image');
          var extension   = img_path.substring(img_path.lastIndexOf('.')+1).toLowerCase();

          const extensions  = ["jpeg", "jpg", "png"];
          isExtAccept       = extensions.includes(extension);
          
          if (!(isExtAccept)) {
            $(previewImg).empty();
            $(previewImg).html("<span class='text-danger'>Định dạng không hợp lệ </span>");
          } else {
              if (typeof(FileReader) != 'undefined') {
                previewImg.empty();
                var reader = new FileReader();
                reader.onload = function(e){
                    $('<img/>',{'src':e.target.result,  'style':'max-width:250px; max-height: 150px; margin-bottom:10px;'}).appendTo(previewImg);
                }
                previewImg.show();
                reader.readAsDataURL($(this)[0].files[0]);
              } else {
                $(previewImg).html("<span class='text-danger'>Định dạng không hợp lệ </span>");
              }
          }

      });
    </script>
@endpush
@endsection 
 
 