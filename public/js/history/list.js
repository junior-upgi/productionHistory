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
    $('#modalTitle').html('編輯產品履歷表');
    $('#btnSave').html('更新');
    $('#type').val('edit');
    $('#id').val(data['id']);
    $('#name').val('');
    $('#mk_no').val(data['mk_no']);
    $('#snm').val(data['NAME']);
    $('#cus_no').val(data['cus_no']);
    $('#searchCustomer').val(data['customerSName']);
    var machno = formatMachno(data['machno']);
    $('#machno').val(machno);
    $('#gauge').val(data['blow']);
    $('#blow').val(data['blow']);
    $('#other').val(data['other']);
    $('#weight').val(data['weight']);
    $('#actualWeight').val(data['actualWeight']);
    $('#skewPower').val(data['skewPower']);
    $('#termalShock').val(data['termalShock']);
    $('#speed').val(data['speed']);
    $('#efficiency').val(data['efficiency']);
    $('#defect').val(data['defect']);
    $('#addModal').modal('show');
}

function formatMachno(val) {
    if (val.substr(0, 1) == '1') {
        return val.substr(0, 3);
    } else {
        return val.substr(0, 2);
    }
}