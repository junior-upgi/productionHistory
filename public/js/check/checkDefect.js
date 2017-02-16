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
                        productionInfo.getDefectList();
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
                        productionInfo.getDefectList();
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

    $("#addSpotCheckDefectForm").ajaxForm({
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
                        productionInfo.getDefectList();
                        $('#addSpotCheckDefectModal').modal('hide');
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
     * 更新抽驗缺點
     */
    $("#editSpotCheckDefectForm").ajaxForm({
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
                        productionInfo.getDefectList();
                        $('#editSpotCheckDefectModal').modal('hide');
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
    data: {
        checkID: null,
        editData: [],
        editDefect: [],
        production: null,
        defects: [],
        items: [],
        spotCheckItems: [],
        spotCheckDefects: [],
        productionData: [],
        defectList: [],
        itemsCount: null,
        spotCheckItemsCount: null,
        avgDefect: null,
        avgSpotCheckDefect: null,
        computedInfo: null,
        classTypeOption: [
            { text: '早班', value: 1},
            { text: '中班', value: 2},
            { text: '晚班', value: 3}
        ],
        productionDataCount: 0,
        spotCheckCount: 0,
        productionDataID: {},
        spotCheckID: {}
    },

    computed: {
        /**
         * 計算每個缺點項目之缺點數
         *
         * @returns {Array}
         */
        itemsCount: function () {
            return this.setItemCount(this.items, this.defects);
        },

        /**
         * 計算抽驗項目之缺點數
         *
         * @returns {Array, Array}
         */
        spotCheckItemsCount: function () {
            return this.setItemCount(this.spotCheckItems, this.spotCheckDefects);
        },

        /**
         * 計算缺點平均
         *
         * @returns {Array}
         */
        avgDefect: function () {
            return this.setAvg(this.defects, this.defectList, this.productionDataID)
        },

        /**
         * 計算抽驗缺點平均
         *
         * @returns {Array}
         */
        avgSpotCheckDefect: function () {
            return this.setAvg(this.spotCheckDefects, this.defectList, this.spotCheckID);
        },

        /**
         * 計算生產資訊
         *
         * @returns {{actualQuantity: number, minute: number, checkRate: number, actualMinWeight: number, actualMaxWeight: number}}
         */
        computedInfo: function () {
            let array = {
                actualQuantity: 0,
                minute: 0,
                checkRate: 0,
                actualMinWeight: 9999999999,
                actualMaxWeight: 0
            };
            var total = 0;
            for (let i = 0; i < this.productionData.length; i++) {
                if (this.productionData[i].spotCheck == 0) {
                    array['actualQuantity'] += this.productionData[i].actualQuantity;
                    array['minute'] += this.productionData[i].minute;
                    //let checkRate = parseFloat(array['checkRate']) + parseFloat(this.productionData[i].checkRate);
                    array['checkRate'] = parseFloat(array['checkRate']) + parseFloat(this.productionData[i].checkRate);
                    array['actualMinWeight'] = parseFloat(Math.min(array['actualMinWeight'], this.productionData[i].actualMinWeight)).toFixed(2);
                    array['actualMaxWeight'] = parseFloat(Math.max(array['actualMaxWeight'], this.productionData[i].actualMaxWeight)).toFixed(2);
                    total++;
                }
            }
            array['checkRate'] = parseFloat(array['checkRate'] / total).toFixed(2);
            return array;
        },

        productionDataCount: function () {
            let count = 0;
            for (let i = 0; i < this.productionData.length; i++) {
                if (this.productionData[i].spotCheck == 0) {
                    count++;
                }
            }
            return count;
        },

        spotCheckCount: function () {
            var count = 0;
            for (var i = 0; i < this.productionData.length; i++) {
                if (this.productionData[i].spotCheck == 1) {
                    count++;
                }
            }
            return count;
        },

        productionDataID: function () {
            var array = [];
            for (var i = 0; i < this.productionData.length; i++) {
                if (this.productionData[i].spotCheck == 0) {
                    array.push(this.productionData[i].id);
                }
            }
            return array;
        },

        spotCheckID: function () {
            var array = [];
            for (var i = 0; i < this.productionData.length; i++) {
                if (this.productionData[i].spotCheck == 1) {
                    array.push(this.productionData[i].id);
                }
            }
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
                    productionInfo.spotCheckItems = results.spotCheckItem;
                    productionInfo.spotCheckDefects = results.spotCheckDefect;
                    console.log(productionInfo.spotCheckItems);
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

        addSpotCheckDefectShow: function () {
            $('#addSpotCheckDefectModal').modal({backdrop: 'static'}, 'show');
        },

        editSpotCheckDefectShow: function (id) {
            this.setEditData(id);
            $('#editSpotCheckDefectModal').modal({backdrop: 'static'}, 'show');
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
            for (var i = 0; i < d.length; i++) {
                if (d[i].productionDataID == id) {
                    this.editDefect[d[i].itemID + d[i].defectID] = d[i].value;
                }
            }
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
        },

        setAvg: function (defects, defectList, IDArray) {
            var array = [];
            for (var i = 0; i < defects.length; i++) {
                var count = 0;
                var total = 0;
                for (var x = 0; x < defectList.length; x++) {
                    if (defects[i].itemID == defectList[x].itemID &&
                        defects[i].defectID == defectList[x].defectID &&
                        IDArray.indexOf(defectList[x].productionDataID, 0) != -1) {
                        total = parseFloat(total) + parseFloat(defectList[x].value);
                        count++;
                    }
                }
                var avg = parseFloat((total / count)).toFixed(2);
                array.push(avg);
            }
            return array;
        },

        setItemCount: function (items, defects) {
            var array = [];
            for (var i = 0; i < items.length; i++) {
                var count = 0;
                for (var x = 0; x < defects.length; x++) {
                    if (defects[x].itemID == items[i].itemID) {
                        count++;
                    }
                }
                array[items[i].itemID] = count;
            }
            return array;
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
                        url: url + "/defect/deleteProductionDefect",
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
                                productionInfo.getDefectList();
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
        }
    }
});
