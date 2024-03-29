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
        url: url + '/Duty/GetDuty',
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
                setDutyData(result.data);
            } else {
                swal("讀取資料失敗!", '找不到資料', "error");
            }
        }
    });
}


function setDutyData(data) {
    $('#modalTitle').html('編輯生產值班表');
    $('#btnSave').html('更新');
    $('#type').val('edit');
    $('#id').val(data['id']);
    $('#name').val('');
    $('#prd_no').val(data['prd_no']);
    $('#schedate').val(data['schedate']);
    $('#glassProdLineID').val(data['glassProdLineID']);
    $('#glassProdLineID').attr('disabled', true);
    $('#staffID').val(data['staffID']);
    $('#searchStaff').val(data['staffName']);
    $('#snm').val(data['snm']);
    $('#quantity').val(data['quantity']);
    $('#pack').val(data['pack']);
    $('#efficiency').val(data['efficiency']);
    $('#annealGrade').val(data['annealGrade']);
    $('#startShutdown').val(data['startShutdown']);
    $('#endShutdown').val(data['endShutdown']);
    $('#jobChange').val(data['jobChange']);
    $('#speedChange').val(data['speedChange']);
    $('#improve').val(data['improve']);
    $('#addModal').modal('show');
}