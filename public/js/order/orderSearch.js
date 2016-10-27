function showimage(source) {
    $("#PicModal").find("#img_show").html("<image src='"+source+"' class='carousel-inner img-responsive img-rounded' />");
    $("#PicModal").modal('show');
}
function showimageAjax(no, item, prc, dep, pl) {
    $.ajax({
        url: url + '/getPic/' + no + '/' + item + '/' + prc + '/' + dep + '/' + pl,
        type: 'GET',
        dataType: 'text',
        error: function (xhr) {
            swal("取得資料失敗!", xhr.statusText, "error");
        },
        success: function (source) {
            $("#PicModal").find("#img_show").html("<image src='"+source+"' class='carousel-inner img-responsive img-rounded' />");
            $("#PicModal").modal('show');
        }
    });
}