function DoDelete(ProjectID, ProductID) {
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
            Delete(ProjectID, ProductID);
        });
}

function Delete(ProjectID, ProductID) {
    $.ajax({
        url: url + '/Product/Delete/' + ProductID,
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
                    document.location.href = url + '/Product/ProductList/' + ProjectID;
                });
            } else {
                swal("刪除資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        }
    })
}
function showimage(source) {
    $("#PicModal").find("#img_show").html("<image src='"+source+"' class='carousel-inner img-responsive img-rounded' />");
    $("#PicModal").modal('show');
}