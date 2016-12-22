$(function () {
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

    $('#taskProduct').bsSuggest('init', {
        url: url + '/History/GetGlass',
        getDataMethod: 'firstByUrl',
        effectiveFields: ['snm'],
        searchFields: ['snm'],
        effectiveFieldsAlias:{snm: '產品編號'},
        ignorecase: true,
        showHeader: true,
        showBtn: false,
        delayUntilKeyup: false, //获取数据的方式为 firstByUrl 时，延迟到有输入/获取到焦点时才请求数据
        idField: 'prd_no',
        keyField: 'snm'
    }).on('onDataRequestSuccess', function (e, result) {
        //console.log('onDataRequestSuccess: ', result);
    }).on('onSetSelectValue', function (e, keyword, data) {
        //console.log('onSetSelectValue: ', keyword, data);
        $('#taskModal #PRD_NO').val(keyword['id']);
    }).on('onUnsetSelectValue', function () {
        //console.log('onUnsetSelectValue');
        $('#taskModal #PRD_NO').val('');
    });

    $("#taskForm").ajaxForm({
        url: url + '/Service/SaveTask',
        type: 'POST',
        beforeSubmit: function () {
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
                    closeOnConfirm: true
                }, function () {
                    $('#taskModal').modal('hide');
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