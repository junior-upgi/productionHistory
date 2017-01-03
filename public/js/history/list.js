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
});

function edit(id) {
    var data = {'id': id};
    $.ajax({
        url: url + '/History/GetHistory',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        error: function(xhr) {
            swal("讀取資料失敗!", xhr.statusText, "error");
        },
        success: function(result) {
            if (result.success) {
                setHistoryData(result.data);
            } else {
                swal("讀取資料失敗!", '找不到資料', "error");
            }
        }
    });
}

function setHistoryData(data) {
    $('#modalTitle').html('編輯產品履歷表');
    $('#btnSave').html('更新');
    $('#type').val('edit');
    $('#id').val(data['id']);
    $('#tbmknoID').val(data['tbmknoID']);
    $('#prd_no').val(data['prd_no']);
    var date = new Date(data['fillOutDate']);
    var formatDate = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
    $('#fillOutDate').val(formatDate);
    var sDate = new Date(data['schedate']);
    var formatsDate = sDate.getFullYear() + '-' + (sDate.getMonth() + 1) + '-' + sDate.getDate();
    $('#schedate').val(formatsDate);
    $('#sampling').val(data['sampling']);
    $('#glassProdLineID').val(data['glassProdLineID']);
    $('#searchProd').val(data['snm']);
    $('#orderQty').val(data['orderQty']);
    if (data['sampling'] == 0) {
        $("#glassProdLineID").attr("disabled", true);
        $('#orderQty').attr("disabled", true);
        $('.cus').hide();
        //$('.prd').hide();
        $('#searchProd').attr("disabled", true);
        $('#searchCustomer').removeAttr("required");
        $('#searchCustomer').val('');
    } else {
        $("#glassProdLineID").attr("disabled", false);
        $('#orderQty').attr("disabled", false);
        $('.prd').attr("disabled", false);
        $('.cus').show();
        $('#searchProd').attr("disabled", false);
        $('#searchCustomer').attr("required", true);
        $('#searchCustomer').val(data['customerSName']);
    }
    $('#cus_no').val(data['cus_no']);
    $('#gauge').val(data['gauge']);
    $('#formingMethod').val(data['formingMethod']);
    $('#other').val(data['other']);
    $('#weight').val(data['weight']);
    $('#actualWeight').val(data['actualWeight']);
    $('#stressLevel').val(data['stressLevel']);
    $('#thermalShock').val(data['thermalShock']);
    $('#speed').val(data['speed']);
    $('#efficiency').val(data['efficiency']);
    $('#defect').val(data['defect']);
    $('#addModal').modal('show');
}

function showTask() {
    $('#taskModal').modal('show');
}

function doDel(id) {
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
            del(id);
        });
}

function del(id) {
    var data = {
        'table': 'history',
        'id': id
    };
    $.ajax({
        url: url + '/History/DeleteHistory',
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        error: function(xhr) {
            swal("刪除資料失敗!", xhr.statusText, "error");
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
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/History/HistoryList';
                });
            } else {
                swal("刪除資料失敗!", '找不到資料', "error");
            }
        }
    });
}