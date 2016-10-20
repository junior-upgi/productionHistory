function DoDelete(ProjectID) {
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
        function(){
            Delete(ProjectID);
        });
}

function Delete(ProjectID) {
    $.ajax({
        url: url + '/Project/Delete/' + ProjectID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("刪除資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
          if (result.success) {
                swal({
                    title: "刪除資料成功!",
                    text: result.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/Project/ProjectList';
                });
            } else {
                swal("刪除資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        }
    })
}