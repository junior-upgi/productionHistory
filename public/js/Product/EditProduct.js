$(document).ready(function () {
    var timeInMs = new Date();
    timInMs = timeInMs.getFullYear() + "-" + (timeInMs.getMonth() + 1) + "-" + timeInMs.getDate();
    $(".date").datetimepicker({
        format: 'yyyy-mm-dd',
        //startDate: timInMs,
        minView: 2,
        maxView: 4,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        language: 'zh-TW'
    });
});
function DoUpdate() {
    var ProjectID = $("#ProjectID").val();
    $("#EditProductForm").ajaxForm({
        url: url + '/Product/UpdateProduct',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            //$.blockUI({ message: '<div>送出資料中請稍候...</div>' });
            $('#BtnSave').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                swal({
                    title: "更新資料成功!",
                    text: obj.msg,
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
                swal("更新資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
}