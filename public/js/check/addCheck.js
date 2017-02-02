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
        language: 'zh-TW',
    });
    $("#addCheckForm").ajaxForm({
        url: url + '/defect/insertCheck',
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
                        closeOnConfirm: false
                    },
                    function () {
                        document.location.href = url + '/nav/check.editCheck?id=' + obj.id;
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
var addCheck = new Vue({
    el: '#addCheck',
    data: {
        template: [],
        schedule: [],
        customer: [],
    },
    computed: {

    },
    mounted: function () {
        this.getSchedule();
        this.getTemplateList();
    },

    methods: {
        getSchedule: function () {
            var urlParams = new URLSearchParams(window.location.search);
            var baseData = {
                'view': 'run',
                'id': urlParams.get('id'),
                'schedate': urlParams.get('schedate'),
                'snm': urlParams.get('snm'),
                'glassProdLineID': urlParams.get('glassProdLineID'),
                'orderQty': urlParams.get('orderQty'),
                'prd_no': urlParams.get('prd_no'),
            };
            this.schedule = baseData;
            this.getCustomer();
        },

        getCustomer: function () {
            $.ajax({
                type: "GET",
                url: url + "/defect/scheduleCustomer",
                data: this.schedule,
                success: function(results){
                    addCheck.customer = results;

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
                    addCheck.template = results;

                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },
    }
});
