//呼叫編輯程序視窗
function EditShow(SideID) {
    $.ajax({
        url: url + '/SysOption/GetSideData/' + SideID,
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function () {
            //swal("資料讀取中，請稍候!")
            goLoading('資料讀取中，請稍候!');
        },
        error: function (xhr) {
            $.unblockUI();
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            $.unblockUI();
            if (result.success) {
                var Data = result.SideData;
                $('#EditSideForm #sideID').val(Data.ID);
                $('#EditSideForm #systemID').val(Data.system);
                $('#EditSideForm #sideName').val(Data.sideName);
                $('#EditSideForm #parentName').val(!(Data['get_parent'] == null) ? Data['get_parent']['sideName']:'');
                $('#EditSideForm #parentID').val(Data.parentID);
                $('#EditSideForm #route').val(Data.route);
                $('#EditSideForm #yield').val(Data.yield);
                $('#BtnEdit').button('reset');
                $('#EditSideModal').modal('show');
            } else {
                swal("取得資料錯誤!", result.msg, "error");
            }
        }
    })
}
function GetParentList (Type) {
    var SideID = $('#' + Type + 'SideForm #sideID').val();
    var SystemID = $('#' + Type + 'SideForm #systemID').val();
    $.ajax({
        url: url + '/SysOption/GetParentList/' + SystemID + '/' + SideID,
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function () {
            goLoading('資料讀取中，請稍候!');
        },
        error: function (xhr) {
            $.unblockUI();
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            $("#ParentListTable").empty();
            for (i=0; i < result.length; i++) {
                $("#ParentListTable").append(
                "<tr>"+
                    "<td>" + 
                        "<input type='button' class='btn btn-default btn-sm' value='選擇' onclick=\"SetParent('" + result[i].ID + "', '" + result[i].sideName + "', '" + Type + "')\">" + 
                    "</td>" +
                    //"<td>" + ((result[i]['get_system'] == null) ? result[i]['get_system']['systemName'] : '') + "</td>" +
                    "<td>" + result[i].sideName + "</td>" +
                    "<td>" + result[i].route + "</td>" +
                "</tr>");
            }
            $('#ParentListModal').modal('show');
            $.unblockUI();
        }
    });
}
function SetParent(SideID, SideName, Type) {
    $('#' + Type + 'SideForm #parentID').val(SideID);
    $('#' + Type + 'SideForm #parentName').val(SideName);
    $('#ParentListModal').modal('hide');
}
function AddShow() {
    $('#AddSideForm #system').val('');
    $('#AddProcessForm #sideName').val('');
    $("#AddProcessForm #parentID").val('');
    $("#AddProcessForm #route").val('');
    $('#AddSideModal').modal('show');
}
function SetSystemChange() {
    $('#SetSystemForm').submit();
}
function DoInsert() {
    $("#AddSideForm").ajaxForm({
        url: url + '/SysOption/InsertSide',
        beforeSubmit: function () {
            $('#BtnAdd').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                $('#AddSideModal').modal('hide');
                swal({
                    title: "新增資料成功!",
                    text: obj.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                }, function () {
                    $('#SetSystemForm').submit();
                });
            } else {
                swal("新增資料失敗!", obj.msg.errorInfo[2], "error");
                $('#BtnAdd').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnAdd').button('reset');
        }
    });
}
function DoUpdate() {
    $("#EditSideForm").ajaxForm({
        url: url + '/SysOption/UpdateSide',
        beforeSubmit: function () {
            $('#BtnEdit').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                $('#EditSideModal').modal('hide');
                swal({
                    title: "編輯資料成功!",
                    text: obj.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                }, function () {
                    $('#SetSystemForm').submit();
                });
            } else {
                swal("編輯資料失敗!", obj.msg.errorInfo[2], "error");
                $('#BtnEdit').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnEdit').button('reset');
        }
    });
}

function DoDelete(ProductID, ProcessID) {
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
            Delete(ProductID, ProcessID);
        });
}

function Delete(SideID) {
    $.ajax({
        url: url + '/SysOption/DeleteSide/' + SideID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("刪除資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
          if (result.success) {
                swal({
                    title: "刪資料成功!",
                    text: result.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                },
                function () {
                    $('#SetSystemForm').submit();
                });
            } else {
                swal("刪除資料失敗!", result.msg.errorInfo[2], "error");
                $('#BtnSave').button('reset');
            }
        }
    })
}