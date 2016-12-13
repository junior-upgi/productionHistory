$(document).ready(function () { 
    $(".date").datetimepicker({
        format: 'yyyy-mm-dd',
        //startDate: timInMs,
        startView: 2,
        minView: 2,
        maxView: 4, 
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        language: 'zh-TW',
    });

    $('#draw').fileinput({
        language: 'zh-TW',
        browseClass: "btn btn-success",
        browseLabel: "選擇檔案",
        browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
        removeClass: "btn btn-danger",
        removeLabel: "移除",
        removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
        fileActionSettings: {
            showZoom: false,
            zoomIcon: "",
            zoomClass: "",
            zoomTitle: "",
        }
    });

    $("#addForm").ajaxForm({
        url: url + '/QC/SaveQC',
        type: 'POST',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            $('#BtnSave').button('loading');
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
                    document.location.href = url + '/QC/QCList';
                });
            } else {
                swal("新增資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (obj) {
            swal("發生異常錯誤!", obj.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
});

function save() {
    $("#addForm").submit();
}