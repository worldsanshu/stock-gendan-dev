$(function () {
    new Vue({
        el: '#index',
        data: {
            banner: [],
            showSearch: false,
            searchList: [],
            searchKey: "",
            stockCode: "sh600000",
            stockShortCode: "600000",
            typeSelectValue: '50',
        },
        methods: {
            getBanner: function () {
                that = this;
                $.get(curpath + "/getSliderApp").done(function (d) {
                    d = JSON.parse(d);
                    if (d.status) {
                        that.banner = d.data;
                    } else {
                        layer.msg(d.data);
                    }
                });
            },
        },
        created: function () {
            this.getBanner();
        }
    })

})
