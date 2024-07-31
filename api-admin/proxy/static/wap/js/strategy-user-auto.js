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
    SimulationTrade: 1, //模拟盘
    FrimTrade: 2, //实盘
    match: 3, //大赛
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
var deductible = 0;//
var cacheData = {};//缓存对象
var _stopWinBox, _stopLossBox;
var bangPrice;
var PositionData;
var IsDelay = 1;

// 切换
$(".switch-yc").click(function () {
    $(this).toggleClass("onswitch-yc");
    if ($(this).hasClass('onswitch-yc')) {
        IsDelay = 0;
    } else {
        IsDelay = 1;
    }
});

function QueryConfig(end) {
    $.get("/strategy/strategy/querysysconf", function (result) {
        if (result.IsSuccess) {
            sysConfig = result.SuccessData[0];
            if (end)
                end();
        } else {
            layer.msg("加载服务器配置失败");
        }
    });
};
QueryConfig();
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


//验证是否为空  v2   返回 空为true  不为空着false
function emptyempty_v2(mixed_var) {
    var key;
    if (mixed_var == "" || mixed_var == 0 || mixed_var == "0" || mixed_var == null || mixed_var == false || mixed_var == '' || typeof (mixed_var) == "undefined") {
        return true;
    }

    if (typeof mixed_var == 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }

    return false;
}


function delauto(id, order, StockName, StockCode) {

    layer.open({
        title: '撤销委托',
        content: '<div class="text-center">是否要撤销委托&nbsp;' + StockName + '(' + StockCode + ')</div>',
        btn: ['确定', '取消'],
        area: ['400px', '222px'],
        yes: function (index) {


            $.get("/member/automatictrading/delauto", {id: id, order: order}).done(function (result) {
                console.log(JSON.stringify(result))
                if (result.IsSuccess) {

                    layer.msg(result.msg);
                    location.reload();
                } else {

                    layer.msg(result.ErrorMessage);
                    return result.status;
                }
            });
        }
    });


}

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
                UpdatePositionType: 0
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
$("#wtmc").click(function () {
    var orders = $("#orders").val();
    var price = $("#entrust_price").val();
    if (emptyempty_v2(orders) || emptyempty_v2(price)) {
        alert("请检查漏填项");
        return false
    }
    $.post("/member/automatictrading/manualdelegation", {
        orders: orders, type: 2,
        price: price
    }, function (succ) {
        if (succ.IsSuccess) {
            layer.closeAll();
            layer.msg("委托成功");
            location.reload()
        } else {
            layer.msg(succ.ErrorMessage);
        }
    }).fail(function () {
        layer.msg("连接服务器失败！");
    });
})

$("#wtmr").click(function () {
    var StockCode = $("#StockCode").val();
    var StockNum = $("#StockNum").val();
    var StockName = $("#StockName").val();
    var price = $("#price").val();

    if (emptyempty_v2(StockCode) || emptyempty_v2(StockNum) || emptyempty_v2(price)) {
        alert("请检查漏填项");
        return false
    }
    $.post("/member/automatictrading/manualdelegation", {
        StockCode: StockCode,
        StockNum: StockNum, StockName: StockName,
        type: 1,
        price: price
    }, function (succ) {
        if (succ.IsSuccess) {
            layer.closeAll();
            layer.msg("委托成功");
            location.reload()
        } else {
            layer.msg(succ.ErrorMessage);
        }
    }).fail(function () {
        layer.msg("连接服务器失败！");
    });
})

function timetrans(date) {
    var date = new Date(date * 1000);//如果date为10位不需要乘1000
    var Y = date.getFullYear() + '-';
    var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
    var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + '';
    var h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
    var m = (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
    var s = (date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds());
    return Y + M + D;
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
            };
            $.post("/strategy/close", param,
                function (data) {
                    layer.closeAll();
                    var succ = eval('(' + data + ')');
                    if (succ.status) {
                        layer.msg("提交成功", {
                            end: function () {
                                getPosition();
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
// 筛选
var first_load = true;

function setFilterItem(sfilter_time) {
//     (first_load == true) && (sfilter_time[0] = sfilter_time[1]);
    var filter = "";
    filter += '<div class="weui-cells__title">快捷筛选</div>'
    filter += '<div class="weui-flex celuo-filter">'
    if ((sfilter_time[1] - sfilter_time[0]) / 3600 / 24 / 365 / 2 >= 1 || first_load == true) {
        first_load = false;
        filter += '<div class="weui-flex__item"><div class="placeholder active" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 365 * 2) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">全部</div></div>'
    } else {
        filter += '<div class="weui-flex__item"><div class="placeholder" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 365 * 2) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">全部</div></div>'
    }
    if ((sfilter_time[1] - sfilter_time[0]) / 3600 / 24 / 30 == 1) {
        filter += '<div class="weui-flex__item"><div class="placeholder active" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 30) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">近1个月</div></div>'
    } else {
        filter += '<div class="weui-flex__item"><div class="placeholder" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 30) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">近1个月</div></div>'
    }
    if ((sfilter_time[1] - sfilter_time[0]) / 3600 / 24 / 91 == 1) {
        filter += '<div class="weui-flex__item"><div class="placeholder active" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 91) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">近3个月</div></div>'
    } else {
        filter += '<div class="weui-flex__item"><div class="placeholder" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 91) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">近3个月</div></div>'
    }
    if ((sfilter_time[1] - sfilter_time[0]) / 3600 / 24 / 365 == 1) {
        filter += '<div class="weui-flex__item"><div class="placeholder active" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 365) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">近1年</div></div>'
    } else {
        filter += '<div class="weui-flex__item"><div class="placeholder" data-filter-time="' + (parseInt(Date.parse(new Date())) / 1000 - 3600 * 24 * 365) + '-' + parseInt(Date.parse(new Date())) / 1000 + '">近1年</div></div>'
    }
    filter += '</div>'
    filter += '<div class="weui-cells__title">日期范围</div>'
    filter += '<div class="weui-flex celuo-filter">'
    filter += '<div class="weui-flex__item"><div class="placeholder"><input name="filter_time_from" class="weui-input date-picker-input" id="dateStart" type="date" value="' + timetrans(sfilter_time[0]) + '" ></div></div>'
    filter += '<div class="weui-flex__item celuo-filter-middle"><div class="placeholder grayb5b5b5">—</div></div>'
    filter += '<div class="weui-flex__item"><div class="placeholder"><input name="filter_time_to" class="weui-input date-picker-input" id="dateEnd" type="date" value="' + timetrans(sfilter_time[1]) + '" ></div></div>'
    filter += '<div id="time-container"></div>'
    filter += '</div>'
    filter += '<div class="button_sp_area celuo-button_sp_area">'
    filter += '<a href="javascript:;" class="weui-btn weui-btn_mini weui-btn_primary close-popup">取消</a>'
    filter += '<a href="javascript:;" id="filter_submit" class="weui-btn weui-btn_mini weui-btn_default close-popup">确定</a>'
    filter += '</div>';
    $("#filter").html(filter);

    // 筛选
//     var datePicker = $(".date-picker-input");
//     datePicker.daterangepicker({
//                 singleDatePicker: true,
//                 locale: {
//                     format: 'YYYY-MM-DD'
//                 }
//             });
    setFiletime();
    $(".celuo-filter:first .weui-flex__item").click(function () {
        var item = $(this);
        item.children(".placeholder").addClass("active");
        item.siblings().children(".placeholder").removeClass("active");
        setFiletime();
    });

    $("#filter_submit").click(function () {
        $("#list").html('');
        $("#list1").html('');
        filter_time = Date.parse($("input[name='filter_time_from']").val()) / 1000 + '-' + Date.parse($("input[name='filter_time_to']").val()) / 1000;
        getPosition(1, pagesize, filter_time, 0);
        positionHistorylist(1, pagesize, filter_time, 0);
    })

    $("input[name='filter_time_to']").on("change", function () {
        var startTimeValue = $("input[name='filter_time_from']").val();
        var endTimeValue = $(this).val();

        var startTimeStamp = Date.parse(startTimeValue);
        var endTimeStamp = Date.parse(endTimeValue);

//                 first_load=true;                
        if (endTimeStamp < startTimeStamp) {
            startTimeValue = $(this).val();
            endTimeValue = $("input[name='filter_time_from']").val();
            $(this).val(endTimeValue);
            $("input[name='filter_time_from']").val(startTimeValue)
        }

    });

}

function setFiletime() {
    if ($(".celuo-filter .weui-flex__item").find(".active").attr("data-filter-time") == undefined) return false;
    filter_time_arr = $(".celuo-filter .weui-flex__item").find(".active").attr("data-filter-time").split('-');
    var startTimeValue = timetrans(filter_time_arr[0]);
    var endTimeValue = timetrans(filter_time_arr[1]);
    filter_time = (Number(filter_time_arr[0]) + 3600 * 24) + '-' + (Number(filter_time_arr[1]) + 3600 * 24);
    $("input[name='filter_time_from']").val(startTimeValue);
    $("input[name='filter_time_to']").val(endTimeValue);
}
