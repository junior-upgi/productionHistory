function duty(id) {
    var data = {'id': id};
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
    $('#mk_no').val(data['mk_no']);
    $('#class').val(data['class']);
    $('#staffID').val(data['staffID']);
    $('#searchStaff').val(data['staffName']);
    var machno = formatMachno(data['machno']);
    $('#machno').val(machno);
    $('#snm').val(data['NAME']);
    $('#quantity').val(data['quantity']);
    $('#piece').val(data['piece']);
    $('#efficiency').val(data['efficiency']);
    $('#anneal').val(data['anneal']);
    $('#startShutdown').val(data['startShutdown']);
    $('#endShutdown').val(data['endShutdown']);
    $('#changeModel').val(data['changeModel']);
    $('#changeSpeed').val(data['changespeed']);
    $('#improve').val(data['improve']);
    $('#suggest').val(data['suggest']);
    $('#addModal').modal('show');
}

function formatMachno(val) {
    if (val.substr(0, 1) == '1') {
        return val.substr(0, 3);
    } else {
        return val.substr(0, 2);
    }
}
function testModelDuty () {
    $('#modalTitle').html('新增生產值班表');
    $('#btnSave').html('新增');
    $('#type').val('add');
    $('#id').val('');
    $('#name').val('');
    $('#').val('');
    $('#addModal').modal('show');
}