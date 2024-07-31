$(function () {

    vm = new Vue({
        el: '#market',
        data: {
            marketData: [],
            current_price: [],
            diff_price: [],
            current_rate: [],
            iconClasss: [],
            sinahy: [],
            stock_top10: [],
            stock_bot10: [],
            industry_detail: [],
            industry_name: '',
            itemindex: 0,
        },
        computed: {},
        methods: {
            getMarket: function () {
                that = this;
                $.get(curpath + "/market_bat", {code: 'sh000001,399001,399006'}).done(function (d) {
                    if (d.status) {
                        $(d.data).each(function (index, n) {
                            that.current_price.push(n.current_price);
                            that.diff_price.push((n.current_price - n.yesterday_price).toFixed(2));
                            that.iconClasss.push((that.diff_price[index] >= 0 ? "red" : "green"));
                            that.current_rate.push((that.diff_price[index] / n.yesterday_price * 100).toFixed(2));
                        })
                    } else {
                        layer.msg(d.data);
                    }
                });
            },
            getSinahy: function () {
                that = this;
                $.get(curpath + "/sinahy").done(function (d) {
                    if (d.status) {
                        //按照涨跌排序
                        that.sinahy = d.data.sort(
                            function (a, b) {
                                return (b[5] - a[5]);
                            }
                        );
                        $(that.sinahy).each(function (index, n) {
                            if (n[5] > 0) {
                                n['buttonClass'] = 'bENKJG';
                                n['colorClass'] = 'cEvEJT';
                            } else if (n[5] < 0) {
                                n['buttonClass'] = 'dOZppT';
                                n['colorClass'] = 'hluaBw';
                            } else {
                                n['buttonClass'] = 'kLnGWl';
                                n['colorClass'] = 'black666';
                            }
                            n['url'] = curpath + "/bankuailist?industry_code=" + n[0] + "&page=1";
                        })
                    } else {
                        layer.msg(d.data);
                    }
                });
            },
            getstock_top: function () {
                that = this;
                $.get(curpath + "/stock_top10").done(function (d) {
                    if (d.status) {
                        that.stock_top10 = d.data;
                        $(that.stock_top10).each(function (index, n) {
                            n['url'] = '/wap.php/strategy/index.html?code=' + n.code;
                        })
                    } else {
                        layer.msg(d.data);
                    }
                });
            },
            getstock_bot: function () {
                that = this;
                $.get(curpath + "/stock_bot10").done(function (d) {
                    if (d.status) {
                        that.stock_bot10 = d.data;
                        $(that.stock_bot10).each(function (index, n) {
                            n['url'] = '/wap.php/strategy/index.html?code=' + n.code;
                        })
                    } else {
                        layer.msg(d.data);
                    }
                });
            },
            getindustry_detail: function (new_gp, page) {
                $.get(curpath + "/industry_detail", {"industry_code": new_gp, "page": page}).done(function (d) {
                    if (d.status) {
                        var code = '';
                        that.industry_name = d.data[0].industry_name;
                        $(d.data).each(function (index, n) {
                            that.industry_detail.push(n);
                            code += n.code + ','
                        })
                        code = code.substring(0, code.lastIndexOf(','));
                        that.addStockitem(code);
                        loading = false;
                    } else {
                        $.toast("暂无更多数据！", "forbidden");
                        return false;
                    }
                });
            },
            addStockitem: function (code) {
                $.get(curpath + "/market_bat", {"code": code}).done(function (d) {
                    if (d.status) {
                        $(d.data).each(function (index, n) {
                            Vue.set(that.industry_detail[that.itemindex], 'current_price', d.data[index].current_price);
                            Vue.set(that.industry_detail[that.itemindex], 'current_rate', ((d.data[index].current_price - d.data[index].yesterday_price) / d.data[index].yesterday_price * 100).toFixed(2));
                            Vue.set(that.industry_detail[that.itemindex], 'url', '/wap.php/strategy/index.html?code=' + n.code);
                            if (that.industry_detail[that.itemindex].current_rate > 0) {
                                Vue.set(that.industry_detail[that.itemindex], 'colorClass', 'red');
                                Vue.set(that.industry_detail[that.itemindex], 'buttonClass', 'bENKJG');
                            } else if (that.industry_detail[that.itemindex].current_rate < 0) {
                                Vue.set(that.industry_detail[that.itemindex], 'colorClass', 'green');
                                Vue.set(that.industry_detail[that.itemindex], 'buttonClass', 'dOZppT');
                            } else {
                                Vue.set(that.industry_detail[that.itemindex], 'colorClass', 'grat');
                                Vue.set(that.industry_detail[that.itemindex], 'buttonClass', 'kLnGWl');
                            }
                            that.itemindex++;
                        })

                    }
                })
            },
        },

        created: function () {
            that = this;
            if (current_page == 'index') {
                that.getMarket();
                that.getSinahy();
                that.getstock_top();
                that.getstock_bot();
                var inter = setInterval(function () {
                    that.getMarket();
                    that.getSinahy();
                    that.getstock_top();
                    that.getstock_bot();
                }, 5000);
            }

            if (current_page == 'bankuai') {
                that.getSinahy();
            }

            if (current_page == 'bankuailist') {
                that.getindustry_detail(new_gp, page);

            }
        },
    })

    if (current_page == 'bankuailist') {
        var loading = false;
        $(document.body).infinite().on("infinite", function () {
            if (loading) return;
            loading = true;
            var page = Math.ceil($(".list-view-section-body .bDGVPo").length / pagesize) + 1;
            if (vm.getindustry_detail(new_gp, page)) loading = false;
        });
    }
})
