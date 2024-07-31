// 体验点买页面js
$(function () {
    new Vue({
        el: "#scdnqmqq",
        data: {
            money: [],
            info: [],
        },
        computed: {},
        methods: {},
        created: function () {
            that = this;
            $.get(curpath, {'ismobile': 1}, function (d) {
                if (d.status == 1) {
                    that.money = d.data.money;
                    that.info = d.data.info;
                } else {
                    $.toast(d.message, "forbidden");
                    return false;
                }
            }, 'json');
        },
    })

})
