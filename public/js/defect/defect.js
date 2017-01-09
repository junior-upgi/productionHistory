var defect = new Vue({
    el: '#defect',
    data: {
        defects: {},
        formSet: {},
        dataSet: {},
    },

    mounted: function () {
        this.getDefect();
    },

    methods: {
        getDefect: function (data = null) {
            $.ajax({
                type: "GET",
                data: data,
                url: url + "/defect/getDefect",
                success: function(results){
                    defect.defects = results;
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
                defect.getDefect(data);
            }
            var data = {
                name: name
            };
            defect.getDefect(data);
        },
        
        tooltip: function (event) {
            $(event.target).tooltip('show');
            $('[data-toggle="tooltip"]').tooltip();
        },

        add: function () {
            defect.formSet = {
                title: '新增缺點項目',
                btn: '新增',
            };
            defect.dataSet = {
                type: 'add',
                id: null,
            };
            $('#addModal').modal({backdrop: 'static'}, 'show');
        },

        edit: function (id) {
            defect.formSet = {
                title: '編輯缺點項目',
                btn: '更新',
            };

            data = {id: id};
            $.ajax({
                type: "GET",
                url: url + "/defect/getDefect",
                data: data,
                success: function(results){
                    defect.dataSet = results;
                    defect.dataSet.type = 'edit';
                    $('#addModal').modal({backdrop: 'static'}, 'show');
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        save: function (data) {
            var action = defect.formSet.btn;
            $.ajax({
                type: 'POST',
                url: url + "/defect/saveDefect",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                error: function(e) {
                    var response = jQuery.parseJSON(e.responseText);	
                    swal(action + "資料失敗!", response.message, "error");	
                    defect.setInit();	    
                    return false;
                },

                success: function(result){			  		  	
                    if (result.success == true){	 
                        defect.setInit();
                        defect.getDefect();
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
                    url: url + "/defect/deleteDefect",
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
                            defect.getDefect();
                        }
                    }
                }); 
            });
        },

        setInit: function () {
            defect.formSet = {};
            defect.dataSet = {};
        }
    }
});   
