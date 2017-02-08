/**
 * Created by Spark on 2017/2/3.
 */

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

    /**
     * 新增生產缺點
     */
    $("#addCheckDefectForm").ajaxForm({
        url: url + '/defect/insertProductionDefect',
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
                    },
                    function () {
                        $('#addProductionDefectModal').modal('hide');
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

    /**
     * 更新生產缺點
     */
    $("#editCheckDefectForm").ajaxForm({
        url: url + '/defect/updateProductionDefect',
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
                        $('#editProductionDefectModal').modal('hide');
                    });
            } else {
                swal("更新資料失敗!", obj.msg, "error");
                $('#BtnSave').button('reset');
            }
        },
        error: function (obj) {
            swal("發生異常錯誤!", obj.statusText, "error");
            $('#BtnSave').button('reset');
        }
    });
});

/**
 * collapse 變動後，重設modal背景高度
 *
 * @param obj
 */
function resetModal (obj) {
    var modal = $(obj).closest('.modal');
    var bodyHeight = document.body.scrollHeight;
    var modalHeight = $(modal).children('.modal-dialog').height() + 60;
    if (modalHeight >= bodyHeight) {
        $(modal).children('.modal-backdrop').height(modalHeight);
    } else {
        $(modal).children('.modal-backdrop').height(bodyHeight);
    }
}

var productionInfo = new Vue({
    el: '#productionInfo',
    computed: {

    },
    data: {
        checkID: null,
        editData: [],
        editDefect: [],
        production: null,
        defects: [],
        items: [],
        productionData: [],
        defectList: [],
        itemsCount: null,
        avgDefect: null,
        computedInfo: null,
        classTypeOption: [
            { text: '早班', value: 1},
            { text: '中班', value: 2},
            { text: '晚班', value: 3}
        ]
    },

    computed: {
        /**
         * 計算每個缺點項目之缺點數
         *
         * @returns {Array}
         */
        itemsCount: function () {
            var array = [];
            for (var i = 0; i < this.items.length; i++) {
                var count = 0;
                for (var x = 0; x < this.defects.length; x++) {
                    if (this.defects[x].itemID == this.items[i].itemID) {
                        count++;
                    }
                }
                array[this.items[i].itemID] = count;
            }
            return array;
        },

        /**
         * 計算缺點平均
         *
         * @returns {Array}
         */
        avgDefect: function () {
            var array = [];
            for (var i = 0; i < this.defects.length; i++) {
                var count = 0;
                var total = 0;
                for (var x = 0; x < this.defectList.length; x++) {
                    if (this.defects[i].defectID == this.defectList[x].defectID) {
                        total += this.defectList[x].value;
                        count++;
                    }
                }
                var avg = parseFloat((total / count).toFixed(2));
                array.push(avg);
            }
            return array;
        },
        /**
         * 計算生產資訊
         *
         * @returns {{actualQuantity: number, minute: number, checkRate: number, actualMinWeight: number, actualMaxWeight: number}}
         */
        computedInfo: function () {
            var array = {
                actualQuantity: 0,
                minute: 0,
                checkRate: 0,
                actualMinWeight: 9999999999,
                actualMaxWeight: 0
            };
            for (var i = 0; i < this.productionData.length; i++) {
                array['actualQuantity'] += this.productionData[i].actualQuantity;
                array['minute'] += this.productionData[i].minute;
                var checkRate = parseFloat(array['checkRate']) + parseFloat(this.productionData[i].checkRate);
                array['checkRate'] = parseFloat((array['checkRate'] + parseFloat(this.productionData[i].checkRate)).toFixed(2));
                array['actualMinWeight'] = parseFloat(Math.min(array['actualMinWeight'], this.productionData[i].actualMinWeight)).toFixed(2);
                array['actualMaxWeight'] = parseFloat(Math.max(array['actualMaxWeight'], this.productionData[i].actualMaxWeight)).toFixed(2);
            }
            array['checkRate'] = parseFloat((array['checkRate'] / this.productionData.length).toFixed(2));
            return array;
        }
    },

    mounted: function () {
        this.getDefectList();
    },

    updated: function () {
        this.setCollapse();
    },

    methods: {
        /**
         * 取得生產缺點資訊
         */
        getDefectList: function () {
            var urlParams = new URLSearchParams(window.location.search);
            this.checkID = urlParams.get('checkID');
            //noinspection JSUnresolvedFunction
            $.ajax({
                type: "GET",
                url: url + "/defect/getProductionDefectList",
                data: {
                    'checkID': this.checkID
                },
                success: function(results){
                    productionInfo.productionData = results.productionData;
                    productionInfo.defectList = results.defectList;
                    productionInfo.items = results.item;
                    productionInfo.defects = results.defect;
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        addProductionDefectShow: function () {
            $('#addProductionDefectModal').modal({backdrop: 'static'}, 'show');
        },

        editProductionDefectShow: function (id) {
            this.setEditData(id);
            $('#editProductionDefectModal').modal({backdrop: 'static'}, 'show');
        },

        /**
         * 設定編輯資料
         *
         * @param id productionDataID
         */
        setEditData: function (id) {
            var p = this.productionData;
            var d = this.defectList;
            for (var i = 0; i < p.length; i++) {
                if (p[i].id == id) {
                    this.editData = p[i];
                }
            }
            var defectList = [];
            for (var i = 0; i < d.length; i++) {
                if (d[i].productionDataID == id) {
                    this.editDefect[d[i].defectID] = d[i].value;
                }
            }
            console.log(this.editData);
            console.log(this.editDefect);
        },

        addSpotCheckDefectShow: function () {
            $('#addProductionDefectModal').modal({backdrop: 'static'}, 'show');
        },

        editSpotCheckDefectShow: function () {
            $('#editProductionDefectModal').modal({backdrop: 'static'}, 'show');
        },

        setCollapse: function () {
            $('.panel-collapse').on('shown.bs.collapse', function () {
                resetModal(this);
            });
            $('.panel-collapse').on('hidden.bs.collapse', function () {
                resetModal(this);
            });
        },

        getDate: function (datetime) {
            var date = new Date(datetime);
            return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' +  date.getDate();
        },

        getClassName: function (classType) {
            var className = {1: '早班', 2: '中班', 3: '晚班'};
            return className[classType];
        }
    }
});
