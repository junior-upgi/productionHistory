function duty(prd_no, glassProdLineID, schedate) {
    var data = {
        'prd_no': prd_no,
        'glassProdLineID': glassProdLineID,
        'schedate': schedate,
        'view': 'allGlass'
    };
    $.ajax({
        url: url + '/Duty/GetSchedule',
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
                setDutyData(result.data);
            } else {
                swal("讀取資料失敗!", '找不到資料', "error");
            }
        }
    });
}

function setDutyData(data) {
    $('#modalTitle').html('新增生產值班表');
    $('#btnSave').html('新增');
    $('#type').val('add');
    $('#id').val(data['id']);
    $('#name').val('');
    $('#prd_no').val(data['prd_no']);
    $('#schedate').val(data['schedate']);
    $('#glassProdLineID').val(data['glassProdLineID']);
    $('#snm').val(data['snm']);
    $('#quantity').val('');
    $('#pack').val('');
    $('#efficiency').val('');
    $('#annealGrade').val('');
    $('#startShutdown').val('');
    $('#endShutdown').val('');
    $('#jobChange').val('');
    $('#speedChange').val('');
    $('#improve').val('');
    $('#addModal').modal('show');
}