/**
 * Created by Spark on 2017/2/14.
 */
let urlParams = new URLSearchParams(window.location.search);
let checkReport = new Vue({
    el: '#checkReport',
    data: {
        check: {},
        data: {},
        defect: {},
        computedInfo: {},
        productionDefect: {},
        spotCheckDefect: {}
    },
    computed: {
        computedInfo: function () {
            let array = {
                actualQuantity: 0,
                minute: 0,
                checkRate: 0,
                actualMinWeight: 9999999999,
                actualMaxWeight: 0,
                speed: [],
                stressLevel: [],
                thermalShock: []
            };
            let total = 0;
            for (let i = 0; i < this.data.length; i++) {
                if (this.data[i].spotCheck == 0) {
                    array['actualQuantity'] += this.data[i].actualQuantity;
                    array['minute'] += this.data[i].minute;
                    array['speed'].push(this.data[i].speed);
                    array['stressLevel'].push(this.data[i].stressLevel);
                    array['thermalShock'].push(this.data[i].thermalShock);
                    array['checkRate'] = parseFloat((array['checkRate'] + parseFloat(this.data[i].checkRate)).toFixed(2));
                    array['actualMinWeight'] = parseFloat(Math.min(array['actualMinWeight'], this.data[i].actualMinWeight)).toFixed(2);
                    array['actualMaxWeight'] = parseFloat(Math.max(array['actualMaxWeight'], this.data[i].actualMaxWeight)).toFixed(2);
                    total++;
                }
            }
            array['checkRate'] = parseFloat((array['checkRate'] / total).toFixed(2));
            array['speed'] = array['speed'].toString();
            array['stressLevel'] = array['stressLevel'].toString();
            array['thermalShock'] = array['thermalShock'].toString();
            return array;
        },

        productionDefect: function () {
            return this.setDefect(0);
        },

        spotCheckDefect: function () {
            return this.setDefect(1);
        }
    },

    mounted: function () {
        this.getReportData();
    },

    methods: {
        getReportData: function () {
            $.ajax({
                type: "GET",
                url: url + "/defect/report",
                data: {'checkID': urlParams.get('checkID')},
                success: function(results){
                    checkReport.check = results.check;
                    checkReport.data = results.data;
                    checkReport.defect = results.defect;
                },
                error: function(e){
                    var response = jQuery.parseJSON(e.responseText);
                    console.log(response.message);
                }
            });
        },

        setDefect: function (spotCheck) {
            let defect = this.defect;
            let array = [];
            let count = 0;
            for (let i = 0; i < defect.length; i++) {
                if (defect[i].spotCheck == spotCheck) {
                    let item = [];
                    item['name'] = defect[i].name;
                    item['value'] = defect[i].value;
                    array.push(item);
                    count++;
                }
            }
            return array;
        },

        nl2br: function (str) {
            var breakTag = '<br/>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
        }
    }
});