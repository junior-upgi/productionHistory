function DoSave() {
    $("#SetCostForm").ajaxForm({
        url: url + '/Mobile/SaveCost',
        beforeSubmit: function () {
            $('#BtnEdit').button('loading');
            goLoading('資料處理中，請稍候!');
        },
        success: function (obj) {
            if (obj.success) {
                $('#EditModal').modal('hide');
                swal({
                    title: "更新資料成功!",
                    text: obj.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                });
                $.unblockUI();
            } else {
                swal("更新資料失敗!", obj.msg, "error");
                $('#BtnEdit').button('reset');
                $.unblockUI();
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnEdit').button('reset');
            $.unblockUI();
        }
    });
}