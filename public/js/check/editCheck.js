/**
 * Created by Spark on 2017/2/2.
 */
$(document).ready(function () {
    $(".time").datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        //startDate: timInMs,
        startView: 2,
        minView: 0,
        maxView: 4,
        autoclose: true,
        todayBtn: true,
        todayHighlight: true,
        language: 'zh-TW'
    });

    //noinspection JSJQueryEfficiency
    $("#editCheckForm").ajaxForm({
        url: url + '/defect/updateCheck',
        type: 'PUT',
        beforeSubmit: function () {
            $('#BtnSave').button('loading');
        },
        success: function (obj) {
            if (obj.success) {
                swal({
                        title: "更新資料成功!",
                        text: obj.msg,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "OK",
                        closeOnConfirm: true
                    },
                    function () {
                        $('#BtnSave').button('reset');
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

    //noinspection JSJQueryEfficiency
    $('#editCheckForm').on('hide.bs.collapse', function () {
        $('#showBtn').text('顯示檢查表資料');
        $('.info').show();
    });
    //noinspection JSJQueryEfficiency
    $("#editCheckForm").on('show.bs.collapse', function () {
        $('#showBtn').text('隱藏檢查表資料');
        $('.info').hide();
    });
});
var editCheck = new Vue({
    el: '#editCheck',
    data: {
        template: [],
        checkData: [],
        decoration: [],
        templateID: '',
    },
    computed: {
        templateID: function () {
            return this.checkData.templateID;
        }
    },
    mounted: function () {
        this.getCheck();
        this.getTemplateList();
    },

    methods: {
        getCheck: function () {
            //noinspection JSUnresolvedFunction
            var urlParams = new URLSearchParams(window.location.search);
            $.ajax({
                type: "GET",
                url: url + "/defect/getCheck",
                data: {'id': urlParams.get('id')},
                success: function(results){
                    editCheck.checkData = results;
                    editCheck.decoration = editCheck.setDecoration(results['decoration']);
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        getTemplateList: function () {
            $.ajax({
                type: "GET",
                url: url + "/defect/getTemplateList",
                success: function(results){
                    editCheck.template = results;
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        setDecoration: function (decoration) {
            return decoration.split(',');
        }
    }
});