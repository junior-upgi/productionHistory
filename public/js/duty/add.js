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

    $(".time").datetimepicker({
        format: 'hh:ii',
        //startDate: timInMs,
        startView: 1,
        minView: 0,
        maxView: 1,
        minuteStep: 5,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        language: 'zh-TW',
    });

    $('#searchStaff').bsSuggest('init', {
        url: url + '/Duty/GetStaff',
        //url: url + '/js/data.json',
        effectiveFields: ['ID', 'nodeName', 'name'],
        searchFields: ['ID', 'nodeName', 'name'],
        effectiveFieldsAlias:{mobileSystemAccount: '員工編號', nodeName: '單位', name: '姓名'},
        ignorecase: true,
        showHeader: true,
        showBtn: false,
        delayUntilKeyup: true, //获取数据的方式为 firstByUrl 时，延迟到有输入/获取到焦点时才请求数据
        idField: 'ID',
        keyField: 'name'
    }).on('onDataRequestSuccess', function (e, result) {
        //console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        //console.log('onSetSelectValue: ', keyword, data);
        $('#staffID').val(keyword['id']);
    }).on('onUnsetSelectValue', function () {
        //console.log('onUnsetSelectValue');
        $('#staffID').val('');
    });
    
    $("#addForm").ajaxForm({
        url: url + '/Duty/SaveDuty',
        type: 'POST',
        beforeSubmit: function () {
            //$('#BtnSave').attr('disabled', 'disabled');
            $('#BtnSave').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                swal({
                    title: "新增資料成功!",
                    text: obj.msg,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: "btn-success",
                    confirmButtonText: "OK",
                    closeOnConfirm: false
                },
                function () {
                    document.location.href = url + '/Duty/DutyList';
                });
            } else {
                swal("新增資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (obj) {
            swal("發生異常錯誤!", obj.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
});

function save() {
    if ($('#staffID').val() == '') {
        $('#searchStaff').val('');
        alert('請選擇正確的機台人員');
    } else {
        $('#glassProdLineID').attr('disabled', false);
        $("#addForm").submit();
    }
}