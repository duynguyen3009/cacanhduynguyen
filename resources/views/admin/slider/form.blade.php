@php
    $status = config('params.status');
    $titleForm = empty($record) ? config('params.action.add') . ' Slider' : config('params.action.edit') . ' Slider';
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
                                        <div class="form-group mb-05">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <input type="text" id="{{ $column }}" name="{{ $column }}" value="{{ old($column, @$record->$column) }}" class="form-control">
                                        </div>
                                        <span class="text-danger error" id="{{ $column }}_error" ></span>
                                        <br />
                                        <br />
                                    @break
                                    @case('datepicker')
                                        <div class="form-group has-feedback mb-05">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <input type="text" name="{{ $column }}" value="{{ old($column, @$record->$column) }}" class="form-control datepicker" id="{{ $column }}">
                                        </div>
                                        <span class="text-danger error" id="{{ $column }}_error" ></span>
                                        <br />
                                        <br />
                                    @break
                                    @case('textarea')
                                        <div class="form-group mb-05">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <textarea id="{{ $column }}" name="{{ $column }}" class="form-control" rows="4">{{ old($column, @$record->$column) }}</textarea>
                                        </div>
                                        <span class="text-danger error" id="{{ $column }}_error" ></span>
                                        <br />
                                        <br />
                                    @break
                                    @case('select')
                                        <div class="form-group mb-05">
                                            <label for="{{ $column }}">{{ $config['label'] }}</label>
                                            <span class="text-danger font-weight-bold">*</span>
                                            <select class="form-control" name="{{ $column }}">
                                                @foreach ($status as $key => $v)
                                                    <option value="{{ $key }}" {{ (old($column) == $key) || (@$record->$column == $key) ? "selected" :""}}>
                                                        {{ $v }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger error" id="{{ $column }}_error" ></span>
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

      // save
      $('#formAdd').on('submit', function(e){
          e.preventDefault();
          var form = this;
          $.ajax({
              url     : $(form).attr('action'),
              method  : $(form).attr('method'),
              data    : new FormData(form),
              processData:false,
              dataType:'json',
              contentType:false,
              beforeSend:function(){
                  $(form).find('span.error').text('');
              },
              success:function(res){
                  if (res.success) {
                    $('form#tmpForm').attr('action', res.url).submit();
                  }
              },
              error: function(jqXHR, status, error) {
                switch (jqXHR.status) {
                    case 422:
                      $.each(jqXHR.responseJSON.errors, function (column, msg){
                        $(form).find('span#'+column+'_error').text(msg);
                      });
                    break;
                
                    default:
                      alert('SERVER ERROR');
                      return
                    break;
                }
                
              }
          });
      });
    </script>
@endpush
@endsection 
 
 