var template;
template = new Vue({
    el: '#template',
    data: {
        templates: {},
        formSet: {},
        dataSet: {},
        itemList: {},
        selectList: {},
        setItem: {},
        setSelect: {},
    },

    mounted: function () {
        this.getTemplate();
        Sortable.create(document.getElementById('selectSort'), {
            onEnd: function (e) {
                var clonedItems = template.setSelect.filter(function (r) {
                    return r;
                });
                clonedItems.splice(e.newIndex, 0, clonedItems.splice(e.oldIndex, 1)[0]);
                template.setSelect = [];
                template.$nextTick(function () {
                    template.setSelect = clonedItems;
                });
            }
        });
    },

    computed: {},

    methods: {
        getTemplate: function () {
            $.ajax({
                type: "GET",
                url: url + "/defect/getTemplateList",
                success: function (results) {
                    template.templates = results;
                },
                error: function (e) {
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        search: function () {
            var name = $('#search_name').val();
            var data = {name: name};
            $.ajax({
                type: "GET",
                data: data,
                url: url + "/defect/searchTemplate",
                success: function (results) {
                    template.templates = results;
                },
                error: function (e) {
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        tooltip: function (event) {
            $(event.target).tooltip('show');
            $('[data-toggle="tooltip"]').tooltip();
        },

        selectItem: function () {
            var selected = template.itemList;
            var leftList = template.setItem;
            var set = [];
            var push = false;
            var rightList = template.setSelect;
            for (var i = 0; i < leftList.length; i++) {
                for (var s = 0; s < selected.length; s++) {
                    if (leftList[i]['id'] == selected[s]) {
                        rightList.push(leftList[i]);
                        push = true;
                    }
                }
                if (push == false) {
                    set.push(leftList[i]);
                }
                push = false;
            }
            template.setItem = set;
            template.setSelect = rightList;
        },

        removeItem: function () {
            var selected = template.selectList;
            var rightList = template.setSelect;
            var set = [];
            var push = false;
            var leftList = template.setItem;
            for (var i = 0; i < rightList.length; i++) {
                for (var s = 0; s < selected.length; s++) {
                    if (rightList[i]['id'] == selected[s]) {
                        leftList.push(rightList[i]);
                        push = true;
                    }
                }
                if (push == false) {
                    set.push(rightList[i]);
                }
                push = false;
            }
            template.setItem = leftList;
            template.setSelect = set;
        },

        add: function () {
            this.setInit();
            template.formSet = {
                title: '新增缺點套板',
                btn: '新增',
            };
            template.dataSet = {
                type: 'add',
                id: null,
            };
            $.ajax({
                type: "GET",
                url: url + "/defect/getTemplateItem",
                success: function (results) {
                    template.setItem = results.itemList;
                    template.setSelect = results.selectList;
                    $('#addModal').modal({backdrop: 'static'}, 'show');
                },
                error: function (e) {
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        edit: function (id) {
            this.setInit();
            template.formSet = {
                title: '編輯缺點套板',
                btn: '更新',
            };
            data = {id: id};
            $.ajax({
                type: "GET",
                url: url + "/defect/getTemplateItem",
                data: data,
                success: function (results) {
                    template.dataSet = results.template;
                    template.dataSet.type = 'edit';
                    template.setItem = results.itemList;
                    template.setSelect = results.selectList;
                    $('#addModal').modal({backdrop: 'static'}, 'show');
                },
                error: function (e) {
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        save: function () {
            var action = template.formSet.btn;
            var mainData = template.dataSet;
            var detailData = template.setSelect;
            var goUrl = '';
            var type = '';

            if (detailData == undefined || mainData == undefined) {
                return false;
            };
            data = {
                mainData: mainData,
                detailData: detailData,
            };

            if ($('#type').val() == 'add') {
                goUrl = url + "/defect/insertTemplate";
                type = 'POST';
            }

            if ($('#type').val() == 'edit') {
                goUrl = url + "/defect/updateTemplate";
                type = 'PUT'
            }

            $.ajax({
                type: type,
                url: goUrl,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                error: function (e) {
                    var response = jQuery.parseJSON(e.responseText);
                    swal(action + "資料失敗!", response.message, "error");
                    template.setInit();
                    return false;
                },

                success: function (result) {
                    if (result.success == true) {
                        template.setInit();
                        template.getTemplate();
                        swal({
                            title: action + "資料成功!",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "OK",
                            closeOnConfirm: true
                        });
                        formSet = {};
                        dataSet = {};
                        itemList = {};
                        selectList = {};
                        setItem = {};
                        setSelect = {};
                        $('#addModal').modal('hide');
                    }
                }
            });
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
                        type: 'DELETE',
                        url: url + "/defect/deleteTemplate",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {id: id},
                        error: function (e) {
                            var response = jQuery.parseJSON(e.responseText);
                            swal("刪除資料失敗!", response.message, "error");
                            template.setInit();
                            return false;
                        },

                        success: function (result) {
                            if (result.success == true) {
                                template.setInit();
                                template.getTemplate();
                                swal({
                                    title: "刪除資料成功!",
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "OK",
                                    closeOnConfirm: true
                                });
                                template.getTemplate();
                            }
                        }
                    });
                });
        },

        setInit: function () {
            template.formSet = {};
            template.dataSet = {};
            template.itemList = [];
            template.selectList = [];
            template.setItem = {};
            template.setSelect = {};
        }
    }
});   

Array.prototype.remove = function () {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};
