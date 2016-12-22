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
    $('#schedate').val(data['schedate']);
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