$(function () {
    $("#historyDetail").sortable({
        helper: fixWidthHelper,
        update: function(event, ui) {
            
        }
    }).disableSelection();
    //防止表格托拽後縮小修正程序
    function fixWidthHelper(e, ui) {
        ui.children().each(function () {
            $(this).width($(this).width());
        });
        return ui;
    };
    $('.ch').change( function () {
        if ($(this).prop('checked')) {
            var a = $(this).val();
            $('#hi_' + a).show();
        } else {
            $('#hi_' + $(this).val()).hide();
        }
    });
});

function delTask(id) {
    swal({
        title: "刪除資料?",
        text: "此動作將會刪除資料!",
        type: "warning",
        showCancelButton: true,
        cancelButtonText: '取消',
        confirmButtonClass: "btn-danger",
        confirmButtonText: "刪除",
        closeOnConfirm: false
    },
    function () {
        $.ajax({
            url: url + '/Service/DeleteTask',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {'id': id},
            error: function(xhr) {
                swal("刪除失敗!", xhr.statusText, "error");
            },
            success: function(result) {
                if (result.success) {
                    swal({
                        title: "刪除資料成功!",
                        text: result.msg,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function() {
                        $('#taskDetail #ta_' + id).remove();
                    });
                } else {
                    swal("刪除資料失敗!", result.msg, "error");
                }
            }
        });
    });
}