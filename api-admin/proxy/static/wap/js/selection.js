$(function () {

    vm = new Vue({
        el: '#market',
        data: {
            current_price: [],
            diff_price: [],
            current_rate: [],
            iconClasss: [],
            DataContainer: [],
            MyHistory: [],
            MaxHistory: [],
            mySelect: [],
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
//             autoHidden:function () {
//                   var mouseondiv = false;
//                   $('.searchPanel').hover(function () {
//                       mouseondiv = true;
//                   }, function () {
//                       mouseondiv = false;
//                   });
//                   setTimeout(function () {
//                       if (!mouseondiv)
//                           $(".searchPanel").hide();
//                   }, 5000);
//               },
            searchBegin: function (er) {
                var key = $("#frmSearch").val();
                if (key) {
                    $(".searchPanel").show();
                    this.searchStock(key);
                } else {
                    $(".searchPanel").hide();
                }
            },
            searchStock: function (key) {
                that = this;
                $.get("/wap.php/strategy/strategy/encodetogb2312", {"str": key}, function (result) {
                    key = result.SuccessData;
                    var scp = document.createElement("script");
                    scp.src = "http://so.hexun.com/ajax.do?key=" + key + '&type=stock';
                    var s = document.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(scp, s);
                    scp.onload = function () {
                        var json = hxSuggest_JsonData;
//                     DataContainer.List = new Array();
                        var pParam = new Object();
                        pParam.stockcode = key;
                        for (var i in json) {
                            if (json[i].type == "A股") {
                                var pp = new Object();
                                pp.StockCode = json[i].code;
                                pp.StockName = json[i].name;
                                pp.PinYin = json[i].pinyin;
                                pp.SelectStatus = 0;
                                pp.url = '/wap.php/strategy/index.html?code=' + json[i].code;
                                pp.DifferentialPersent = 0;
                                that.DataContainer.unshift(pp);
//                             pParam.stockcode = pParam.stockcode + json[i].code + ",";
                            }
                        }

                    };
                });
            },
            //添加自选股
            AddSelfStock: function (elem) {
                elem = elem.currentTarget;

                stockCode = $(elem).attr("stockcode");
                StockName = $(elem).attr("StockName");
                if (stockCode.length == 6) {
                    $.post("/wap.php/strategy/strategy/addselfstock", {
                        code: stockCode,
                        name: StockName
                    }).done(function (result) {
                        if (result.status) {
                            $(elem).children("img").attr('src', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAYAAAAehFoBAAAEy0lEQVRYhb3ZeaxdUxTH8c+9StSrGmKoP0yhDWoM/0hpSQz1B1rzEPKIqTEmpsbQRomYEypm2opZqIpoqyGGEtKkghRFJWKqoQnV18pDnz/Wvd55+5x7373vnef3315773O+5+y119577cqiRW8YhDpwMMZjLEZjW4yo1a/BT/gCn+ItLEbXQF9YGQBwBUfiLByN4W32X4d5mI3X0NNO52qbLzsOH2I+TtI+rFqfU7AASzGpnc6tAu+KRXgB+7Tzgn60L+ZiIXZppcOwFtqcjvuxaYP6VXgD7wk//QFra3WbYSfsgQk4SPGoHCH+9rl4rhlMMx+u4EZc26D+dczEq/ir2Usy2gwn4FLs1aDNDExv9IBGLlHBA4phPxZR4TAxeVqFhd/xqHCrk/FtQZtpuKfRAxoB34zzElsPbsX+eKcNyCL1iKEfizkF9ReL0c2pCPhUTE1s3eKPTMXfA8bM6w904hKsT+quE/Onj1Lg0XgwsXXjGDxfCmKxZoq4nsbkByTRIwW+Tz4anC/CzlDrcVyR2Ebg3qwhC3y8mEhZPSFWpP9Ld8mP5ESZxaUOXBGzM6tfhPMPlYbhbBGJtsvYp+DXpO00wfgf8ETsnTSajt9KxwxtgldEiLtJLPdb1OpW4fqk/X44nF7gzqTBj5g1BKAE7EtiA1XXtjgwU35MPkZ3EsAdIgpkNQd/lklZUx328MT+D5Znyt0iAGR1LIZXxaq1cVL5TImQdTWC7cEFWJHYny7of1AdOKuV+KgkyOzLmsE+UtDnG3ye2MZXsWdifK8Mwoz6g32oSd/FSXnPqtjrZrVceRoMLHyWlMdUsU1i/G7AeH01WFjykWLUMPmleHWDzqNwogh5c8XMHkpYYnOU1ciiE0elwDYSH2CHWnmuOJd1N4CdJ7/MtwtbyFJV8BUFHSdkYGEynsVGQwhLwehX8XNi3L6g41fyLjBJX+iyYWHHpLyyWoPJareCjp/h6gJ7HXpz5cPC7kl5eRWfJMZxiv34TlxVYJ8kVqmyYckvasuq8uezrcS5rUi3K4beMimXATtaPlfxdhVvy290TmvyoEbQdZUBC2ck5bVYXBWJuZeTyk69Cb0iNYIuC7aj9pys5mFdfT88O6ncQiQ7mqkOXT84rlcOLHHS2TqxzaI381MRu/5s3myNiBjf9/PwQ0ScXoj3B89qByzTd4SX4gD01P9wj0gRZTVC/PkN+nnBm7hBObDD8KS8O85QG8nsqflFkaHM6jBx5vq/dI9IGGY1X/gv8nmJKfKbn6m4vHS0vqqIOTElsf+Bi7KGFHiFSJykugO36d89BqKNheulSRRiEn+dNRTl1p7BLQX2K4XLpOv7YLQfluDMgroZeCo1NspeXiPyXakOFfuKaXrzCAPRjuJUvET+iAZ3a5AjbgTcIzKKRSnP4SIqfCMSh4dgwxYgR+EcMYG+FP5a5GI34LJGD2nlFukkPKx4n1xXl/hbX4i43SVCVAd2FnngfRVvqupaLT6oaZa01WuvXcQQHtFK4wFoAS6UTLAitXqLtEKkliYrN2fxodieHqUFWNq/p3tJzOyJIpqsa7O/Wp+nas/YX2ZRaEWtXHul6hH7hoXCR8eJvcRYjBFpg45a2y69V7fLxNXtu3qvxdrWv01AFci/sQFZAAAAAElFTkSuQmCC');
                            that.DataContainer[$(elem).index()].SelectStatus = 1;
                            layer.msg(result.message);
                        } else {
                            layer.msg(result.message);
                        }
                    });
                } else {
                    layer.msg("请先选择股票");
                }
            },
            //添加自选股
            DelSelfStock: function (elem) {
                elem = elem.currentTarget;
                stockCode = $(elem).attr("stockcode");
                StockName = $(elem).attr("StockName");
                if (stockCode.length == 6) {
                    $.post("/wap.php/strategy/strategy/delselfstock", {
                        code: stockCode,
                        name: StockName
                    }).done(function (result) {
                        if (result.status) {
                            $(elem).children("img").attr('src', "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAYAAAAehFoBAAAEeElEQVRYhcXZa4hVVRQH8N+93UodzbSXEZnkI8NeVt80IyiTqCzCD2KFhr0s60MkvegxQQ8wKCoriNQgkRLMPmQqFFkZRGkPLDTtQVmWZeGoI9Nj+rDvreM+95y55851+sMwrLX243/2XXvtvdYudV+gN2jDBJyLcRiDozGwat+Nn7AZG/E23sPeZicsNUG4hAsxE5eif8H+nXgVi7Ea3YUmL0j4MtyP0wv1ysYGPIAVjXYoN9hupLAay7WOLIwXVvsNnNhIh0ZWeDqexWEZ9p14E+vwOX7AnqptCE4Q/HsSJqJfxji7MBuvNEu4hAdxd4b9LTyB1/FH3iQJDMY03IpTMtq0475MUhmES8KqXlfH9iluxjsNkswafxrm4/g69idxS72OWT78kDTZbjyKs/SObG2slwVXWVzHPlf4dVOot8LTsSTSdeFKPfhXLzAXj0sv4IyYS0x4FNZjUELXJcTbVa1mGeFqLBLcpYYOIZJsrSniL1pgf7JwvQNPFl7E7ZFuEJ5KKpKEr0DsIC8JX10EV+G36t+sgn0fw7JINwVTa0LNJUr4GKclGu4Q7ga/F5hwsHB3OLQqd+FYIVY3iiOwqfq/hvU4G921FZ4SkSXEwiJkYXiCLByCkwqO8SvuiXRn4nz+c4mZUYMfsbDgRFk4qIk+L+D7SDeLQLhNiAJJLMa+JiZqFbrwdKSbiv5l4YyPz/elfcGqB8QcBmBiGedEhu34pE8o5eMbYfMlMamMUyPluj6h0xjiK8C4inC6JRF/VYxhOC7DFo9FiBKdGe2/xS85c30RyWMqQg6WRLw7k7gGz6GS0ybG8zm2P3Gt7MPpu0geVpY+inflTNCuGNmeUMHDOfaOSB5U73pZqqOrodGUqgjyxkxxKUt/RVYqRLhUN52i10EnbsqxD4zkjgp+xtCEsl4GUMMyrMRo9U+wUdLxc7ZwT4nxN74UahdZGBHJ2yvYgrEJ5Vj52JNBgHBCxdiEj3oYMwsnR/LmMj6LlBPk+3FfYlIkbyxLB+cjhbzt/8ZooR6SxNoy1kpfdKb3CaV8zIjkvXi3LPjka5FxlvQO7Uu0YU6kW4HOWgxcFBmHyKgLNIG/mugzF0dFuoXkp0i7hYixrcBEQ4XjdEBV3idkITsKjDFcKM0mf+FUitQtVBGTGCisfJHTbadQv9iKr4WEtAjZipD4xu7YXuWYqkusls6cH8GdBSbtDRbgxki3EhfVhHj15kgf1XfgtpZT2x8loQwWk+0Q/PlfxIS3qF8AnF8dsJmEsif0E1xvXh3bDRJVH+r751LBDWLMwxphU7QK4/GBUKaK0S5d48vcUHcJF/UY5wlZwL04vDmOCEXuBQLZOEUjlFvr1oh7U9DeJazAErwvZA95OAYX4xJhEx2c0a6pgnYSPT0ZEGL2h8LNbJtwjFaEeDxCqLafIT9E9vrJIImReEY65LUKq4QI9VVPDRs9FLZiMi7X2prFBuEpbYoGyNL8w+JkoR43VXMPi8uFctgaB/hhMUbW021b1b5Hi59u/wFPzfCMJWTqWQAAAABJRU5ErkJggg==");
                            that.DataContainer[$(elem).index()].SelectStatus = 0;
                            layer.msg(result.message);
                        } else {
                            layer.msg(result.message);
                        }
                    });
                } else {
                    layer.msg("请先选择股票");
                }
            },
            opendetail: function (url, elem) {
                window.open(url);
                that.addHistory(elem);
            },
            addHistory: function (elem) {
                elem = elem.currentTarget;
                stockCode = $(elem).attr("stockcode");
                StockName = $(elem).attr("StockName");
                $.post(curpath + "/addHistory", {code: stockCode, code_title: StockName}).done(function (d) {
                    if (d.status) {
                        layer.msg(d.message);
                    }
                })
            },
            delHistory: function (elem) {
                $.post(curpath + "/delHistory", {mid: mid}).done(function (d) {
                    if (d.status) {
                        $(".llKIdg").html("");
                        layer.msg(d.message);
                    }
                })
            },
            getMyHistory_secher: function () {
                that = this;
                $.post(curpath + "/getHistory_secher", {mid: mid}).done(function (d) {
                    if (d.status) {
                        $(d.data).each(function (index, n) {
                            n['url'] = '/wap.php/strategy/index.html?code=' + n.code;
                        })
                        that.MyHistory = d.data;
//                             layer.msg(d.message);
                    }
                })
            },

            getMaxHistory_secher: function () {
                that = this;
                $.post(curpath + "/getHistory_secher", {mid: 0}).done(function (d) {
                    if (d.status) {
                        $(d.data).each(function (index, n) {
                            n['url'] = '/wap.php/strategy/index.html?code=' + n.code;
                        })
                        that.MaxHistory = d.data;
//                             layer.msg(d.message);
                    }
                })
            },
            getMy_select: function () {
                that = this;
                $.post("/wap.php/strategy/strategy/my_select", {mid: mid}).done(function (d) {
                    if (d.status) {
                        $(d.data).each(function (index, n) {
                            n['diff_price'] = (n.current_price - n.yesterday_price).toFixed(4);
                            if (n['diff_price'] > 0) {
                                n['buttonClass'] = 'bENKJG';
                                n['colorClass'] = 'red';
                            } else if (n['diff_price'] < 0) {
                                n['buttonClass'] = 'dOZppT';
                                n['colorClass'] = 'green';
                            } else {
                                n['buttonClass'] = 'kLnGWl';
                                n['colorClass'] = 'grayb5b5b5';
                            }
                            n['current_rate'] = (n['diff_price'] / n.yesterday_price * 100).toFixed(2);
                            n['url'] = '/wap.php/strategy/index.html?code=' + n.code;
                        })
                        that.mySelect = d.data;
                    }
                })
            },

        },

        created: function () {
            $('body').click(function () {
                $('.searchPanel').hide();
            })
            this.getMarket();
            this.getMyHistory_secher();
            this.getMaxHistory_secher();
            current_page == 'selection' && this.getMy_select()
        },
    })
})
