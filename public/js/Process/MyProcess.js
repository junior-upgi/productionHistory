function showimage(source) {
    $("#PicModal").find("#img_show").html("<image src='"+source+"' class='carousel-inner img-responsive img-rounded' />");
    $("#PicModal").modal('show');
}
//呼叫編輯程序視窗
function EditShow(ID) {
    $.ajax({
        url: url + '/Process/GetProcessData/' + ID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            if (result.success) {
                var StaffList = result.StaffList;
                $('#EditProcessForm #ProcessID').val(result.ID);
                $('#EditProcessForm #ProcessNumber').val(result.ProcessNumber);
                $('#EditProcessForm #ProcessName').val(result.ProcessName);
                $("#EditProcessForm #PhaseID option[value=" + result.PhaseID + "]").attr('selected', true);
                $('#EditProcessForm #ProcessStartDate').val(result.ProcessStartDate);
                $('#EditProcessForm #TimeCost').val(result.TimeCost);
                $("#EditProcessForm #NodeID option[value=" + result.NodeID + "]").attr('selected', true);
                if (StaffList.length > 0) {
                    $("#EditProcessForm #StaffID").empty();
                    $("#EditProcessForm #StaffID").append($("<option></option>").attr("value", "").text("請選擇"));
                    for (i = 0; i < StaffList.length; i++) {
                        $("#EditProcessForm #StaffID").append($("<option></option>").attr("value", StaffList[i].ID).text(StaffList[i].name));
                    }
                    $("#EditProcessForm #StaffID option[value=" + result.StaffID + "]").attr('selected', true);
                }
                if (result.processImg != null) {
                    var img = "<img src='" + result.processImg + "' class='kv-preview-data file-preview-image' style='width:auto;height:160px;'>";
                    $('#EditProcessForm #fileSet').val('true');
                } else {
                    var img = null;
                }
                $("#editImgDiv").empty();
                $("#editImgDiv").html("<input id='editImg' name='img' type='file' class='file-loading' data-show-upload='false' accept='image/*'>");
                $("#editImg").fileinput({
                    language: 'zh-TW',
                    previewFileType: "image",
                    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
                    previewClass: "bg-warning",
                    browseClass: "btn btn-success",
                    browseLabel: "選擇圖片",
                    browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
                    removeClass: "btn btn-danger",
                    removeLabel: "移除",
                    removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
                    fileActionSettings: {
                        showZoom: false,
                        showDrag: false,
                    },
                    initialPreview: 
                        [img]
                });

                $("#editPreview").attr("src", img);
                $('#BtnEdit').button('reset');
                $('#EditModal').modal('show');
            } else {
                swal("取得資料錯誤!", result.msg, "error");
            }
        }
    })
}
function DoUpdate(ProcessID) {
    var ProductID = $('#ProductID').val();
    var ProcessID = $('#ProcessID').val();
    $("#EditProcessForm").ajaxForm({
        url: url + '/Process/UpdateProcess',
        beforeSubmit: function () {
            $('#BtnEdit').button('loading');
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
                },
                function () {
                    document.location.href = url + '/Process/MyProcess';
                });
            } else {
                swal("更新資料失敗!", obj.msg, "error");
                $('#BtnEdit').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnEdit').button('reset');
        }
    });
}
function Complete($ProcessID) {
    var ProductID = $('#ProductID').val();
    $.ajax({
        url: url + '/Process/ProcessComplete/' + $ProcessID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("更新資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            if (result.success) {
                swal({
                    title: "更新資料成功!",
                    text: result.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/Process/MyProcess';
                });
            } else {
                swal("更新資料失敗!", obj.msg, "error");
            }
        }
    });
}