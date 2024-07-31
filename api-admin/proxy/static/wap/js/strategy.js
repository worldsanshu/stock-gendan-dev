//交易方向
var EntrustDirection = {
    Buy: 1,
    Sell: 2
};
//交易模式
var TradeType = {
    MustTrade: 0,//即时成交
    HandTrade: 1,//手动平仓
    ExpiredTrade: 2,//过期平仓
    RiskTrade: 3//强制平仓
};

//策略类型
var StrategyType = {
    FrimTrade: 0, //实盘
    SimulationTrade: 1 //模拟盘
};

var TradeMode = {
    T1: 0,
    TN: 1
}

var stockCode = "";
var priceData = {};
var sysConfig = new Object();
var TradeModel = 1;
var isKlineRiskStock = false;
var fristDoCounter = true;
var stockVolume = "";
var deductible = 0;//
var cacheData = {};//缓存对象
var _stopWinBox, _stopLossBox, _stop_zyprice, _stop_zsprice;
var bangPrice;
var PositionData;
var IsDelay = 1;
var page = 1;
var pagesize = 8;
var total = 0;
var trademodel = -1;
var firstEchart = true;
// 切换
$(".switch-yc").click(function () {
    $(this).toggleClass("onswitch-yc");
    if ($(this).hasClass('onswitch-yc')) {
        IsDelay = 0;
    } else {
        IsDelay = 1;
    }
});

//url参数===================
// var tab = getQueryString("tab");
// switch (tab) {
//     case "1":
//         $(".stra-top ul li").eq(1).click();
//         break;
//     case "2":
//         $(".stra-top ul li").eq(2).click();
//         break;
//     default:
//         break;
// }
//url参数===================

//搜索===================
$(document).ready(function () {
    $("#frmSearch").val(stcode);
    $("#SearchBtn").click();

});
$("#frmSearch").bind("input", function (er) {
    var key = $("#frmSearch").val();
    if (key) {
        $(".searchPanel").show();
        searchStock(key);
    } else {
        $(".searchPanel").hide();
    }
});
$("#SearchBtn").click(function () {
    var key = $("#frmSearch").val();
    if (key) {
        $(".searchPanel").show();
        searchStock(key);
    } else {
        $(".searchPanel").hide();
    }
});

$("#frmSearch").bind("blur", function () {
    var mouseondiv = false;
    $('.searchPanel').hover(function () {
        mouseondiv = true;
    }, function () {
        mouseondiv = false;
    });
    setTimeout(function () {
        if (!mouseondiv)
            $(".searchPanel").hide();
    }, 200000);
});

// $("#frmSearch").bind("focus", function () {
//     $(this).val("");
// });  

//K线图
var minute_k = '/wap.php/market/index/minute_k';
var month_k = '/wap.php/market/index/month_k';
var week_k = '/wap.php/market/index/week_k';
var day_k = '/wap.php/market/index/day_k';
var selectStock = function (code, stVolume) {
    if (stockCode != code) {
        stockCode = code;
        $(".searchPanel").hide();
//         $("#frmSearch").val(code);

        getData(minute_k, "minute_k", code);
        getData(day_k, "day_k", code);
        getData(week_k, "week_k", code);
        getData(month_k, "month_k", code);

        GetQMarket(code);
        initStrategy(code);
        setVisits(code);
        var inter = setInterval(function () {
            if (priceData.miniXYJ != undefined) {
                DoCounter();
                initPriceBox(stVolume);
                clearInterval(inter);
            }
        }, 2000);
    } else {
        $(".searchPanel").hide();
//         $("#frmSearch").val(code);
    }
}
selectStock(stcode);

function getData(url, id, code) {
    $.ajax({
        url: url,
        type: "get",
        dataType: "json",
        data: {"code": code},
        success: function (d) {
            if (d.status == 1) {
                if (id == 'minute_k') {
                    printKm(d.data, id);
                } else {
                    printK(d.data, id);
                }
            } else {
                $.toast("k线数据获取失败，请确认是否退市！", "forbidden", function () {
                    window.location.href = "/wap.php/strategy/index.html";
                });
                return false;
            }
        }
    });
}

var preclose;
var minute_k_width = $('body').width() * 0.65;
var dwminute_k_width = $('body').width();
$("#minute_k").css('width', minute_k_width + 'px');
$("#day_k").css('width', dwminute_k_width + 'px');
$("#week_k").css('width', dwminute_k_width + 'px');
$("#month_k").css('width', dwminute_k_width + 'px');

function printKm(Data, id) {
    var date = [];
    var data = [];
    var volumes = [];
    var MA5 = calculateMA(5, data);
    var MA10 = calculateMA(10, data);
    var MA20 = calculateMA(20, data);
    $.each($(Data), function (i, n) {
        data.push(parseFloat(n.price));
        date.push(n.time.substr(0, 2) + ':' + n.time.substr(2, 2));
        volumes.push(n.volume);
    });

    var option = {
        tooltip: {
            trigger: 'axis',
            position: function (pt) {
                return [pt[0], '10%'];
            }
        },
        title: {
            show: false,
        },
        grid: [{
            left: 0,
            right: 0,
            top: 5,
            width: '100%',
            height: 120
        }, {
            left: 0,
            right: 0,
            height: 40,
            width: '100%',
            top: 138
        }],
        xAxis: [{
            type: 'category',
            boundaryGap: false,
            data: date,
            axisLine: {
                lineStyle: {
                    color: '#b5b5b5',
                },
            },
            axisLabel: {
                color: '#b5b5b5',
                fontSize: 8,
                align: 'left',
            },
        },
            {
                type: 'category',
                gridIndex: 1,
                data: date,
                scale: true,
                boundaryGap: false,
                axisLine: {
                    lineStyle: {
                        color: '#b5b5b5',
                        width: 1,
                    }
                },
                axisTick: {show: false},
                splitLine: {show: false},
                axisLabel: {show: false},
                splitNumber: 20,
                min: 'dataMin',
                max: 'dataMax'
                // axisPointer: {
                //     label: {
                //         formatter: function (params) {
                //             var seriesValue = (params.seriesData[0] || {}).value;
                //             return params.value
                //             + (seriesValue != null
                //                 ? '\n' + echarts.format.addCommas(seriesValue)
                //                 : ''
                //             );
                //         }
                //     }
                // }
            }
        ],
        yAxis: [{
            type: 'value',
            boundaryGap: [0, '100%'],
            axisTick: {show: false},
            max: Math.max.apply(null, data),
            min: Math.min.apply(null, data),
            axisLine: {
                lineStyle: {
                    color: '#b5b5b5',
                    width: 1,
                }
            },
            splitLine: {
                show: true,
                lineStyle: {
                    opacity: 0.5,
                },
            },
            axisLabel: {
                inside: true,
                fontSize: 8,
                textStyle: {
                    color: function (value, index) {
                        return value >= preclose ? '#FF4D4F' : 'green';
                    }
                }
            },
        },
            {
                scale: true,
                gridIndex: 1,
                splitNumber: 2,
                axisLabel: {show: false},
                axisLine: {show: false},
                axisTick: {show: false},
                splitLine: {show: false}
            }
        ],

        series: [
            {
                name: '价格',
                type: 'line',
                smooth: true,
                symbol: 'none',
                sampling: 'average',
                itemStyle: {
                    color: '#333',
                },
                lineStyle: {
                    color: '#ff4d4f',
                    width: 1,
                    pacity: 0,
                    type: 'dashed',
                },
                areaStyle: {
                    color: '#fde7e1',
                    opacity: 1,
                    origin: 'start',
                },
                data: data,
            },
            {
                name: 'Volume',
                type: 'bar',
                xAxisIndex: 1,
                yAxisIndex: 1,
                data: volumes
            }
        ]
    };

    if (option && typeof option === "object") {
        var myChart = echarts.init(document.getElementById(id));
        myChart.setOption(option, true);
        window.onresize = myChart.resize;
    }
}

function printK(Data, id) {

    var upColor = '#00da3c';
    var downColor = '#ec0000';
    var colorList = ['#c23531', '#2f4554', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622', '#bda29a', '#6e7074', '#546570', '#c4ccd3'];
    var labelFont = 'bold 12px Sans-serif';
    var dates = [];
    var data = [];
    var volumes = [];
    var dayData = new Array();
    $.each($(Data), function (i, n) {
        data.push([parseFloat(n.open), parseFloat(n.close), parseFloat(n.high), parseFloat(n.low), parseFloat(n.price_equal)]);
        dates.push(n.time.substr(0, 2) + '/' + n.time.substr(2, 2) + '/' + n.time.substr(4, 2));
        volumes.push(n.volume);
    });
    var dataMA5 = calculateMA(5, data);
    var dataMA10 = calculateMA(10, data);
    var dataMA20 = calculateMA(20, data);
    var linetype = '日K';
    (id == 'week_k') && (linetype = '周K');
    (id == 'month_k') && (linetype = '月K');

    // 数据意义：开盘(open)，收盘(close)，最低(lowest)，最高(highest)
    option = {
        animation: false,
        color: colorList,
        title: {
            show: false,
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'line'
            }
        },
        visualMap: {
            show: false,
            seriesIndex: 0,
            dimension: 2,
            pieces: [{
                value: 1,
                color: downColor
            }, {
                value: -1,
                color: upColor
            }]
        },
        legend: {
            type: 'scroll',
            top: 0,
            right: '5%',
            itemWidth: 10,
            itemGap: 5,
            padding: 2,
            data: ['MA5', 'MA10', 'MA20'],
            textStyle: {
                fontSize: 10,
                width: '50%',
                borderColor: '#ffffff',
            }
        },

        axisPointer: {
            link: [{
                xAxisIndex: [0, 1]
            }]
        },
        xAxis: [{
            type: 'category',
            data: dates,
            boundaryGap: false,
            axisLine: {lineStyle: {color: '#777'}},
            axisLabel: {
                align: 'left',
            },
            min: 'dataMin',
            max: 'dataMax',
            axisPointer: {
                show: true
            }
        }, {
            type: 'category',
            gridIndex: 1,
            data: dates,
            scale: true,
            boundaryGap: false,
            splitLine: {show: false},
            axisLabel: {show: false},
            axisTick: {show: false},
            axisLine: {lineStyle: {color: '#777'}},
            splitNumber: 20,
            min: 'dataMin',
            max: 'dataMax',
            axisPointer: {
                type: 'shadow',
                label: {show: false},
                triggerTooltip: true,
                handle: {
                    show: true,
                    margin: 30,
                    color: '#B80C00'
                }
            }
        }],
        yAxis: [{
            scale: true,
            splitNumber: 2,
            axisLine: {lineStyle: {color: '#777'}},
            splitLine: {show: true},
            axisTick: {show: false},
            axisLabel: {
                inside: true,
                formatter: '{value}\n'
            }
        }, {
            scale: true,
            gridIndex: 1,
            splitNumber: 2,
            axisLabel: {show: false},
            axisLine: {show: false},
            axisTick: {show: false},
            splitLine: {show: false}
        }],
        grid: [{
            left: 10,
            right: 10,
            top: 30,
            width: '95%',
            height: 120
        }, {
            left: 10,
            right: 10,
            height: 40,
            width: '95%',
            top: 160
        }],
        graphic: [{
            type: 'group',
            left: 'center',
            top: 50,
            width: 300,
            bounding: 'raw',
            children: [{
                id: 'MA5',
                type: 'text',
                style: {fill: colorList[1], font: labelFont},
                left: 0
            }, {
                id: 'MA10',
                type: 'text',
                style: {fill: colorList[2], font: labelFont},
                left: 'center'
            }, {
                id: 'MA20',
                type: 'text',
                style: {fill: colorList[3], font: labelFont},
                right: 0
            }]
        }],
        series: [{
            name: 'Volume',
            type: 'bar',
            xAxisIndex: 1,
            yAxisIndex: 1,
            itemStyle: {
                normal: {
                    color: '#7fbe9e',
                },
                emphasis: {
                    color: '#140'
                }
            },
            data: volumes
        }, {
            type: 'candlestick',
            name: linetype,
            data: data,
            itemStyle: {
                normal: {
                    color: '#ef232a',
                    color0: '#14b143',
                    borderColor: '#ef232a',
                    borderColor0: '#14b143'
                },
                emphasis: {
                    color: 'black',
                    color0: '#444',
                    borderColor: 'black',
                    borderColor0: '#444'
                }
            }
        }, {
            name: 'MA5',
            type: 'line',
            data: dataMA5,
            smooth: true,
            showSymbol: false,
            lineStyle: {
                normal: {
                    width: 1
                }
            }
        }, {
            name: 'MA10',
            type: 'line',
            data: dataMA10,
            smooth: true,
            showSymbol: false,
            lineStyle: {
                normal: {
                    width: 1
                }
            }
        }, {
            name: 'MA20',
            type: 'line',
            data: dataMA20,
            smooth: true,
            showSymbol: false,
            lineStyle: {
                normal: {
                    width: 1
                }
            }
        }]
    };

    if (option && typeof option === "object") {
        var myChart = echarts.init(document.getElementById(id));
        myChart.setOption(option, true);
        window.onresize = myChart.resize;
    }

}

function calculateMA(dayCount, data) {
    var result = [];
    for (var i = 0, len = data.length; i < len; i++) {
        if (i < dayCount) {
            result.push('-');
            continue;
        }
        var sum = 0;
        for (var j = 0; j < dayCount; j++) {
            sum += data[i - j][1];
        }
        result.push((sum / dayCount).toFixed(2));
    }
    return result;
}

function GetQMarket(code) {

    if (stockCode != code) {
        return;//阻止无限递归
    }
    $.post("/strategy/strategy/getqmarket", {"stockcode": code}, function (result) {
        var data = result;
        if (!$("#frmSearch").is(':focus')) {
            $("#frmSearch").val(data.name + "  " + data.code);
        }
        data.CurrentMatch = data.current_price;
        if (data.CurrentMatch == "0.00") {
            data.CurrentMatch = data.PreClose;
        }

        data.Differential = data.CurrentMatch - data.PreClose;
        data.DifferentialPersent = ((data.Differential / (data.PreClose == 0 ? 1 : data.PreClose)) * 100).toFixed(2);
        $(".gp-stockname").html(data.name);
        $(".gp-num").html(data.code);
        setColorValue(data.gp_price, $(".gp-price"), data.current_price);
        setColorValue(data.gp_price, $(".icon-status"), '<span>' + data.gp_price + '</span>');
        setColorValue(data.gp_price, $(".price"), data.gp_price);
        setColorValue(data.Differential, $(".gp-zf"), data.gp_zf + '%');
        $(".gp-open").html(data.open_price);
        $(".gp-preclose").html(data.PreClose);
        $(".gp-max").html(data.highest);
        $(".gp-min").html(data.lowest);
        $(".gp-maxprice").html(data.limit_up);
        $(".gp-minprice").html(data.limit_down);
        $(".gp-cjl").html(data.volume + "手");
        $(".gp-cje").html(data.turnover + "万");
        preclose = data.PreClose;
        //setColorValue(data.DifferentialPersent, $("#_newPrice"), parseFloat(data.CurrentMatch));
        //setColorValue(data.DifferentialPersent, $("#_newDiff"), data.CurrentMatch + "%");

        $(".stra-fr").show();

        var Handicap = new Array();
        var Handicap_sell = new Array();
        var Handicap_buy = new Array();
        for (var i in data.SellHandicap) {
            var cls = "black";
            if (data.SellHandicap[i].Price > data.PreClose) {
                cls = "red";
            } else if (data.SellHandicap[i].Price < data.PreClose) {
                cls = "green";
            }
            Handicap_sell.unshift({
                Index: "卖" + data.SellHandicap[i].Index,
                Price: data.SellHandicap[i].Price,
                Volume: convertWan(data.SellHandicap[i].Volume),
                cls: cls
            });
        }
        for (var i in data.BuyHandicap) {
            var cls = "black";
            if (data.BuyHandicap[i].Price > data.PreClose) {
                cls = "red";
            } else if (data.BuyHandicap[i].Price < data.PreClose) {
                cls = "green";
            }
            Handicap_buy.push({
                Index: "买" + data.BuyHandicap[i].Index,
                Price: data.BuyHandicap[i].Price,
                Volume: convertWan(data.BuyHandicap[i].Volume),
                cls: cls
            });
        }
        Handicap.unshift(Handicap_sell);
        Handicap.push(Handicap_buy);

        $("#stalls").html(template("templateStalls", {
            List: Handicap
        }));

        if (sysConfig.Multiple) {
            data.miniXYJ = Math.ceil(data.current_price / sysConfig.Multiple[0]) * 100;
        }
        priceData = data;
        $(".minus").css("z-index", "1");

        setTimeout(function () {
            getData(minute_k, "minute_k", code);
            GetQMarket(code);
        }, 3000);
    });
}

function convertWan(volume) {
    if (volume > 10000) {
        return (volume / 10000).toFixed(2) + "万";
    } else {
        return volume;
    }
};

//添加自选股
function AddSelfStock(elem) {
    if (stockCode.length == 6) {
        $.post("/strategy/strategy/addselfstock", {
            code: stockCode,
            name: $(".gp-stockname").html()
        }).done(function (result) {
            if (result.status) {
                $(elem).html('取消自选');
                $(elem).attr("onclick", "DelSelfStock(this)");
                layer.msg(result.message);
            } else {
                $(elem).html('取消自选');
                $(elem).attr("onclick", "DelSelfStock(this)");
                layer.msg(result.message);
            }
        });
    } else {
        layer.msg("请先选择股票");
    }
}

//添加自选股
function DelSelfStock(elem) {
    if (stockCode.length == 6) {
        $.post("/strategy/strategy/delselfstock", {
            code: stockCode,
            name: $(".gp-stockname").html()
        }).done(function (result) {
            if (result.status) {
                $(elem).html('添加自选');
                $(elem).attr("onclick", "AddSelfStock(this)");
                layer.msg(result.message);
            } else {
                $(elem).html('添加自选');
                $(elem).attr("onclick", "AddSelfStock(this)");
                layer.msg(result.message);
            }
        });
    } else {
        layer.msg("请先选择股票");
    }
}

//搜索===================
//创建策略===================

$(".trade-money .placeholder").click(function () {
    $(".trade-money .placeholder").removeClass("active");
    $(this).addClass("active");
    var value = $(this).html();
    $("#i-xyj").val(value);
    var current_price = priceData.current_price;
    //如果变换信用金，当前股票数量要被更新，信用金倍率里的数量（data）也要被更新
    var Multiple = $(".trade-num").find(".placeholder.active").html().replace('倍', '');
    var stockVolume = Math.floor(value * Multiple / current_price / 100) * 100;
    $("#stockNum").html(stockVolume);
    $("#multiple").html(Multiple);
    $.each($(".trade-num .placeholder"), function (i, e) {
        var each_multiple = $(e).html().replace('倍', '');
        var data_stockVolume = Math.floor(value * each_multiple / current_price / 100) * 100
        $(e).attr("data", data_stockVolume);
    });
    DoCounter();

});


$("#i-xyj").blur(function () {
    var xyj = $(this).val();
    xyj = parseInt(xyj / 100) * 100;
    if (xyj > 5000000) {
        $(this).val(5000000);
        layer.msg("确定投入这么多信用金吗，请联系管理员！");
    } else {
        $(this).val(xyj);
    }
    DoCounter();
})


function QueryConfig(end) {
    $.get("/strategy/strategy/querysysconf", function (result) {
        if (result.IsSuccess) {
            sysConfig = result.SuccessData[0];
            $("#i-uplimit").val(parseFloat(((1 + sysConfig.GainScale) * priceData.CurrentMatch)).toFixed(2));
            $("#i-ul-per").html(sysConfig.GainScale * 100 + '%').toString();

            $("#profit_proportion").html(sysConfig.profit_proportion + '%');
            if (end)
                end();
        } else {
            layer.msg("加载服务器配置失败");
        }
    });
};

function initStrategy(code) {
    if (sysConfig.GainScale == undefined) {
        QueryConfig(function () {
            StockPool();
//             initKChart();
        });
    } else {
        StockPool();//股票池风控
//         initKChart();//K线风控
    }
}

Date.prototype.Format = function (format) {
    var o = {
        "M+": this.getMonth() + 1, //month
        "d+": this.getDate(), //day
        "h+": this.getHours(), //hour
        "m+": this.getMinutes(), //minute
        "s+": this.getSeconds(), //second
        "q+": Math.floor((this.getMonth() + 3) / 3), //quarter
        "S": this.getMilliseconds() //millisecond
    }
    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}

// function initKChart() {

//     var startTime = new Date().Format("yyyyMMdd092900");
//     var hk = (stockCode.indexOf("6") == 0 ? "sse" : "szse") + stockCode;
//     $.post("/strategy/strategy/getkdata", { code: hk, startTime: startTime }, function (result) {
//         if (result.IsSuccess) {
//             krisk(JSON.parse(result.SuccessData));
//         }
//     });
// };

function translateDate(tb) {
    var year = tb.toString().substring(0, 4);
    var month = tb.toString().substring(4, 6);
    var day = tb.toString().substring(6, 8);
    var date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));
    return date.getTime();
}

var krisk = function (data) {
    var _data = data.Data[0];
    if (_data.length <= 0)
        return;
    var kdata = [];
    for (var i in _data) {
        kdata.push([
            translateDate(_data[i][0]),
            _data[i][1] / 100, //前收盘价
            _data[i][2] / 100, //开盘价
            _data[i][3] / 100, //收盘价
            _data[i][4] / 100, //最高价
            _data[i][5] / 100, //最低价
            _data[i][6], //成交量
            _data[i][7] //成交额
        ]);
    }
    if (kdata.length <= 0)
        return;
    //K线风控
    var fkdata = kdata.slice(0 - sysConfig.AmplitudeDay - 1);
    var fkzsj = 0;
    if (fkdata.length == 1)
        fkzsj = fkdata[0][1];//昨收价
    else
        fkzsj = fkdata[sysConfig.AmplitudeDay - 2][1];//昨收价
    var fkzgj = fkzsj;//最高价
    $.each(fkdata, function (i, value) {
        if (value[4] > fkzgj) {
            fkzgj = value[4];
        }
    });
    var fkzdj = fkzsj;//最低价
    $.each(fkdata, function (i, value) {
        if (value[5] < fkzdj) {
            fkzdj = value[5];
        }
    });
    if ((fkzgj - fkzsj) / fkzsj * 100 > sysConfig.AmplitudeScale) {
        isKlineRiskStock = true;
    }
    if ((fkzsj - fkzdj) / fkzsj * 100 > sysConfig.AmplitudeScale) {
        isKlineRiskStock = true;
    }
}

//获取股票池
var StockPool = function () {
    return false;
    $.get("/strategy/strategy/querystockversion", "", function (res) {
        if (res.IsSuccess) {
            var version = res.SuccessData;
            var pool = getStockPool(version);
            if (pool != null && pool.length > 0) {
                setPoolData(pool);
            } else {
                $.get("/strategy/strategy/QueryRiskStockPool", "", function (res) {
                    pool = res.SuccessData;
                    saveStockPool(res.SuccessData, version);
                    setPoolData(pool);
                });
            }
        } else {
            layer.msg("获取股票池版本失败");
        }
    });
}
var saveStockPool = function (value, version) {
    if (window.localStorage) {
        window.localStorage.setItem("version", version);
        window.localStorage.setItem("StockPool", JSON.stringify(value));
    }
}
var getStockPool = function (version) {
    if (version == parseInt(window.localStorage.getItem("version"))) {
        if (window.localStorage)
            return JSON.parse(window.localStorage.getItem("StockPool"));
        else
            return null;
    } else {
        return null;
    }
}
var setPoolData = function (pool) {
    var pools = new Array();
    $.each(pool, function (i, e) {
        var stock = e.StockList.filter(function (ee) {
            return ee.StockCode == stockCode
        });
        if (stock.length > 0) {
            pools.push(e);
        }
    });
    if (pools.length > 0) {
        var IsTrade = pools.filter(function (ee) {
            return ee.IsTrade == false;
        });
        if (IsTrade.length == 0) {
            pools.sort(function (a, b) {
                return a.Sort - b.Sort
            });
            sysConfig.IsTrade = true;
//             sysConfig.Money_scale = pools[0].Scale;

        } else {
            //禁止交易
            sysConfig.IsTrade = false;
            IsTrade.sort(function (a, b) {
                return a.Sort - b.Sort
            });
//             sysConfig.Money_scale = IsTrade[0].Scale == 0 ? 1 : IsTrade[0].Scale;
        }
    } else {
        //禁止交易
        sysConfig.IsTrade = false;
    }
}


// 计算股票倍数
function Multiple(price) {
    var current_price = priceData.current_price;
    if (current_price <= 0) {
        return false;
    }
    var priceCounter = new Array();
    for (var i in sysConfig.Multiple) {
        priceCounter[i] = new Array();
        priceCounter[i]['stockVolume'] = Math.floor(price * sysConfig.Multiple[i] / current_price / 100) * 100;
        priceCounter[i]['marketValue'] = parseInt(priceCounter[i]['stockVolume'] * current_price);
        priceCounter[i]['multiple'] = sysConfig.Multiple[i];
    }
    priceCounter[0]['class'] = "active";
    $.each($(".trade-num .placeholder"), function (i, e) {
        priceCounter[i]['class'] = "";
        if ($(e).hasClass("active")) {
            priceCounter[i]['class'] = "active";
        }
    });

    $("#s-number").html(template("templatePriceCounter", {
        List: priceCounter
    }));

    $(".trade-num .placeholder").click(function () {
        $(".trade-num .placeholder").removeClass("active");
        $(this).addClass("active");
        stockVolume = $(this).attr('data');
        $("#stockNum").html(stockVolume);
        $("#multiple").html($(this).attr('d'));
        iearn(stockVolume);
        initPriceBox(stockVolume);
    });

    if ($("#stockNum").html() == "") {
        stockVolume = priceCounter[0]['stockVolume'];
        $(".trade-num .placeholder").eq(0).trigger("click");
    } else {
        $.each($(".trade-num .placeholder"), function (i, e) {
            if ($(e).hasClass("active")) {
                stockVolume = $(this).attr('data');
            }
        });
    }
    $("#stockNum").html(stockVolume);
    iearn(stockVolume);

}

$(".trade-num .placeholder").eq(0).click();

// 最大可赚金额
function iearn(stockVolume) {
    if (parseFloat($("#i-uplimit").val()) > parseFloat(priceData.CurrentMatch)) $("#i-earn").html(((parseFloat($("#i-uplimit").val()) - parseFloat(priceData.CurrentMatch)) * stockVolume).toFixed(2) + '元'); //最大可以赚到金额

    var xy_data = $("#i-xyj").val();
    var stockVolume = parseInt($("#stockNum").html());
    var fee = (stockVolume * priceData.current_price * sysConfig.Fee / 10000).toFixed(2);
    if (parseFloat(fee) < parseFloat(sysConfig.Fee)) {
        fee = sysConfig.Fee;
    }
    $("#orderMoney").html(parseInt(xy_data) + parseFloat(fee));
    $("#i-fee").html(fee);

}

/*信用金计算*/
function DoCounter() {
    if (!$("#i-xyj").is(":focus") || fristDoCounter) {
        var xy_data = $("#i-xyj").val();

        xy_data = parseInt(xy_data);
        $(".trade-money .placeholder").removeClass("active");
        $.each($(".trade-money .placeholder"), function (i, e) {
            if ($(e).html() == xy_data.toString()) {
                $(e).addClass("active");
            }
        });
        if (xy_data < priceData.miniXYJ) {

            if (!fristDoCounter) {
                $(".trade-money .placeholder").removeClass("active");
                layer.msg('最少信用金为' + priceData.miniXYJ + '元');
            }
            $("#i-xyj").val(priceData.miniXYJ);
            xy_data = priceData.miniXYJ;
            Multiple(xy_data);
        } else {
//             xy_data = priceData.miniXYJ;
            Multiple(xy_data);
        }

        if (xy_data > 0 && xy_data % 100 == 0 && xy_data >= priceData.miniXYJ) //100的倍数小于10W
        {
            $("#i-xyj").attr("placeholder", "信用金为100的整数倍");
            var stockVolume = parseInt($("#stockNum").html());
            if (parseFloat($("#i-uplimit").val()) > parseFloat(priceData.CurrentMatch))
                $("#i-earn").html(((parseFloat($("#i-uplimit").val()) - parseFloat(priceData.CurrentMatch)) * stockVolume).toFixed(2) + '元'); //最大可以赚到金额
            //$("#orderMoney").html(parseInt($("#i-xyj").val()) - deductible;
            var resultMinValue = (((priceData.CurrentMatch * stockVolume) - (xy_data * sysConfig.LossScale)) / stockVolume).toFixed(2); //默认止损
            if (cacheData.StopLossPrice && cacheData.StopLossPrice > resultMinValue) //如果手动设置了止损价格或者止损价大于爆仓价格
            {
                resultMinValue = parseFloat(cacheData.StopLossPrice);
            }
            $("#i-downlimit").val(parseFloat(resultMinValue).toFixed(2));
            $("#i-dn-per").html(((priceData.CurrentMatch - resultMinValue) / priceData.CurrentMatch * 100).toFixed(2) + "%");
            fristDoCounter && initPriceBox(stockVolume);
            fristDoCounter = false;
            return true;
        } else {
            fristDoCounter = false;
            return false;
        }
    }
}

function initPriceBox(stVolume) {
    var maxWin = ((1 + sysConfig.GainScale) * priceData.CurrentMatch).toFixed(2);
    var stockVolume = isNaN(stVolume) ? parseInt($("#stockNum").html()) : stVolume;

    if (isNaN(stockVolume)) {
        DoCounter();
        initPriceBox();
        return;
    }

    priceData.miniXYJ = Math.ceil(priceData.current_price / sysConfig.Multiple[0]) * 100;
    fristDoCounter && $("#i-xyj").val(priceData.miniXYJ);
    var resultMaxValue = maxWin;
    if (cacheData.StopWinPrice && cacheData.StopWinPrice < maxWin) //如果手动设置了止损价格或者止损价大于爆仓价格
    {
        resultMaxValue = parseFloat(cacheData.StopWinPrice);
    }
    $("#i-uplimit").val(parseFloat(resultMaxValue).toFixed(2));
    $("#i-earn").html(((parseFloat(maxWin) - parseFloat(priceData.CurrentMatch)) * stockVolume).toFixed(2) + '元'); //最大可以赚到金额

    if (_stopWinBox) {
        _stopWinBox.setExtremes(999, priceData.CurrentMatch);//不限制最高止盈价
    } else {
        _stopWinBox = $("#stopWinBox").WanSpinner({
            maxValue: 999,//不限制最高止盈价
            minValue: parseFloat(priceData.CurrentMatch).toFixed(2),
            step: 0.01,
            inputWidth: 100,
            exceptionFun: function (EXCEPTION) {
                if (EXCEPTION == 1) {
                    layer.msg("超过最大止盈价(￥" + 999 + ")");
                } else if (EXCEPTION == -1) {
                    layer.msg("超过最小止盈价(￥" + priceData.CurrentMatch + ")");
                }
            },
            valueChanged: function (el, val) {
                cacheData.StopWinPrice = parseFloat(val).toFixed(2);
                stockVolume = parseInt($("#stockNum").html());
                $("#i-earn").html(((parseFloat(val) - parseFloat(priceData.CurrentMatch)) * stockVolume).toFixed(2) + '元'); //最大可以赚到金额
                $("#i-ul-per").html(((parseFloat(val) - parseFloat(priceData.CurrentMatch)) / parseFloat(priceData.CurrentMatch) * 100).toFixed(2) + "%");
            }
        });
    }


    //priceData,sysConfig 计算最大止盈价格
    var orgMoney = $("#i-xyj").val(); //$("#orderMoney").html(); //最小信用金
    //(（买入价*股票数量）-（信用金*止损比例）)/股票数量*100%
    bangPrice = (((priceData.CurrentMatch * stockVolume) - (orgMoney * sysConfig.LossScale)) / stockVolume).toFixed(2); //爆仓价


//     var minPrice = parseFloat((buy_price*number-credit*sysConfig.LossScale)/number).toFixed(2);


    if (bangPrice < 0)
        bangPrice = 0;
    var resultMinValue = bangPrice; //默认止损
    if (cacheData.StopLossPrice && cacheData.StopLossPrice > bangPrice) //如果手动设置了止损价格或者止损价大于爆仓价格
    {

        resultMinValue = parseFloat(cacheData.StopLossPrice);
    }

    $("#i-downlimit").val(parseFloat(resultMinValue).toFixed(2));
    $("#i-dn-per").html(((priceData.CurrentMatch - resultMinValue) / priceData.CurrentMatch * 100).toFixed(2) + "%");
    if (_stopLossBox) {
        _stopLossBox.setExtremes(priceData.CurrentMatch, bangPrice);
    } else {
        _stopLossBox = $("#stopLossBox").WanSpinner({
            maxValue: parseFloat(priceData.CurrentMatch).toFixed(2),
            minValue: bangPrice,
            step: 0.01,
            inputWidth: 100,
            exceptionFun: function (EXCEPTION) {
                if (EXCEPTION == 1) {
                    layer.msg("超过最大止损价(￥" + priceData.CurrentMatch + ")");
                } else if (EXCEPTION == -1) {
                    layer.msg("超过最低止损价(￥" + bangPrice + ")");
                }
            },
            valueChanged: function (el, val) {
                cacheData.StopLossPrice = parseFloat(val).toFixed(2);
                $("#i-dn-per").html(((priceData.CurrentMatch - val) / priceData.CurrentMatch * 100).toFixed(2) + "%");
            }
        });
    }
};

function changeStockVolumn() {
    var nowNumber = $("#stockNum").html();
    var maxNumber = $("#stockNum").attr("data-max");
    var boxHtml = template("boxChangeVolumn", {
        number: nowNumber,
        maxNumber: maxNumber,
        marketValue: parseFloat(nowNumber) * parseFloat(priceData.CurrentMatch)
    });
    layer.open({
        title: [
            '修改股票数量',
            'background-color: #FF4351; color:#fff;height:40px; line-height:40px;'
        ],
        type: 1,
        btn: ['提交', '关闭'],
        content: boxHtml,
        yes: function (index) {
            cacheData.stockVolume = cacheData.TempStockVolume;
            $("#stockNum").html(cacheData.stockVolume);
            DoCounter();
            layer.close(index);
            initPriceBox();
        }
    });
    $(".wan-spinner").WanSpinner({
        maxValue: maxNumber,
        minValue: 100,
        step: 100,
        inputWidth: 100,
        exceptionFun: function (EXCEPTION) {
            if (EXCEPTION == 1) {
                layer.msg("超过最大值");
            } else if (EXCEPTION == -1) {
                layer.msg("超过最小值");
            }
        },
        valueChanged: function (el, val) {
            cacheData.TempStockVolume = val;
            var ckMarketValue = parseFloat(priceData.CurrentMatch) * parseFloat(val);
            $("#ckMarketValue").html(ckMarketValue);
        }
    });
};

function submit() {
    if (!DoCounter('submit'))//计算信用金
    {
        layer.msg('信用金输入有误');
        return false;
    }

    if (sysConfig.IsTrade == false) {
        layer.msg('非股票池股票禁止交易');
        return false;
    }
    var PositionCount = 1;//策略数
    if (PositionCount == 0 && !hasReadXy) {
        $(".imgbox").show();
        return false;
    }
    var isRead = $("#readxy").is(":checked");
    if (isRead) {
        $("#submit").attr('disabled', true);

        layer.msg('正在拼命提交策略', {
            icon: 16,
            shade: 0.01
        });
        var orgMoney = parseFloat($("#i-xyj").val()); //原始资金
        var newMoney = $("#orderMoney").html();
        var type = $("#type").val();

        if (isKlineRiskStock) {
            layer.closeAll();
            layer.msg(sysConfig.AmplitudeDay + '天内涨跌幅大于' + sysConfig.AmplitudeScale + "%的风险股票禁止交易");
            $("#submit").attr('disabled', false);
            return false;
        }

        var balance = $("#balance").html();
        if (parseInt(balance) < parseInt(newMoney)) {
            layer.closeAll();
            layer.msg('余额不足，请先充值');
            $("#submit").attr('disabled', false);
            return;
        }
        var PositionMoney = 0;//持仓总信用金
        if (PositionMoney + orgMoney > 300000) {
            layer.closeAll();
            layer.msg('总持仓最高30万信用金');
            $("#submit").attr('disabled', false);
            return;
        }

        var stockNumber = parseFloat($("#stockNum").html()); //股票数量
        if (orgMoney > parseFloat(priceData.CurrentMatch) * stockNumber) {
            layer.closeAll();
            layer.msg('信用金不能大于当前市值');
            $("#submit").attr('disabled', false);
            return;
        }
        if (stockNumber % 100 != 0 || stockNumber < 100) {
            layer.closeAll();
            layer.msg('股票数量需要大于100且是100的整数倍');
            $("#submit").attr('disabled', false);
            return;
        }
        var stockCode = priceData.StockCode; //股票代码
        var stockName = priceData.StockName; //股票名称
        var upLimit = parseFloat($("#i-uplimit").val()); //止盈
        if (upLimit <= parseFloat(priceData.CurrentMatch)) {
            layer.closeAll();
            layer.msg('止盈价不能低于或等于最新价');
            $("#submit").attr('disabled', false);
            return;
        }
        var downLimit = parseFloat($("#i-downlimit").val()); //止损
        if (downLimit >= parseFloat(priceData.CurrentMatch)) {
            layer.closeAll();
            layer.msg('止损价不能大于或等于最新价');
            $("#submit").attr('disabled', false);
            return;
        }
        if (upLimit <= downLimit) {
            layer.closeAll();
            layer.msg('止盈价不能低于或等于止损价');
            $("#submit").attr('disabled', false);
            return;
        }
        var multiple = $("#multiple").html();
        var param = {
            credit: orgMoney,
            StockCode: priceData.code,
            StockNum: stockNumber,
            surplus_price: upLimit,
            stop_price: downLimit,
            IsDelay: IsDelay,
            type: type,
            multiple: multiple,
        };
        $.post("/strategy/trade", param, function (result) {
            layer.closeAll()
            if (!result.status) {
                $("#submit").attr('disabled', false);
                layer.msg(result.message);
            } else {
                layer.msg('策略提交成功，正在为您跳转...', {
                    end: function () {
                        if (type == 3) {
                            var url = "/wap.php/strategy/match/create.html";
                        } else {
                            var url = "/wap.php/strategy/index.html";
                        }
                        window.location.href = url;
                    }
                });
            }
        });
    } else {
        layer.msg('请先同意协议条款');
    }
}


// 相关协议
$("#clxy").on("click", function () {
    var xyj = $("#i-xyj").val();
    var num = $("#stockNum").html();
    var tempPact = {
        title: syspact[TradeModel].title,
        content: syspact[TradeModel].content
            .replace(/{信用金}/g, "<u>" + xyj + "</u>")
            .replace(/{信用金大写}/g, "<u>" + DX(xyj) + "</u>")
            .replace(/{总市值}/g, "<u>" + (priceData.CurrentMatch * num).toFixed(0) + "</u>")
            .replace(/{总市值大写}/g, "<u>" + DX(parseInt(priceData.CurrentMatch * num)) + "</u>")
    };
    layer.open({
        title: tempPact.title,
        area: ['90%', '60%'], //宽高
        content: "<div style='line-height: 24px;'>" + tempPact.content + "</div>",
        btn: ['同意并继续', '取消',],
        btnAlign: 'c',
        yes: function () {
            var el = $('#readxy');
            el.checked = true;
            hasReadXy = true;
            layer.closeAll();
        },
        btn2: function () {
            var el = $('#readxy');
            el.checked = false;
            hasReadXy = false;
            layer.closeAll();
        }
    });
});

function DX(n) {
    if (!/^(0|[1-9]\d*)(\.\d+)?$/.test(n))
        return "数据非法";
    var unit = "千百拾亿千百拾万千百拾元角分", str = "";
    n += "00";
    var p = n.indexOf('.');
    if (p >= 0)
        n = n.substring(0, p) + n.substr(p + 1, 2);
    unit = unit.substr(unit.length - n.length);
    for (var i = 0; i < n.length; i++)
        str += '零壹贰叁肆伍陆柒捌玖'.charAt(n.charAt(i)) + unit.charAt(i);
    return str.replace(/零(千|百|拾|角)/g, "零").replace(/(零)+/g, "零").replace(/零(万|亿|元)/g, "$1").replace(/(亿)万|壹(拾)/g, "$1$2").replace(/^元零?|零分/g, "").replace(/元$/g, "元整");
}

//创建策略===================


function LoadNewPrice(_data) {
    stockcode = "";
    $.each(_data, function (i, e) {
        stockcode = stockcode + e.StockCode + ",";
    });
    $.post("/strategy/strategy/stocksinfo", {stockcode: stockcode}).done(function (result) {
        if (result.IsSuccess) {
            var MarketValue = 0;//总市值
            var AllProfit = 0;//总浮盈
            var AllXyj = 0;//总信用金
            $.each(result.SuccessData, function (i, e) {
                $.each($(".stra-cc[data-code=" + e.StockCode + "]"), function (j, d) {
                    setColorValue(e.DifferentialPersent, $(d).find("i[data-value=NewPrice]"), e.NewPrice);
                    setColorValue(e.DifferentialPersent, $(d).find("i[data-value=DifferentialPersent]"));
                    var buyprice = $(d).data("buyprice");
                    var stocknum = $(d).data("stocknum");
                    var stockprice = $(d).data("stockprice");
                    setColorValue((e.NewPrice - buyprice) * stocknum, $(d).find("i[data-value=FloatProfit]"));
                    setColorValue((e.NewPrice - stockprice) * stocknum, $(d).find("i[data-value=Profit]"));
                    AllProfit += (e.NewPrice - buyprice) * stocknum;
                    MarketValue += e.NewPrice * stocknum;
                    AllXyj += _data[j].Money;
                });
            });

            $(".stra-top").find("span[data-value=total]").html((parseFloat($("#Balance").html()) + AllXyj + AllProfit).toFixed(2));

            $(".stra-top").find("span[data-value=MarketValue]").html(MarketValue.toFixed(2));
            setColorValue(AllProfit, $(".stra-top").find("span[data-value=AllProfit]"));
        } else {
            layer.msg(result.ErrorMessage);
        }
    });
}

var saleStrategy = function (orders) {
    var $strategy = $("#" + orders);
    var StockName = $strategy.data("stockname");
    var StockCode = $strategy.data("code");
    layer.open({
        title: '卖出策略',
        content: '<div class="text-center">是否要卖出策略&nbsp;' + StockName + '(' + StockCode + ')</div>',
        btn: ['确定', '取消'],
        area: ['80%', '222px'],
        yes: function (index) {
            layer.close(index);
            layer.msg("正在拼命提交", {icon: 16, shade: 0.3});
            var param = {
                orders: orders,
                stype: 2
            };
            $.post("/strategy/close", param,
                function (data) {
                    layer.closeAll();
                    var succ = eval('(' + data + ')');
                    if (succ.status) {
                        layer.msg("提交成功", {
                            end: function () {
                                window.location.reload();
                            }
                        });
                    } else {
                        layer.msg(succ.message);
                    }
                }
            ).fail(function () {
                layer.msg("连接服务器失败！");
            });
        }
    });
};

var changeGain = function (surplus_price, number, orders, stcode, price) {
    var content = $("#changeGain").html();
    content = template("changeGain", {
        surplus_price: parseFloat(surplus_price).toFixed(2),
        number: number,
        strategyCode: orders
    });
    layer.open({
        title: [
            '修改止盈价',
            'background-color: #FF4351; color:#fff;height:40px; line-height:40px;'
        ],
        btn: ['提交', '关闭'],
        content: content,
        area: ['80%', '224px'],
        yes: function (index) {
            var winPrice = $("#zyprice").val();
            if (parseFloat(price) >= parseFloat(winPrice)) {
                layer.msg("止盈价不能低于或等于最新价" + price);
                return false;
            }
            layer.msg("正在拼命提交", {icon: 16, shade: 0.3});
            $.post("/strategy/strategy/UpdateGainLoPrice", {
                orders: orders,
                Price: winPrice,
                UpdatePositionType: 1
            }, function (succ) {
                if (succ.IsSuccess) {
                    layer.closeAll();
                    layer.msg("修改成功", {
                        end: function () {
                            window.location.reload();
                        }
                    });
                } else {
                    layer.closeAll();
                    layer.msg(succ.ErrorMessage);
                }
            }).fail(function () {
                layer.msg("连接服务器失败！");
            });
        }
    });

    _stopWinBox = $("#stop_zyprice").WanSpinner({
        step: 0.01,
        inputWidth: 100,
    });
//  stockCode="";
//  fristDoCounter= true;
//  selectStock(stcode,number);

};


var changeLossGain = function (buy_price, stop_price, number, orders, credit, stcode, price) {
    var content = $("#changeLossGain").html();
    var minPrice = parseFloat((buy_price * number - credit * sysConfig.LossScale) / number).toFixed(2);
//     var minPrice = bangPrice;
    content = template("changeLossGain", {
        stop_price: parseFloat(stop_price).toFixed(2),
        number: number,
        minPrice: minPrice,
        strategyCode: orders,
        credit: credit,
        buy_price: buy_price,
    });
    layer.open({
        title: [
            '修改止损价',
            'background-color: #FF4351; color:#fff;height:40px; line-height:40px;'
        ],
        btn: ['提交', '关闭'],
        content: content,
        area: ['80%', '300px'],
        yes: function (index) {
            var lossPrice = $("#zsprice").val();
            var bcxyj = $("#bcxyj").val();
            if (parseFloat(bcxyj) > parseFloat($("#Balance").html())) {
                layer.msg("余额不足,请充值");
                return false;
            }
            if (lossPrice <= 0) {
                layer.msg("止损价应该大于零！");
                return false;
            }

//             if (parseFloat(lossPrice) < parseFloat(minPrice)) {
//                 layer.msg("止损价低于最小止损价"+minPrice);
//                 return false;
//             }
            if (parseFloat(lossPrice) >= parseFloat(price)) {
                layer.msg("止损价大于当前价" + price);
                return false;
            }
            layer.msg("正在拼命提交", {icon: 16, shade: 0.3});
            $.post("/strategy/strategy/UpdateGainLoPrice", {
                orders: orders,
                Price: lossPrice,
                Money: bcxyj,
                UpdatePositionType: 0,
                StrategyType: $("#type").val(),
            }, function (succ) {
                if (succ.IsSuccess) {
                    layer.closeAll();
                    layer.msg("修改成功", {
                        end: function () {
                            window.location.reload();
                        }
                    });
                } else {
                    layer.closeAll();
                    layer.msg(succ.ErrorMessage);
                }
            }).fail(function () {
                layer.msg("连接服务器失败！");
            });
        }
    });
    bcxyj(stop_price, buy_price, number, minPrice, credit);
    _stopLossBox = $("#stop_zsprice").WanSpinner({
        step: 0.01,
        inputWidth: 60,
    });
//  $(".pminus").css("z-index", "-1");
//  stockCode="";
//  fristDoCounter= true;
//  selectStock(stcode,number);
};

var changePrice = function (code, minPrice, stop_price, buy_price, number, credit, step) {
//     $("#zsprice").val(parseFloat($("#zsprice").val()).toFixed(2));
//     var lossPrice = parseFloat($("#zsprice").attr("data-loss")); //原来的止损价
    var value = parseFloat($("#zsprice").val()) + step; //新止损价
//     var StockNum = $("#" + code).attr("StockNum");
//     var Money = parseInt($("#" + code).attr("Money"));
    if (value < minPrice) {
        bcxyj(value, buy_price, number, minPrice, credit);
    } else {
        $("#bcxyj").val(0);
    }
};

//设置个股访问量===================
function setVisits(code) {
    $.post("/strategy/strategy/setVisits", {code: code,});
}


