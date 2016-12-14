function qc(prd_no, glassProdLineID, schedate) {
    var data = {
        'prd_no': prd_no,
        'glassProdLineID': glassProdLineID,
        'schedate': schedate,
        'view': 'plan'
    };
    $.ajax({
        url: url + '/QC/GetSchedule',
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
                setQCData(result.data);
            } else {
                swal("讀取資料失敗!", '找不到資料', "error");
            }
        }
    });
}

function setQCData(data) {
    $('#modalTitle').html('新增品管管制表');
    $('#btnSave').html('新增');
    $('#type').val('add');
    $('#prd_no').val(data['prd_no']);
    $('#snm').val(data['snm']);
    $('#schedate').val(data['schedate']);
    $('#orderQty').val(data['orderQty']);
    $('#glassProdLineID').val(data['glassProdLineID']);
    $('#customer').val(data['customer']);
    $('#finishType').val(data['finishType']);
    $('#finishDiaGo').val(data['finishDiaGo']);
    $('#finishDiaNoGo').val(data['finishDiaNoGo']);
    $('#finishInnerDiaGo').val(data['finishInnerDiaGo']);
    $('#finishInnerDiaNoGo').val(data['finishInnerDiaNoGo']);
    $('#threadDiaGo').val(data['threadDiaGo']);
    $('#threadDiaNoGo').val(data['threadDiaNoGo']);
    $('#lipDiaGo').val(data['lipDiaGo']);
    $('#lipDiaNoGo').val(data['lipDiaNoGo']);
    $('#saftyRingGo').val(data['saftyRingGo']);
    $('#saftyRingNoGo').val(data['saftyRingNoGo']);
    $('#bodyDiaGo').val(data['bodyDiaGo']);
    $('#bodyDiaNoGo').val(data['bodyDiaNoGo']);
    $('#hSettingMin').val(data['hSettingMin']);
    $('#hSettingMax').val(data['hSettingMax']);
    $('#heightGo').val(data['heightGo']);
    $('#heightNoGo').val(data['heightNoGo']);
    $('#verticality').val(data['verticality']);
    $('#wavyFinish').val(data['wavyFinish']);
    $('#slantedFinish').val(data['slantedFinish']);
    $('#weight').val(data['weight']);
    $('#brimCapueity').val(data['brimCapueity']);
    $('#requirement').val(data['requirement']);
    $('#draw').val(data['draw']);
    $('#customer').val(data['customer']);
    $('#qualityLevel').val(data['qualityLevel']);
    $('#country').val(data['country']);
    $('#decoration').val(data['decoration']);
    $('#note').val(data['note']);
    $('#recentProdDefectList').val(data['recentProdDefectList']);
    $('#packRate').val(data['packRate']);
    $('#addModal').modal('show');
}