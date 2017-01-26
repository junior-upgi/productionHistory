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
        }
    }
});