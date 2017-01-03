var todayGlass = new Vue({
    el: '#todayGlass',
    data: {
        ip: null,
        auth: false,
        glasses: [],
        search: null,
    },

    mounted: function () {
        this.getIp();
    },

    methods: {
        getIp: function () {
            $.get( window.baseurl + "/api/ip", function( results ) {
                todayGlass.ip = results.data;
                
                if (todayGlass.ip.substr(0, 7) == "192.168") {
                    todayGlass.auth = true;
                    todayGlass.searchGlassInfo();
                };

                if (!todayGlass.auth) {
                    $(".no-data").show();
                }
                
            }).fail(function(e){
                console.log( e );
            });
        },

        searchGlassInfo: function () {
            $.get( window.baseurl + "/api/getTodayGlassInfo/", function( results ) {
                todayGlass.glasses = results.data;
            }).fail(function(e){
                console.log( e );
            });
        },
    }
});   