$(function () {
    $("#sort").sortable({
        helper: fixWidthHelper,
        update: function(event, ui) {
            
        }
    }).disableSelection();
    //防止表格托拽後縮小修正程序
    function fixWidthHelper(e, ui) {
        ui.children().each(function () {
            $(this).width($(this).width());
        });
        return ui;
    };
});

function search() {
    var snm = $('#searchSnm').val();
    if (snm != '') {
        var data = {
            'snm': snm
        };
        $.ajax({
            url: url + '/Report/GetHistory',
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
                    initData(result);
                } else {
                    swal('異常錯誤', result.msg, 'error');
                }
            }
        });
    }
}

function initData(result) {
    if (result.historyList.length < 1) {
        viewReset();
        swal('注意', '此產品沒有生產履歷記錄', 'warning');
    } else {
        setHistory(result.historyList);
        setBasicData(result.historyList);
    }
}

function viewReset() {
    $('#history_table').hide();
    $('#history_tbody').empty();
}

function setHistory(list) {
    $('#history_table').show();
    $('#history_tbody').empty();
    for (i=0; i < list.length; i++) {
        var id = list[i]['id'];
        var date = list[i]['productionDate'].substr(0, 10);
        var line = list[i]['glassProdLineID'];
        var efficiency = list[i]['efficiency'];
        $('#history_tbody').append(
            '<tr>' + 
                '<td><input type="checkbox" class="historyCheckbox" value="' + id + '"></td>' +
                '<td>' + date + '</td>' + 
                '<td>' + line + '</td>' + 
                '<td>' + efficiency + '%</td>' +
            '</tr>'
        );
    }
}

function setBasicData(list) {
    var snm = list[0]['snm'];
    var gauge = list[0]['gauge'];
    $('#basicData').show();
    $('#basicDataInfo').html(
        '<span class="" style="margin-right: 10px;">瓶號：' + snm + '</span>' +
        '<span class="" style="margin-right: 10px;">量規：' + gauge + '</span>'
    );  
}

function setBasicData222(list) {
    var snm = list[0]['snm'];
    var gauge = list[0]['gauge'];
    $('#basicData').show();
    $('#basicData_tbody').append(
        '<tr><td>' +  + '</td></tr>' +

        '<span class="" style="margin-right: 10px;">量規：' + gauge + '</span>'
    );  
}