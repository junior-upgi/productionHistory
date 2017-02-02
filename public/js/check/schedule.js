/**
 * Created by Spark on 2017/1/26.
 */
var schedule;
schedule = new Vue({
    el: '#schedule',
    data: {
        scheduleList: {},
        pageList: {},
        currentPage: 1,
        row: 10,
        totalPage: null,
        pageIndex: null,
        pageIndexCount: 5,
        formSet: {title: null, btn: null},
        dataSet: {type: null, id: null}
    },

    mounted: function () {
        this.getScheduleList();
    },

    computed: {
        pageStart: function () {
            return (this.currentPage - 1) * this.totalPage();
        },

        totalPage: function () {
            if (this.scheduleList.length > 0) {
                return Math.ceil(this.scheduleList.length / this.row);
            }
            return 0;
        },

        pageIndex: function () {
            if (this.scheduleList.length > 0) {
                var allPage = Math.ceil(this.scheduleList.length / this.row);
                var d = Math.floor((schedule.currentPage - 1) / this.pageIndexCount);
                var start = d * this.pageIndexCount;
                var end = start + this.pageIndexCount;
                var list = new Array();
                while (start < end) {
                    list.push(start + 1)
                    start++;
                }
                return list;
            }
            return {};
        }
    },

    methods: {
        getScheduleList: function () {
            $.ajax({
                type: "GET",
                url: url + "/defect/scheduleList",
                success: function (results) {
                    schedule.scheduleList = results;
                    schedule.getPageList(0, schedule.row);
                },
                error: function (e) {
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        setPage: function (page) {
            if (page < 1 || page > schedule.totalPage) {
                return false;
            }
            startIndex = (page - 1) * schedule.row;
            endIndex = startIndex + 10;
            schedule.currentPage = page;
            schedule.getPageList(startIndex, endIndex);
        },

        getPageList: function (start, end) {
            schedule.pageList = schedule.scheduleList.slice(start, end);
        },

        add: function (data) {
            schedule.formSet = {
                title: '新增檢查表',
                btn: '新增'
            };
            var params = 'schedate=' + data.schedate + '&prd_no=' + data.prd_no + '&snm=' + data.snm +
                '&glassProdLineID=' + data.glassProdLineID + '&orderQty=' + data.orderQty + '&id=' + data.id;
            document.location.href = url + '/nav/check.addCheck?' + params;
            //$('#addModal').modal({backdrop: 'static'}, 'show');
        },

        edit: function () {
            schedule.formSet = {
                title: '編輯檢查表',
                btn: '編輯'
            };
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
        }
    }
});