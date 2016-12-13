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
    
    $('#searchCustomer').bsSuggest('init', {
        url: url + '/History/GetCustomer',
        getDataMethod: 'firstByUrl',
        effectiveFields: ['name'],
        searchFields: ['name', 'sname'],
        effectiveFieldsAlias:{name: '顧客名稱'},
        ignorecase: true,
        showHeader: true,
        showBtn: false,
        delayUntilKeyup: false, //获取数据的方式为 firstByUrl 时，延迟到有输入/获取到焦点时才请求数据
        idField: 'ID',
        keyField: 'sname'
    }).on('onDataRequestSuccess', function (e, result) {
        //console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        //console.log('onSetSelectValue: ', keyword, data);
        $('#cus_no').val(keyword['id']);
    }).on('onUnsetSelectValue', function () {
        //console.log('onUnsetSelectValue');
        $('#cus_no').val('');
    });

    $("#addForm").ajaxForm({
        url: url + '/History/SaveHistory',
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
                    document.location.href = url + '/History/HistoryList';
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
    $('#glassProdLineID').attr('disabled', false);
    $("#addForm").submit();
}