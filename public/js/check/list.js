/**
 * Created by Spark on 2017/1/26.
 */
var check = new Vue({
    el: '#check',
    data: {
        checkList: {}
    },

    mounted: function () {
        this.getCheckList();
    },

    methods: {
        getCheckList: function () {
            $.ajax({
                type: "GET",
                url: url + "/defect/getCheckList",
                success: function (results) {
                    check.checkList = results;
                },
                error: function (e) {
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        edit: function (data) {
            document.location.href = url + '/nav/check.editCheck?checkID=' + data.id;
        },

        print: function (data) {
            document.location.href = url + '/nav/check.editCheck?checkID=' + data.id;
        },

        del: function (id) {
            swal({
                title: "刪除資料?",
                text: "此動作將會刪除資料!",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: '取消',
                confirmButtonClass: "btn-danger",
                confirmButtonText: "刪除",
                closeOnConfirm: false
            },
            function () {
                $.ajax({
                    type: "DELETE",
                    url: url + "/defect/deleteCheck",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {id: id},
                    success: function (result) {
                        if (result.success) {
                            swal({
                                title: "刪除資料成功!",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                            });
                            check.getCheckList();
                        } else {
                            swal("刪除資料失敗!", response.message, "error");
                        }
                    },
                    error: function (e) {
                        var response = jQuery.parseJSON(e.responseText);
                        swal("刪除資料失敗!", response.message, "error");
                        console.log(response.message);
                    }
                });
            });
        },

        tooltip: function (event) {
            $(event.target).tooltip('show');
            $('[data-toggle="tooltip"]').tooltip();
        },
    }
});