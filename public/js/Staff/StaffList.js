//呼叫編輯程序視窗
function EditShow(StaffID) {
    $.ajax({
        url: url + '/SysOption/GetStaffData/' + StaffID,
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function () {
            //swal("資料讀取中，請稍候!")
            $.blockUI({ 
                css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff'
                },
                message: '資料讀取中，請稍候!',
            }); 
        },
        error: function (xhr) {
            $.unblockUI();
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            $.unblockUI();
            if (result.success) {
                var SuList = result.SuList;
                var PrList = result.PrList;
                var SeList = result.SeList;
                InitSelect(result.NodeList);
                var StaffName = result.nodeName + '_' + result.name + ' ' + result.position;
                $('#EditStaffForm #ID').val(result.ID);
                $('#EditStaffForm #StaffName').html(StaffName);
                if (SuList.length > 0) {
                    $("#EditStaffForm #SuperivisorID").append($("<option></option>").attr("value", "").text("請選擇"));
                    for (i = 0; i < SuList.length; i++) {
                        $("#EditStaffForm #SuperivisorID").append($("<option></option>").attr("value", SuList[i].ID).text(SuList[i].name));
                    }
                    $("#EditStaffForm #SuNodeID option[value=" + SuList[0].nodeID + "]").attr('selected', true);
                    $("#EditStaffForm #SuperivisorID option[value=" + result.SuperivisorID + "]").attr('selected', true);
                }
                if (PrList.length > 0) {
                    $("#EditStaffForm #PrimaryDelegateID").append($("<option></option>").attr("value", "").text("請選擇"));
                    for (i = 0; i < PrList.length; i++) {
                        $("#EditStaffForm #PrimaryDelegateID").append($("<option></option>").attr("value", PrList[i].ID).text(PrList[i].name));
                    }
                    $("#EditStaffForm #PrNodeID option[value=" + PrList[0].nodeID + "]").attr('selected', true);
                    $("#EditStaffForm #PrimaryDelegateID option[value=" + result.PrimaryDelegateID + "]").attr('selected', true);
                }
                if (SeList.length > 0) {
                    $("#EditStaffForm #SecondaryDelegateID").append($("<option></option>").attr("value", "").text("請選擇"));
                    for (i = 0; i < SeList.length; i++) {
                        $("#EditStaffForm #SecondaryDelegateID").append($("<option></option>").attr("value", SeList[i].ID).text(SeList[i].name));
                    }
                    $("#EditStaffForm #SeNodeID option[value=" + SeList[0].nodeID + "]").attr('selected', true);
                    $("#EditStaffForm #SecondaryDelegateID option[value=" + result.SecondaryDelegateID + "]").attr('selected', true);
                }
                $('#BtnEdit').button('reset');
                $('#EditModal').modal('show');
            } else {
                swal("取得資料錯誤!", result.msg, "error");
            }
        }
    })
}
//初始化單位選單
function InitSelect(List) {
    $('select').each(function () {
        $(this).empty();
        if ($(this).hasClass("node")) {
            $(this).append($("<option></option>").attr("value", "").text("請選擇"));
            for (i = 0; i < List.length; i++) {
                $(this).append($("<option></option>").attr("value", List[i].nodeID).text(List[i].nodeName));
            }
        }
    });
}
//取得單位成員資料
function GetStaff(type) {
    var FormID = "#EditStaffForm";
    var NodeID = $("#" + type + "NodeID").find(":selected").val();
    var StaffID = "";
    switch (type) {
        case "Su" :
            StaffID = "SuperivisorID";
            break;
        case "Pr" :
            StaffID = "PrimaryDelegateID";
            break;
        case "Se":
            StaffID = "SecondaryDelegateID";
            break;
    }
    $.ajax({
        url: url + '/SysOption/GetStaffList/' + NodeID,
        type: 'GET',
        dataType: 'JSON',
        error: function (xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (result) {
            $(FormID + " #" + StaffID).remove();
            if (result.length > 0) {
                $(FormID + " #" + StaffID).append($("<option></option>").attr("value", "").text("請選擇"));
                for (i = 0; i < result.length; i++) {
                    $(FormID + " #" + StaffID).append($("<option></option>").attr("value", result[i].ID).text(result[i].name));
                }
            }
        }
    });
}
//更新員工資料
function DoUpdate() {
    $("#EditStaffForm").ajaxForm({
        url: url + '/SysOption/UpdateStaff',
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
                },function () {
                    document.location.href = url + '/SysOption/StaffList';
                });
            } else {
                swal("更新資料失敗!", obj.msg.errorInfo[2], "error");
                $('#BtnEdit').button('reset');
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $('#BtnEdit').button('reset');
        }
    });
}
function DataSyn()
{
    var Title = "資料同步？";
    var Message = "此動作會遠端更新資料到本系統!\n" 
                + "請在非作業時段進行同步!\n"
                + "請問您確定要開始進行資料同步嗎？";
    swal({
        title: Title,
        text: Message,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "取消",
        confirmButtonClass: "btn-warning",
        confirmButtonText: "確定",
        closeOnConfirm: true
    },
    function() {
        Syn();
    });
}
function Syn() {
    $.ajax({
        url: url + '/SysOption/MoveData',
        type: 'GET',
        dataType: 'JSON',
        beforeSend: function () {
            goLoading('更新資料中，請稍候...');
        },
        error: function (xhr) {
            swal("操作失敗!", xhr.statusText, "error");
            $.unblockUI();
        },
        success: function (result) {
            $.unblockUI();
            if (result.success) {
                swal({
                    title: result.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: true
                },
                function () {

                });
            } else {
                swal("更新資料失敗!", result.msg.errorInfo[2], "error");
                $.unblockUI();
            }
        }
    });
}