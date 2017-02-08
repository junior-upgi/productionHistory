/**
 * Created by Spark on 2017/1/26.
 */
var check;
check = new Vue({
    el: '#check',
    data: {
        checkList: {},
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

        tooltip: function (event) {
            $(event.target).tooltip('show');
            $('[data-toggle="tooltip"]').tooltip();
        },
    }
});