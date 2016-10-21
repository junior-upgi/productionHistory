function a() {
    var ID = $("#orderNumber").val();
    $("#orderSearchForm").ajaxForm({
        url: url + "/order/" + ID,
        beforeSubmit: function () {
            //$("#BtnEdit").button("loading");
            goLoading('資料處理中，請稍候!');
        },
        success: function (obj) {
            if (obj.success) {
                $.unblockUI();
            } else {
                alert("找不到訂單資料!");
                $.unblockUI();
            }
        },
        error: function (xhr) {
            swal("發生異常錯誤!", xhr.statusText, "error");
            $.unblockUI();
        }
    });
}