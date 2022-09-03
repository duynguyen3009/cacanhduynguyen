@php
    $status = config('params.status');
    $titleForm = empty($record) ? 'Thêm Slider' : 'Chỉnh sửa Slider';
@endphp
@extends('layouts.master')
@section('main')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <a href="{{ route('slider', @$request['transform_search']) }}" class="btn btn-dark">Trang trước</a>
            </div>
            {{-- <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                <li class="breadcrumb-item active">Thêm Slider</li>
                </ol>
            </div> --}}
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('store') }}" method="post" id="formAdd">
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
                <div class="col-md-12">
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
                                <input type="file" name="file_image" id="image" class="form-control" accept="image/*">
                                <input type="hidden" name="image" value="{{ !empty($record) ? $record->image : '' }}">
                            </div>
                            <div class="form-group" id="preview-image">
                                @if (!empty($record))
                                     <img src="{{ asset("storage/sliders") . '/' .$record->image }}" width="200" height="100"/>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="name">Tên</label>
                                <span class="text-danger font-weight-bold">*</span>
                                <input type="text" id="name" name="name" value="{{ old('name', @$record->name) }}" class="form-control @error('name') is-invalid @enderror">
                            </div>
                            @error('name')
                                <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                            @enderror
                            <br />
                            <div class="form-group">
                                <label for="date_show_start">Ngày bắt đầu trình bày</label>
                                <span class="text-danger font-weight-bold">*</span>
                                <input type="text" name="date_show_start" value="{{ old('date_show_start', @$record->date_show_start) }}" class="form-control datepicker" id="date_show_start">
                            </div>
                            @error('date_show_start')
                                <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                            @enderror
                            <br />

                            <div class="form-group">
                                <label for="date_show_end">Ngày kết thúc trình bày</label>
                                <span class="text-danger font-weight-bold">*</span>
                                <input type="text" name="date_show_end" class="form-control datepicker" value="{{ old('date_show_end', @$record->date_show_end) }}" id="date_show_end">
                            </div> 
                            @error('date_show_end')
                                <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                            @enderror
                            <br />




                            <div class="form-group">
                                <label for="href">Href</label>
                                <span class="text-danger font-weight-bold">*</span>
                                <input type="text" id="href" name="href" value="{{ old('href', @$record->href) }}" class="form-control @error('href') is-invalid @enderror">
                            </div>
                            @error('href')
                                <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                            @enderror
                            <br />
                            <div class="form-group">
                                <label for="description">Miêu tả</label>
                                <span class="text-danger font-weight-bold">*</span>
                                <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', @$record->description) }}</textarea>
                            </div>
                            @error('description')
                                <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                            @enderror
                            <br />
                            <div class="form-group">
                                <label for="status">Trạng thái </label> 
                                <span class="text-danger font-weight-bold">*</span>
                                <select class="form-control @error('status') is-invalid @enderror" name="status">
                                    @foreach ($status as $key => $v)
                                        <option value="{{ $key }}" {{ (old('status') == $key) || (@$record->status == $key) ? "selected" :""}}>
                                            {{ $v }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('status')
                                <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                            @enderror
                            <br />
                            <div class="form-group">
                                <label for="ordering">Vị trí</label>
                                <span class="text-danger font-weight-bold">*</span>
                                <input type="text" id="ordering" name="ordering" value="{{ old('ordering', @$record->ordering) }}" class="form-control @error('ordering') is-invalid @enderror">
                            </div>
                            @error('ordering')
                                <div class="text-danger" style="margin-top: -15px">{{ $message }}</div>
                            @enderror
                            <br />
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>    
            <div class="row">
                <div class="col-12">
                <input type="submit" value="Lưu lại" class="btn btn-primary float-right">
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
        var url       = '{{ route("uploadImage") }}';
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
 
 