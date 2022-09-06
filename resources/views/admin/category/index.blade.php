@extends('admin.layouts.master')
@section('main')

<x-search :request="$request"/>

@if (session('action_success'))
    <div class="alert alert-success">
        {{ session('action_success') }}
    </div>
@endif

<div class="card" style="width: 97%; margin: 5px auto;">
    <div class="card-body">
        <table class="table table-bordered" id="listSlider">
            <thead>
                <tr>
                    <th style="width: 3%"><input type="checkbox" name="check_all" id="check_all" onclick="base.list.toggleCheckboxSelection(this)"></th>
                    <th style="width: 3%">#</th>
                    <th style="width: 15%">Tên danh mục</th>
                    <th style="width: 8%">Trạng thái</th>
                    <th style="width: 10%">Vị trí</th>
                </tr>
            </thead>
            <tbody>
                 @foreach ($records as $i => $record)
                    <tr>
                        <td><input type="checkbox" name="selections[]" value="{{ $record->id }}"></td>
                        <td>{{ $i+1 }}</td>
                        <td class="col-1">
                          <a href="javascript:void(0)" 
                            data-href="{{ route('admin.category.form') }}"
                            data-id="{{ $record->id }}"
                            onclick="base.list.redirectToEditPage(this, 'transformSearch')">
                            {{ $record->name }}
                          </a>
                        </td>
                        
                        <td>
                            <div class="custom-switch" style="position: relative">
                                <input type="checkbox" class="custom-control-input" 
                                        {{ $record->status == 'active' ? 'checked' : ''}}
                                        id="status-{{ $record->id }}" 
                                        name="status"
                                        data-id="{{ $record->id }}"
                                        data-href="{{ route("admin.category.updateStatus") }}"
                                        onclick="base.list.updateStatus(this)"
                                >
                                <label class="custom-control-label" for="status-{{ $record->id }}"></label>
                            </div>
                        </td>
                        <td>
                            <div style="position: relative">
                              <input class="form-control ordering" 
                                    type="text" 
                                    data-id="{{ $record->id }}"
                                    id="ordering-{{ $record->id }}"
                                    value="{{ $record->ordering }}" 
                                    data-href="{{ route("admin.category.updateOrdering") }}"
                                    readonly
                                    onchange="base.list.updateOrdering(this)"
                              >
                            </div>
                        </td>
                    </tr>
                @endforeach 
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix d-flex justify-content-end">
        {{ $records->links('pagination.simple-bootstrap-4') }}
    </div>
    @php
        $msgs = [
            'remind_delete' => 'Bạn chưa chọn dòng để xóa',
        ];
    @endphp
</div>
@push('css')
<style>
  table {
    table-layout: fixed;
    word-wrap: break-word;
  }
</style>
@endpush
@push('js')
  <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('plugins/notify.min.js') }}" type="text/javascript"></script>
  <script type="text/javascript">
    $.extend(base.msgs, @json($msgs));
  </script>
@endpush
@endsection 
 
 