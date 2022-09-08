@php
    $status     = config('params.status');
    $titleForm  = empty($record) ? config('params.action.add') . ' Category' : config('params.action.edit') . ' Category';
    $blade = [
        'inputs' => require(app_path('Helpers/Form/config/category.php'))
    ];
@endphp
@extends('admin.layouts.master')
@section('main')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <a href="{{ route('admin.category.index', @$request['transform_search']) }}" class="btn btn-outline-info">&laquo; Trang trước</a>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('admin.category.store') }}" method="post" id="formAdd" enctype="multipart/form-data">
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
@endsection 
 
 