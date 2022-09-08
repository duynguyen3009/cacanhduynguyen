@php
    $pathImg = "storage".DIRECTORY_SEPARATOR."sliders";
@endphp
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
                    <th style="width: 15%">Tên Slider</th>
                    <th style="width: 30%">Nội dung</th>
                    <th style="width: 10%">Ngày bắt đầu</th>
                    <th style="width: 10%">Ngày kết thúc</th>
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
                            data-href="{{ route('admin.slider.form') }}"
                            data-id="{{ $record->id }}"
                            onclick="base.list.redirectToEditPage(this, 'transformSearch')">
                            {{ $record->name }}
                          </a>
                        </td>

                        <td>
                            <div class="row" >
                                <div class="col-12">
                                    <img src="{{ asset($pathImg) . DIRECTORY_SEPARATOR . $record->image }}" width="100%" height="100"/>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-12" style="
                                    white-space: nowrap; 
                                    width: 50px; 
                                    overflow: hidden;
                                    text-overflow: ellipsis; 
                                ">
                                    {{ $record->description }}
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-12">
                                    <a href="{{ $record->href }}">Liên kết</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ $record->date_show_start }}
                        </td>
                        <td>{{ $record->date_show_end }}</td>
                        <td>
                            <div class="custom-switch" style="position: relative">
                                <input type="checkbox" class="custom-control-input" 
                                        {{ $record->status == 'active' ? 'checked' : ''}}
                                        id="status-{{ $record->id }}" 
                                        name="status"
                                        data-id="{{ $record->id }}"
                                        data-href="{{ route("admin.slider.updateStatus") }}"
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
                                    data-href="{{ route("admin.slider.updateOrdering") }}"
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
  <script type="text/javascript">
    $.extend(base.msgs, @json($msgs));
  </script>
@endpush
@endsection 
 
 