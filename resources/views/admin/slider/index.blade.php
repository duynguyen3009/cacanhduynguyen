@extends('layouts.master')
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
                    <th style="width: 3%"><input type="checkbox" name="check_all" id="check_all" onclick="checkAll(this)"></th>
                    <th style="width: 3%">#</th>
                    <th style="width: 15%">Tên Slider</th>
                    <th style="width: 30%">Nội dung</th>
                    <th style="width: 10%">Ngày bắt đầu</th>
                    <th style="width: 10%">Ngày kết thúc</th>
                    <th style="width: 15%">Trạng thái</th>
                    <th style="width: 10%">Vị trí</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $i => $record)
                    @php
                        $classStatus = ($record->status == 'active') ? 'success' : 'secondary';
                        $valueStatus = ($record->status == 'active') ? 'Kích hoạt' : 'Chưa kích hoạt';
                    @endphp
                    <tr>
                        <td><input type="checkbox" name="selections[]" value="{{ $record->id }}"></td>
                        <td>{{ $i+1 }}</td>
                        <td class="col-1">
                          <a href="javascript:void(0)" 
                            data-href="{{ route('form') }}"
                            data-id="{{ $record->id }}"
                            onclick="showFormEdit(this)">
                            {{ $record->name }}
                          </a>
                        </td>

                        <td>
                            <div class="row" >
                                <div class="col-12">
                                    <img src="{{ asset("storage/sliders") . '/' .$record->image }}" width="100%" height="100"/>
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
                            <a data-value="{{ $record->status }}" 
                                data-id="{{ $record->id }}"
                                data-current_class="{{$classStatus}}"
                                class="btn btn-{{$classStatus}}" 
                                style="width: 140px"
                                id="status-{{ $record->id }}"
                                onclick="updateStatus(this)"
                            >
                                {{ $valueStatus }}
                            </a>
                        </td>
                        <td>
                            <input class="form-control ordering" 
                                  type="text" 
                                  data-id="{{ $record->id }}"
                                  id="ordering-{{ $record->id }}"
                                  value="{{ $record->ordering }}" 
                                  readonly
                            >
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix d-flex justify-content-end">
        {{ $records->links('pagination.simple-bootstrap-4') }}
    </div>
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
    function updateStatus(element)
    {
      var url = '{{ route("updateStatus") }}';
      var dataSend = {
        id            : $(element).data('id'),
        status        : $(element).data('value'),
        current_class : $(element).data('current_class'),
      };
      $.ajax({
        type: "POST",
        url: url,
        data: dataSend,
        success: function (res) {
          if (res.success) {
            $(element).removeClass('btn-' + res.class_current);
            $(element).addClass('btn-' + res.class_after_update);
            $(element).text('');
            $(element).text(res.text_after_update);
            $(element).data('value', res.value_after_update);
            $(element).data('current_class', res.class_after_update);
            $.notify(res.msg, { 
                className: 'success',
              }
            );
          }
        }
      });
    }

    // remove attribute readonly
    $("input.ordering").dblclick(function(){
      $(this).removeAttr("readonly");
    });

    //update ordering
    $("input.ordering").change(function(){
      var url = '{{ route("updateOrdering") }}';
      var dataSend = {
        id        : $(this).data('id'),
        ordering  : $(this).val(),
      };
      $.ajax({
        type: "POST",
        url: url,
        data: dataSend,
        success: function (res) {
          if (res.success) {
            $('#ordering-' + res.id).prop('readonly', true);
            $('#ordering-' + res.id).siblings('span.text-danger').remove();
            $('#ordering-' + res.id).removeClass('is-invalid');
            
            $.notify(res.msg, { 
                className: 'success',
              }
            );
          }
        },
        error: function(jqXHR, status, error) {
          var res = jqXHR.responseJSON;
          $('#ordering-' + res.id).removeClass('is-invalid');
          $('#ordering-' + res.id).addClass('is-invalid');
          $('#ordering-' + res.id).siblings('span.text-danger').remove();
          $('#ordering-' + res.id).after(`<span class="text-danger">${res.errors}</span>`);
        }
      });
    });

    // delete
    function deleteData() {
      event.preventDefault(); // prevent form submit
      var selections = new Array();

      $('input[name="selections[]"]:checked').each(function() {
        selections.push($(this).val());
      });
      
      if (selections.length == 0) {
        $.notify('Bạn chưa chọn dòng để xóa !', { 
            position: 'top left',
            className: 'error',
          }
        );
        return
      }
      swal({
            title: `Bạn đang chọn và muốn xóa ${selections.length} dòng ?`,
            text: "Các id sau: " + selections,
            icon: "warning",
            buttons: ["Hủy", "Chấp nhận xóa"],
            dangerMode: true,
           })
          .then((willDelete) => {
            if (willDelete) {
              var url = "{{ route('deleteData') }}" ;
              var dataSend = selections.reduce(function(o, val) { o[val] = val; return o; }, {});
              $.ajax({
                type: "POST",
                url: url,
                data: dataSend,
                success: function (res) {
                  if (res.success) {
                    $.notify(res.msg, {className: 'success',});
                    location.reload();
                  }
                }
              });
            } 
        });
    }

    // check all
    function checkAll(element)
    {
      var checked = $(element).prop('checked');
      $('#listSlider').find('input[name="selections[]"]').prop('checked', checked);
    }

    //showFormEdit
    function showFormEdit(element)
    {
      var id = $(element).data('id');
      $('#transformSearch input[type=hidden][name=id]').val(id);
      $('#transformSearch').attr('action', $(element).data('href')).submit();
    }
  </script>
@endpush
@endsection 
 
 