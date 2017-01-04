var item = new Vue({
    el: '#item',
    data: {
        items: {},
        formSet: {},
        dataSet: {},
    },

    mounted: function () {
        this.getItem();
    },

    methods: {
        getItem: function (data = null) {
            $.ajax({
                type: "GET",
                data: data,
                url: url + "/defect/getItem",
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

        add: function () {
            item.formSet = {
                title: '新增缺點項目',
                btn: '新增',
            };
            item.dataSet = {
                type: 'add',
                id: null,
            };
            $('#addModal').modal({backdrop: 'static'}, 'show');
        },

        edit: function (id) {
            item.formSet = {
                title: '編輯缺點項目',
                btn: '更新',
            };

            data = {id: id};
            $.ajax({
                type: "GET",
                url: url + "/defect/getItem",
                data: data,
                success: function(results){
                    item.dataSet = results;
                    item.dataSet.type = 'edit';
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
            $.ajax({
                type: 'POST',
                url: url + "/defect/saveItem",
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
                        item.getItem();
                        swal({
                            title: action + "資料成功!",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "OK",
                            closeOnConfirm: true
                        });
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
        }
    }
});   
