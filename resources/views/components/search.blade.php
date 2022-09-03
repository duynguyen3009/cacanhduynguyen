@props(['list' => [], 'data' => '', 'request' => []])
@php
    $s                  = request('s');
    $o                  = request('o');
    $q                  = request('q');
    $status             = config('params.status');
    $listOrdering       = @$request['fieldsAcceptOrdering'];
    $transformSearch    = [
                            's' => $s,
                            'q' => $q,
                            'o' => $o,
                        ];
    $currentRouteName = \Request::route()->getName();
@endphp
<div class="content-header">
    <div class="container-fluid">
        <form action="{{ url()->current() }}" method="GET" id="formSearch">
            <div class="row" >
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="Nhập để tìm kiếm">
                        </div>
                        <div class="col-sm-3">
                            <x-select :list="$status" :data="@$s" name='s' />
                        </div>
                        <div class="col-sm-3">
                            <x-select :list="$listOrdering" :data="@$o" name='o'/>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-8">
                    <button type="submit" class="col-sm-2 btn btn-info ml-1">Tìm kiếm</button>
                    <a href="{{ route($currentRouteName) }}" class="col-sm-2 btn btn-secondary ml-1">Xóa tìm kiếm</a>
                </div>
            </div>
        </form>
    </div>
</div>
<hr>
<div class="d-flex justify-content-between m-3">
    <div>
        <button type="button" class="btn btn-danger" onclick="deleteData()">Xóa</button>
    </div>
    <div>
        <form action="#" method="post" id="transformSearch">
            @csrf
            <input type="hidden" name="id" value="">
            @foreach ($transformSearch as $column => $v)
                @if ($v != null || $v != 0)
                    <input type="hidden" name="transform_search[{{$column}}]" value="{{ $v }}" /> 
                @endif
            @endforeach
        </form>
        <button type="button" data-href="{{ route('form') }}" 
            class="btn btn-success"
            onclick="$('#transformSearch').attr('action', $(this).data('href')).submit()"
            >+ Thêm
        </button>
    </div>
</div>