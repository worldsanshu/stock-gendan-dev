define(["jquery", "easy-admin", "echarts", "echarts-theme"], function ($, ea, echarts, undefined) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'statistics.consolidatedStatement/index',
    };
    var laydate = layui.laydate;
    var Controller = {

        index: function () {
            $(function () {
                var iniTime = $('#iniTime').attr('data-iniTime');
                laydate.render({
                    elem: '#consultProfitAndLossTime', //指定元素
                    type: 'date',
                    range: true,
                    value: iniTime,
                    change: function (value, date, endDate) {
                        // console.log(value); //得到日期生成的值，如：2017-08-18
                        // console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                        // console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                        loadConsultProfitAndLoss(value);
                    }
                });
                laydate.render({
                    elem: '#userStatisticsTime', //指定元素
                    type: 'date',
                    range: true,
                    value: iniTime,
                    change: function (value, date, endDate) {
                        //console.log(value); //得到日期生成的值，如：2017-08-18
                        // console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                        // console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                        loadUserStatistics(value);
                    }
                });
                laydate.render({
                    elem: '#userRechargeWithdrawStatisticsTime', //指定元素
                    type: 'date',
                    range: true,
                    value: iniTime,
                    change: function (value, date, endDate) {
                        //console.log(value); //得到日期生成的值，如：2017-08-18
                        // console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                        // console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                        loadUserRechargeWithdrawStatistics(value);
                    }
                });
                laydate.render({
                    elem: '#consultStatisticsTime', //指定元素
                    type: 'date',
                    range: true,
                    value: iniTime,
                    change: function (value, date, endDate) {
                        //console.log(value); //得到日期生成的值，如：2017-08-18
                        // console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                        // console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                        loadConsultStatistics(value);
                    }
                });

                $('i#platformProfitLossNotice').on({
                    mouseenter: function () {
                        var that = this;
                        content = '<div><div class="tishi_size">平台累计盈亏 = 平台资金池 - 会员净资产 - 累计返佣</div></div>';
                        tips = layer.tips(content, that, {tips: [2, '#000'], time: 0, area: 'auto', maxWidth: 700});
                    },
                    mouseleave: function () {
                        layer.close(tips);
                    }
                });
                $('i#totalConsultNetAssetsNotice').on({
                    mouseenter: function () {
                        var that = this;
                        content = '<div><div class="tishi_size">合约净资产 =（每个未结算合约总值 - 每个未结算合约总配资资金）之和;</div><div>某个未结算合约净资产小于等于0时，此合约的净资产为0</div></div>';
                        tips = layer.tips(content, that, {tips: [2, '#000'], time: 0, area: 'auto', maxWidth: 700});
                    },
                    mouseleave: function () {
                        layer.close(tips);
                    }
                });
                $('i#totalPlatformMoneyNotice').on({
                    mouseenter: function () {
                        var that = this;
                        content = '<div><div class="tishi_size">平台资金池  = 会员总充值 + 人工转入 - 会员总提现 - 人工转出</div></div>';
                        tips = layer.tips(content, that, {tips: [2, '#000'], time: 0, area: 'auto', maxWidth: 700});
                    },
                    mouseleave: function () {
                        layer.close(tips);
                    }
                });
                $('i#totalNetAssetsNotice').on({
                    mouseenter: function () {
                        var that = this;
                        content = '<div><div class="tishi_size">会员净资产 = 钱包余额 + 提现冻结 + 合约净资产 - 充值返现冻结 </div></div>';//
                        tips = layer.tips(content, that, {tips: [2, '#000'], time: 0, area: 'auto', maxWidth: 700});
                    },
                    mouseleave: function () {
                        layer.close(tips);
                    }
                });
                $('i.consultSettleProfitLossNotice').on({
                    mouseenter: function () {
                        var that = this;
                        content = '<div><div class="tishi_size">合约结算盈亏 = 结算资金 + 累计提盈 - 保证金 (申请+扩大+追加)</div></div>';//
                        tips = layer.tips(content, that, {tips: [2, '#000'], time: 0, area: 'auto', maxWidth: 700});
                    },
                    mouseleave: function () {
                        layer.close(tips);
                    }
                });
                $('i.activeUserNotice').on({
                    mouseenter: function () {
                        var that = this;
                        content = '<div><div class="tishi_size">活跃用户：平台一段时间内有过某些有效操作的用户\n' +
                            '\n' +
                            '活跃用户=充值提现用户（已成功的）+合约用户（申请+扩大+追加，已成功 的）+买卖股票用户;\n' +
                            '\n' +
                            '重复用户记为一个用户</div></div>';//
                        tips = layer.tips(content, that, {tips: [2, '#000'], time: 0, area: 'auto', maxWidth: 700});
                    },
                    mouseleave: function () {
                        layer.close(tips);
                    }
                });

                function loadConsultProfitAndLoss(value) {
                    $.ajax({
                        url: "getSettleContractProfitAndLoss",
                        type: 'post',
                        data: {'time_range': value},
                        success: function (data) {
                            //console.log(data);
                            if (data.state == 1) {
                                $('#consultProfitAndLoss').html(data.data.list.total_settle_consult_profit + '元');
                                $('#totalExpandInterest').html(data.data.list.total_expand_interest + '元');
                                $('#totalApplyInterest').html(data.data.list.total_apply_interest + '元');
                                $('#totalDeferInterest').html(data.data.list.total_defer_interest + '元');
                                loadEchartsConsultProfitAndLoss(data.data.statistics_time_list, data.data.settle_consult_profit_list,
                                    data.data.defer_interest_list, data.data.rebate_interest_list, data.data.expand_interest_list)
                            } else {
                                $alert(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                function getTodaySettleContractProfitAndLoss() {
                    $.ajax({
                        url: "getTodaySettleContractProfitAndLoss",
                        type: 'post',
                        data: {},
                        success: function (data) {
                            // console.log(data);
                            if (data.state == 1) {
                                $('#todaySettleConsultProfit').html(data.data.today_settle_consult_profit + '元');
                                $('#todayExpandInterest').html(data.data.today_expand_interest + '元');
                                $('#todayApplyInterest').html(data.data.today_apply_interest + '元');
                                $('#todayDeferInterest').html(data.data.today_defer_interest + '元');
                            } else {
                                console.log(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                /**
                 * 报表功能
                 */
                function loadEchartsConsultProfitAndLoss(consultProfitDataTime, $settle_consult_profit_list, $defer_interest_list, $rebate_interest_list, $expand_interest_list) {
                    var echartsRecords = echarts.init(document.getElementById('echarts-consultProfitAndLoss'), 'walden');
                    //var consultProfitDataTime = ['周一', '周二', '周三', '周四', '周五', '周六', '周日'];
                    var optionRecords = {
                        // xAxis: {
                        //     type: 'category',
                        //     data: ['A', 'B', 'C']
                        // },
                        // yAxis: {
                        //     type: 'value'
                        // },
                        // series: [
                        //     {
                        //         data: [120, 200, 150],
                        //         type: 'line'
                        //     }
                        // ],
                        title: {
                            // text: '访问统计'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['合约结算盈亏', '扩大利息', '申请利息', '续期利息']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: consultProfitDataTime
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                name: '合约结算盈亏',
                                type: 'line',
                                stack: '总量',
                                data: $settle_consult_profit_list
                            },
                            {
                                name: '扩大利息',
                                type: 'line',
                                stack: '总量',
                                data: $defer_interest_list
                            },
                            {
                                name: '申请利息',
                                type: 'line',
                                stack: '总量',
                                data: $rebate_interest_list
                            },
                            {
                                name: '续期利息',
                                type: 'line',
                                stack: '总量',
                                data: $expand_interest_list
                            },
                        ]
                    };
                    echartsRecords.setOption(optionRecords);
                    window.addEventListener("resize", function () {
                        echartsRecords.resize();
                    });
                }

                function loadUserStatistics(value) {
                    $.ajax({
                        url: "userStatistics",
                        type: 'post',
                        data: {'time_range': value},
                        success: function (data) {
                            // console.log(data);
                            if (data.state == 1) {
                                $('#totalUserWithdrawRecharge').html(data.data.list.total_user_withdraw_recharge);
                                $('#totalUserApplyConsult').html(data.data.list.total_user_apply_consult);
                                $('#totalRegisterNum').html(data.data.list.total_register_num);
                                $('#totalBuySell').html(data.data.list.total_buy_sell);
                                $('#totalActiveUser').html(data.data.list.total_active_user);
                                loadEchartsUserStatistics(data.data.statistics_time_list, data.data.active_user_list,
                                    data.data.buy_sell_list, data.data.register_num_list, data.data.user_apply_consult_list, data.data.user_withdraw_recharge_list)
                            } else {
                                $alert(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                function getTodayUserStatistics() {
                    $.ajax({
                        url: "userTodayStatistics",
                        type: 'post',
                        data: {},
                        success: function (data) {
                            // console.log(data);
                            if (data.state == 1) {
                                $('#todayUserWithdrawRecharge').html(data.data.today_user_withdraw_recharge);
                                $('#todayUserApplyConsult').html(data.data.today_user_apply_consult);
                                $('#todayRegisterNum').html(data.data.today_register_num);
                                $('#todayBuySell').html(data.data.today_buy_sell);
                                $('#todayActiveUser').html(data.data.today_active_user);
                            } else {
                                console.log(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                /**
                 * 报表功能
                 */
                function loadEchartsUserStatistics(consultProfitDataTime, active_user_list,
                                                   buy_sell_list, register_num_list, user_apply_consult_list, user_withdraw_recharge_list) {
                    var echartsRecords = echarts.init(document.getElementById('echarts-userStatistics'), 'walden');
                    //var consultProfitDataTime = ['周一', '周二', '周三', '周四', '周五', '周六', '周日'];
                    var optionRecords = {
                        // xAxis: {
                        //     type: 'category',
                        //     data: ['A', 'B', 'C']
                        // },
                        // yAxis: {
                        //     type: 'value'
                        // },
                        // series: [
                        //     {
                        //         data: [120, 200, 150],
                        //         type: 'line'
                        //     }
                        // ],
                        title: {
                            // text: '访问统计'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['活跃用户', '买卖股票用户', '新注册用户数', '申请合约用户', '充值提现']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: consultProfitDataTime
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                name: '活跃用户',
                                type: 'line',
                                stack: '总量',
                                data: active_user_list
                            },
                            {
                                name: '买卖股票用户',
                                type: 'line',
                                stack: '总量',
                                data: buy_sell_list
                            },
                            {
                                name: '新注册用户数',
                                type: 'line',
                                stack: '总量',
                                data: register_num_list
                            },
                            {
                                name: '申请合约用户',
                                type: 'line',
                                stack: '总量',
                                data: user_apply_consult_list
                            },
                            {
                                name: '充值提现',
                                type: 'line',
                                stack: '总量',
                                data: user_withdraw_recharge_list
                            },
                        ]
                    };
                    echartsRecords.setOption(optionRecords);
                    window.addEventListener("resize", function () {
                        echartsRecords.resize();
                    });
                }


                /**** 用户充值统计 ***/
                function loadUserRechargeWithdrawStatistics(value) {
                    $.ajax({
                        url: "userRechargeWithdrawStatistics",
                        type: 'post',
                        data: {'time_range': value},
                        success: function (data) {
                            // console.log(data);
                            if (data.state == 1) {
                                $('#totalRechargeAmount').html(data.data.list.total_recharge_amount + '元');
                                $('#totalWithdrawalAmount').html(data.data.list.total_withdrawal_amount + '元');
                                $('#totalCashBack').html(data.data.list.total_cash_back + '元');
                                $('#totalUserWithdrawNum').html(data.data.list.total_user_withdraw_num);
                                $('#totalUserRechargeNum').html(data.data.list.total_user_recharge_num);
                                // loadEchartsRechargeWithdrawStatistics(data.data.statistics_time_list,data.data.recharge_amount_list,
                                //     data.data.withdrawal_amount_list,data.data.cash_back_list,data.data.user_withdraw_num_list,data.data.user_recharge_num_list)
                                loadEchartsRechargeWithdrawStatistics(data.data.statistics_time_list, data.data.recharge_amount_list,
                                    data.data.withdrawal_amount_list)
                            } else {
                                $alert(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                function getTodayRechargeWithdrawStatistics() {
                    $.ajax({
                        url: "userTodayRechargeWithdrawStatistics",
                        type: 'post',
                        data: {},
                        success: function (data) {
                            //console.log(data);
                            if (data.state == 1) {
                                $('#todayRechargeAmount').html(data.data.today_recharge_amount + '元');
                                $('#todayWithdrawalAmount').html(data.data.today_withdrawal_amount + '元');
                                $('#todayCashBack').html(data.data.cash_back + '元');
                                $('#todayUserWithdrawNum').html(data.data.user_withdraw_num);
                                $('#todayUserRechargeNum').html(data.data.user_recharge_num);
                            } else {
                                console.log(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                /**
                 * 报表功能
                 */
                function loadEchartsRechargeWithdrawStatistics(consultProfitDataTime, recharge_amount,
                                                               withdrawal_amount) {
                    var echartsRecords = echarts.init(document.getElementById('echarts-RechargeWithdrawStatistics'), 'walden');
                    //var consultProfitDataTime = ['周一', '周二', '周三', '周四', '周五', '周六', '周日'];
                    var optionRecords = {
                        // xAxis: {
                        //     type: 'category',
                        //     data: ['A', 'B', 'C']
                        // },
                        // yAxis: {
                        //     type: 'value'
                        // },
                        // series: [
                        //     {
                        //         data: [120, 200, 150],
                        //         type: 'line'
                        //     }
                        // ],
                        title: {
                            text: '单位：元'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['用户充值', '用户提现']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: consultProfitDataTime
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                name: '用户充值',
                                type: 'line',
                                stack: '总量',
                                data: recharge_amount
                            },
                            {
                                name: '用户提现',
                                type: 'line',
                                stack: '总量',
                                data: withdrawal_amount
                            },
                        ]
                    };
                    echartsRecords.setOption(optionRecords);
                    window.addEventListener("resize", function () {
                        echartsRecords.resize();
                    });
                }

                /*****  合约统计 ******/
                function loadConsultStatistics(value) {
                    $.ajax({
                        url: "getConsultStatistics",
                        type: 'post',
                        data: {'time_range': value},
                        success: function (data) {
                            // console.log(data);
                            if (data.state == 1) {
                                $('#totalNewConsultAmount').html(data.data.list.total_new_consult_amount + '元/' + data.data.list.total_new_consult_num + '个');
                                $('#totalStrongLiquidationAmount').html(data.data.list.total_strong_liquidation_amount + '元/' + data.data.list.total_strong_liquidation_num + '个');
                                $('#totalContractSettleAmount').html(data.data.list.total_settle_contract_amount + '元/' + data.data.list.total_settle_contract_num + '个');
                                $('#totalContractAmount').html(data.data.list.total_contract_amount + '元/' + data.data.list.total_contract_amount, +'个');
                                loadEchartsConsultStatistics(data.data.statistics_time_list, data.data.consult_num_list,
                                    data.data.strong_liquidation_list, data.data.settle_contract_num_list, data.data.contract_num_list)
                            } else {
                                $alert(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                function getTodayConsultStatistics() {
                    $.ajax({
                        url: "getTodayConsultStatistics",
                        type: 'post',
                        data: {},
                        success: function (data) {
                            // console.log(data);
                            if (data.state == 1) {
                                $('#todayNewConsultAmount').html(data.data.today_new_consult_amount + '元/' + data.data.today_new_consult_num + '个');
                                $('#todayStrongLiquidationAmount').html(data.data.today_strong_liquidation_amount + '元/' + data.data.today_strong_liquidation_num + '个');
                                $('#todayContractSettleAmount').html(data.data.today_settle_contract_amount + '元/' + data.data.today_settle_contract_num + '个');
                                $('#todayContractAmount').html(data.data.today_contract_amount + '元/' + data.data.today_contract_num + '个');
                            } else {
                                console.log(data.info);
                            }
                        },
                        error: function (data) {
                            console.log(data.info);
                        }
                    })
                }

                /**
                 * 报表功能
                 */
                function loadEchartsConsultStatistics(consultProfitDataTime, consult_num_list,
                                                      strong_liquidation_list, settle_contract_num_list, contract_num_list) {
                    var echartsRecords = echarts.init(document.getElementById('echarts-consultStatistics'), 'walden');

                    var optionRecords = {
                        title: {
                            text: '走势图(单位:个)'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                            data: ['新开合约', '强平合约', '结算合约', '穿仓合约']
                        },
                        grid: {
                            left: '3%',
                            right: '4%',
                            bottom: '3%',
                            containLabel: true
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            }
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: consultProfitDataTime
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                name: '新开合约',
                                type: 'line',
                                stack: '总量',
                                data: consult_num_list
                            },
                            {
                                name: '强平合约',
                                type: 'line',
                                stack: '总量',
                                data: strong_liquidation_list
                            },
                            {
                                name: '结算合约',
                                type: 'line',
                                stack: '总量',
                                data: settle_contract_num_list
                            },
                            {
                                name: '穿仓合约',
                                type: 'line',
                                stack: '总量',
                                data: contract_num_list
                            },
                        ]
                    };
                    echartsRecords.setOption(optionRecords);
                    window.addEventListener("resize", function () {
                        echartsRecords.resize();
                    });
                }

                //加载前7天的数据
                loadConsultProfitAndLoss(iniTime);
                getTodaySettleContractProfitAndLoss();
                loadUserStatistics(iniTime);
                getTodayUserStatistics();

                loadUserRechargeWithdrawStatistics(iniTime);
                getTodayRechargeWithdrawStatistics();

                loadConsultStatistics(iniTime);
                getTodayConsultStatistics();
            });
            ea.listen();
        },
    };
    return Controller;
});