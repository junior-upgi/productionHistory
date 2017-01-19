var item = new Vue({
    el: '#item',
    data: {
        items: {},
        formSet: {},
        dataSet: {},
        defectList: {},
        selectList: {},
        setDefect: {},
        setSelect: {},
    },

    mounted: function () {
        this.getItemList();
        Sortable.create(document.getElementById('selectSort'), {
            onEnd: function(e) {
                var clonedItems = item.setSelect.filter(function(r){
                    return r;
                });
                clonedItems.splice(e.newIndex, 0, clonedItems.splice(e.oldIndex, 1)[0]);
                item.setSelect = [];
                item.$nextTick(function(){
                    item.setSelect = clonedItems;
                });
            }
        });
    },

    methods: {
        getItemList: function (data = null) {
            $.ajax({
                type: "GET",
                data: data,
                url: url + "/defect/getItemList",
                success: function(results){
                    item.items = results;
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        search: function () {
            var name = $('#search_name').val();
            if (name.trim() == '')
            {
                item.getItem(data);
            }
            var data = {
                name: name
            };
            item.getItem(data);
        },
        
        tooltip: function (event) {
            $(event.target).tooltip('show');
            $('[data-toggle="tooltip"]').tooltip();
        },

        selectDefect: function () {
            var selected = item.defectList;
            var leftList = item.setDefect;
            var set = [];
            var push = false;
            var rightList = item.setSelect;
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
            item.setDefect = set;
            item.setSelect = rightList;
        },

        removeDefect: function () {
            var selected = item.selectList;
            var rightList = item.setSelect;
            var set = [];
            var push = false;
            var leftList = item.setDefect;
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
            item.setDefect = leftList;
            item.setSelect = set;
        },

        add: function () {
            this.setInit();
            item.formSet = {
                title: '新增缺點上層項目',
                btn: '新增',
            };
            item.dataSet = {
                type: 'add',
                id: null,
            };
            $.ajax({
                type: "GET",
                url: url + "/defect/getDefectGroup",
                success: function(results){
                    item.setDefect = results.defectGroup;
                    item.setSelect = results.selected;
                    $('#addModal').modal({backdrop: 'static'}, 'show');
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        edit: function (id) {
            this.setInit();
            item.formSet = {
                title: '編輯缺點上層項目',
                btn: '更新',
            };
            data = {id: id};
            $.ajax({
                type: "GET",
                url: url + "/defect/getDefectGroup",
                data: data,
                success: function(results){
                    item.dataSet = results.item;
                    item.dataSet.type = 'edit';
                    item.setDefect = results.defectGroup;
                    item.setSelect = results.selected;
                    console.log(item.setDefect);
                    console.log(item.setSelect);
                    $('#addModal').modal({backdrop: 'static'}, 'show');
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        save: function (data) {
            var action = item.formSet.btn;
            var mainData = item.dataSet;
            var detailData = item.setSelect;
            var goUrl = '';
            var type = '';

            if (detailData == undefined || mainData == undefined) {
                return false;
            }
            data = {
                mainData: mainData,
                detailData: detailData,
            };
            
            if ($('#type').val() == 'add') {
                goUrl = url + "/defect/insertItem";
                type = 'POST';  
            } 

            if ($('#type').val() == 'edit') {
                goUrl = url + "/defect/updateItem";
                type = 'PUT'
            }

            $.ajax({
                type: type,
                url: goUrl,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                error: function(e) {
                    var response = jQuery.parseJSON(e.responseText);	
                    swal(action + "資料失敗!", response.message, "error");	
                    item.setInit();	    
                    return false;
                },

                success: function(result){			  		  	
                    if (result.success == true){	 
                        item.setInit();
                        item.getItemList();
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
                        defectList = {};
                        selectList = {};
                        setDefect = {};
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
            function(){
                $.ajax({
                    type: 'DELETE',
                    url: url + "/defect/deleteItem",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {id: id},
                    error: function(e) {
                        var response = jQuery.parseJSON(e.responseText);	
                        swal("刪除資料失敗!", response.message, "error");	
                        item.setInit();	    
                        return false;
                    },

                    success: function(result){			  		  	
                        if (result.success == true){	 
                            item.setInit();
                            item.getItem();
                            swal({
                                title: "刪除資料成功!",
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-success",
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                            });
                            item.getItem();
                        }
                    }
                }); 
            });
        },

        setInit: function () {
            item.formSet = {};
            item.dataSet = {};
            item.defectList = [];
            item.selectList = [];
            item.setDefect = {};
            item.setSelect = {};
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