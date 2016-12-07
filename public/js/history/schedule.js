function history(id) {
    var data = {'id': id};
    $.ajax({
        url: url + '/History/GetSchedule',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: data,
        error: function(xhr) {
            swal("取讀資料失敗!", xhr.statusText, "error");
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
    $('#mk_no').val(data['mk_no']);
    $('#snm').val(data['NAME']);
    $('#cus_no').val(data['cus_no']);
    $('#searchCustomer').val(data['customerSName']);
    var machno = formatMachno(data['machno']);
    $('#machno').val(machno);
    $('#gauge').val('');
    $('#blow').val('');
    $('#other').val('');
    $('#weight').val('');
    $('#actualWeight').val('');
    $('#skewPower').val('');
    $('#termalShock').val('');
    $('#speed').val('');
    $('#efficiency').val('');
    $('#defect').val('');
    $('#addModal').modal('show');
}

function formatMachno(val) {
    if (val.substr(0, 1) == '1') {
        return val.substr(0, 3);
    } else {
        return val.substr(0, 2);
    }
}

function testModel() {
    $('#modalTitle').html('新增試模履歷表');
    $('#btnSave').html('新增');
    $('#type').val('add');
    $('#mk_no').val('--');
    $('#snm').val('');
    $('#cus_no').val('');
    $('#searchCustomer').val('');
    $('#machno').val('1-1');
    $('#gauge').val('');
    $('#blow').val('');
    $('#other').val('');
    $('#weight').val('');
    $('#actualWeight').val('');
    $('#skewPower').val('');
    $('#termalShock').val('');
    $('#speed').val('');
    $('#efficiency').val('');
    $('#defect').val('');
    $('#addModal').modal('show');
}