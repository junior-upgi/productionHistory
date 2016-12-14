function history(prd_no, glassProdLineID, schedate) {
    var data = {
        'prd_no': prd_no,
        'glassProdLineID': glassProdLineID,
        'schedate': schedate,
        'view': 'run'
    };
    $.ajax({
        url: url + '/History/GetSchedule',
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
    $('#modalTitle').html('新增產品履歷表');
    $('#btnSave').html('新增');
    $('#type').val('add');
    $('#prd_no').val(data['prd_no']);
    $('#schedate').val(data['schedate']);
    $("#glassProdLineID").attr("disabled", true);
    $('#glassProdLineID').val(data['glassProdLineID']);
    $('.cus').hide();
    $('.prd').hide();
    $('searchCustomer').removeAttr("required");
    $('searchCustomer').val('');
    $('cus_no').val('');
    $('#gauge').val('');
    $('#formingMethod').val('');
    $('#other').val('');
    $('#weight').val('');
    $('#actualWeight').val('');
    $('#stressLevel').val('');
    $('#thermalShock').val('');
    $('#speed').val('');
    $('#efficiency').val('');
    $('#defect').val('');
    $('#addModal').modal('show');
}

function testModel() {
    $('#modalTitle').html('新增試模履歷表');
    $('#btnSave').html('新增');
    $('#type').val('add');
    $('#prd_no').val('');
    $('#searchProd').val('');
    $('#schedate').val('');
    $('#cus_no').val('');
    $('.cus').show();
    $('.prd').show();
    $('#searchCustomer').attr("required", true);
    $('#searchCustomer').val('');
    $("#glassProdLineID").attr("disabled", false);
    $('#glassProdLineID').val('L1-1');
    $('#gauge').val('');
    $('#formingMethod').val('');
    $('#other').val('');
    $('#weight').val('');
    $('#actualWeight').val('');
    $('#stressLevel').val('');
    $('#thermalShock').val('');
    $('#speed').val('');
    $('#efficiency').val('');
    $('#defect').val('');
    $('#addModal').modal('show');
}