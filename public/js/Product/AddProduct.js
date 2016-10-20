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
function DoInsert() {
    var ProjectID = $("#ProjectID").val();
    $("#AddProductForm").ajaxForm({
        url: url + '/Product/InsertProduct',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            $('#BtnSave').button('loading');
            //$.blockUI({ message: '<div>送出資料中請稍候...</div>' });
        },
        success: function (obj) {
            if (obj.success) {
                swal({
                    title: "新增資料成功!",
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
                swal("新增資料失敗!!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!!", xhr.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
}