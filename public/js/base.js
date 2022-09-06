base = typeof(window.base) === 'object' ? window.base : {};

base.list  = typeof(base.list) === 'object' ? base.list : {};

base.msgs = {
  remind_delete: '',
}
/****************list *****************************/ 

// remove attribute readonly
$("input.ordering").dblclick(function(){
  $(this).removeAttr("readonly");
});

// check all checkboxes
base.list.toggleCheckboxSelection = function(el) {
  var boxes = $(el).parents('table').find('tbody tr td:first-child').find('input[type=checkbox]');
  var checkAll = $(el).prop('checked');
  boxes.each(function() { $(this).prop('checked', checkAll); });
};

// redirect to edit page, have a conditions search
base.list.redirectToEditPage = function(el, idForm) { // transformSearch, nằm ở search.blade.php
  var id = $(el).data('id');
  $('#' + idForm+ ' input[type=hidden][name=id]').val(id);
  $('#' + idForm ).attr('action', $(el).data('href')).submit();
};

base.list.updateStatus = function(el) {
  var dataSend = {
    id      : $(el).data('id'),
    status  : $(el).is(':checked'),
  };
  $.ajax({
    type: "POST",
    url: $(el).data('href'),
    data: dataSend,
    success: function (res) {
      if (res.success) {
        $(el).notify(res.msg, { 
            className: 'success',
          }
        );
      }
    }
  });
}

base.list.updateOrdering = function(el) {
  var clsInvalid = 'is-invalid';
  var textDanger = 'span.text-danger';
  var dataSend = {
    id        : $(el).data('id'),
    ordering  : $(el).val(),
  };
  $.ajax({
    type: "POST",
    url: $(el).data('href'),
    data: dataSend,
    success: function (res) {
    if (res.success) {
        $(el).prop('readonly', true);
        $(el).siblings(textDanger).remove();
        $(el).removeClass(clsInvalid);
        
        $(el).notify(res.msg, { 
            className: 'success',
          }
        );
      }
    },
    error: function(jqXHR) {
      var res = jqXHR.responseJSON;
      $(el).removeClass(clsInvalid);
      $(el).addClass(clsInvalid);
      $(el).siblings(textDanger).remove();
      $(el).after(`<span class="text-danger">${res.errors}</span>`);
    }
  });
}

base.list.deleteData = function(el) {
  var selections = new Array();

  $('table tbody input[name="selections[]"]:checked').each(function() {
    selections.push($(this).val());
  });
      
  if (selections.length == 0) {
    $(el).notify(base.msgs.remind_delete, { 
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
          var url = $(el).data('href');
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
