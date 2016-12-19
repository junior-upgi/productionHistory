$(function () {
    $("#historyDetail").sortable({
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
    $('.ch').change( function () {
        if ($(this).prop('checked')) {
            var a = $(this).val();
            $('#hi_' + a).show();
        } else {
            $('#hi_' + $(this).val()).hide();
        }
    });
});