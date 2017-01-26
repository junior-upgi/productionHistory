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
    }
});