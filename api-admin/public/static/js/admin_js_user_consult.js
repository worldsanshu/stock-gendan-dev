define(["jquery", "easy-admin"], function ($, ea) {


    var getURLParams = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]);
        return null;
    }
    var initIndex = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'user.consult/index',
    };
    var initPosition = {
        table_elem: '#positionTab',
        table_render_id: 'positionRenderId'
    };
    var initConsult = {
        table_elem: '#consultTab',
        table_render_id: 'consultRenderId'
    };
    var initRecord = {
        table_elem: '#recordTab',
        table_render_id: 'recordRenderId'
    };
    var initUserRecordTab = {
        table_elem: '#userrecordTab',
        table_render_id: 'userrecordeRenderId'
    };
    var initEntrust = {
        table_elem: '#entrustTab',
        table_render_id: 'entrustRenderId'
    };
    var initEntrustFee = {
        table_elem: '#entrustFeeTab',
        table_render_id: 'entrustFeeRenderId'
    };
    var tempInterval;
    var table = layui.table;
    var consultId = 0; //合约主键id
    var code = 0;
    var Controller = {
        index: function () {
            ea.table.render({
                init: initConsult,
                url: ea.url('user.consult/consult?user_id=' + userId),
                toolbar: [],
                skin: 'line',
                height: 400,
                cols: [[
                    {
                        field: 'contact_id',
                        title: '合约名称/编号',
                        width: 150,
                        searchTitle: '合约编号',
                        searchTip: '请输入合约编号',
                        templet: function (d) {
                            if (d.status == 1 || d.status == 2) {
                                return "<div style='text-decoration:line-through' >" + d.name + "[" + d.consult_id + "]<br/>" + d.contact_id + "</div>";
                            } else {
                                return "<div>" + d.name + "[" + d.consult_id + "]<br/>" + d.contact_id + "</div>";
                            }

                        }
                    },
                    // {title:'总操盘资金/总配资金额', width:200, templet:"<div>{{d.account_money}}<br/>{{d.funding}}</div>"},
                    {
                        title: '合约总值/持仓市值',
                        width: 200,
                        templet: "<div>{{d.capital.consultMoney}}<br/>{{d.capital.marketValue}}</div>"
                    },
                    // {title:'起始保证金/追加保证金', width:200, templet:"<div>{{d.deposit}}<br/>{{d.append_deposit}}</div>"},
                    {
                        title: '扩大保证金/累计提盈',
                        width: 200,
                        templet: "<div>{{d.expand_deposit}}<br/>{{d.profits}}</div>"
                    },
                    // {title:'距离预警线/距离强平线', width:200, templet:"<div>{{(parseFloat(d.capital.consultMoney)-parseFloat(d.loss_warn)).toFixed(2)}}<br/>{{(parseFloat(d.capital.consultMoney)-parseFloat(d.loss_close)).toFixed(2)}}</div>"},
                    {
                        title: '持仓盈亏/浮动盈亏', width: 200, templet: function (d) {
                            if (d.capital.floatPL > 0) {
                                d.capital.floatPL = '<span style="color:#b54b4b">' + d.capital.floatPL + '</span>';
                            } else if (d.capital.floatPL == 0) {
                                d.capital.floatPL = '<span style="color:gray">' + d.capital.floatPL + '</span>';
                            } else {
                                d.capital.floatPL = '<span style="color:green">' + d.capital.floatPL + '</span>';
                            }
                            if (d.capital.marketFloatPL > 0) {
                                d.capital.marketFloatPL = '<span style="color:#b54b4b">' + d.capital.marketFloatPL + '</span>';
                            } else if (d.capital.marketFloatPL == 0) {
                                d.capital.marketFloatPL = '<span style="color:gray">' + d.capital.marketFloatPL + '</span>';
                            } else {
                                d.capital.marketFloatPL = '<span style="color:green">' + d.capital.marketFloatPL + '</span>';
                            }
                            return d.capital.marketFloatPL + '<br>' + d.capital.floatPL;
                        }
                    },
                    {title: '利息利率/累计利息', width: 200, templet: "<div>{{d.ratio}}%<br/>{{d.interest}}</div>"},
                    // {title:'可用余额/冻结金额', width:200, templet:"<div>{{d.available_money}}<br/>{{d.blocked_money}}</div>"},
                    // {field:'ctime',searchTitle:'时间',searchTip:'请输入时间',title:'申请时间/合约时间',width:200,search:'range',templet:'<div>{{layui.util.toDateString(d.ctime*1000,\'yyyy-MM-dd HH:mm:ss\')}}<br/>{{layui.util.toDateString(d.atime*1000,\'yyyy-MM-dd\')}}&nbsp;至&nbsp;{{layui.util.toDateString(d.etime*1000,\'yyyy-MM-dd\')}}</div>'},
                    {
                        field: 'status',
                        title: '结算状态',
                        width: 150,
                        selectList: {0: '未结算', 1: '已结算', 2: '已删除'},
                        search: "select"
                    },
                ]],
                done: function (res, curr, count) {
                    //监听行的点击事件
                    $tag = $(initConsult.table_elem).next().find(".layui-table-main tbody").find("tr");
                    if ($tag != undefined) {
                        if ($tag.length > 1) {
                            consultId = JSON.stringify($($tag[1]).data('index'));
                            $($tag[1]).css({
                                'background-color': 'rgb(61 177 166)',
                                'color': '#fff',
                                'font-weight': 'bold'
                            });
                        } else {
                            consultId = JSON.stringify($($tag).data('index'));
                            $($tag).css({
                                'background-color': 'rgb(61 177 166)',
                                'color': '#fff',
                                'font-weight': 'bold'
                            });
                        }
                        if (consultId != undefined) {
                            consultId = consultId.replace("\"", "").replace("\"", "");
                            loadPosition(consultId);
                            loadRecord(consultId)
                        }

                    }
                    loadUserRecord();
                    //点击合约列表格的事件
                    $(initConsult.table_elem).next().find(".layui-table-main tbody").children("tr").on('click', function () {
                        $('#recordDisplay').show();
                        $('#entrustDisplay').hide();
                        $('#entrustFeeDisplay').hide();
                        consultId = JSON.stringify($(initConsult.table_elem).next().find(".layui-table-main tbody").find(".layui-table-hover").data('index'));
                        consultId = consultId.replace("\"", "").replace("\"", "");
                        // var obj = res.data[id];
                        // //这里都是表格的数据
                        //选中样式
                        $(initConsult.table_elem).next().find(".layui-table-main tbody").children("tr").css({
                            'background-color': 'white',
                            'color': 'black'
                        });
                        $(initConsult.table_elem).next().find(".layui-table-main tbody").find(".layui-table-hover").css({
                            'background-color': 'rgb(61 177 166)',
                            'color': '#fff',
                            'font-weight': 'bold'
                        });
                        //重载持仓列表的数据--合约的资金明细
                        var newPositionUrl = ea.url('user.consult/position?user_id=' + userId + '&consultId=' + consultId);
                        table.reload('positionListId', {'url': newPositionUrl});
                        // table.reload('userRecordId', options);
                        var newRecordUrl = ea.url('user.consult/record?user_id=' + userId + '&consultId=' + consultId);
                        table.reload('recordId', {'url': newRecordUrl});
                    })

                    //显示搜索框
                    var searchFieldsetId = 'searchFieldset_' + initConsult.table_render_id;
                    var _that = $("#" + searchFieldsetId);
                    console.log(searchFieldsetId);
                    console.log(_that);
                    if (_that.hasClass("layui-hide")) {
                        _that.removeClass('layui-hide');
                    }
                    _that.find('legend').addClass('layui-hide');
                    $(".layui-table-tool").addClass('layui-hide');
                },
            });

            function loadPosition(consultId) {
                ea.table.render({
                    init: initPosition,
                    id: 'positionListId',
                    url: ea.url('user.consult/position?user_id=' + userId + '&consultId=' + consultId),
                    toolbar: [],
                    height: 312,
                    skin: 'line',
                    cols: [[
                        {
                            title: '股票名称/代码', width: 200, search: false, templet: function (d) {
                                if (d.status == 2) {
                                    return "<div style='text-decoration:line-through' >" + d.name + "/" + d.code + "</div>";
                                } else {
                                    return "<div>" + d.name + "/" + d.code + "</div>";
                                }

                            }
                        },
                        {title: '股票名代码', field: 'order.code', width: 200, hide: true},
                        {
                            title: '可卖数量/持仓数量',
                            search: false,
                            width: 150,
                            templet: "<div>{{d.hand}}/{{d.deal_hand}}</div>"
                        },
                        {title: '可卖数量', search: false, width: 150, templet: "<div>{{d.hand}}</div>"},
                        // {title:'买入均价/当前价格', width:200, templet:"<div>{{d.chengben}}<br/>{{d.last_px}}</div>"},
                        // {title:'买入均价', width:150,templet:"<div>{{d.chengben}}</div>"},
                        // {title:'当前价格', width:150,templet:"<div>{{d.last_px}}</div>"},

                        {title: '合约主键', search: false, field: 'consult_key', width: 150, hide: true},
                        {
                            title: '股票状态',
                            field: 'order.status',
                            selectList: {1: '股票持仓', 2: '股票清仓',},
                            width: 150,
                            hide: true
                        },
                        {
                            title: '持仓市值',
                            search: false,
                            width: 150,
                            templet: "<div>{{parseFloat(d.marketValue).toFixed(2)}}</div>"
                        },
                        {
                            fixed: 'right',
                            title: '参考盈亏',
                            width: 150,
                            templet: "<div>{{parseFloat(d.floatPL).toFixed(2)}}</div>"
                        },
                    ]],
                    done: function (res, curr, count) {
                        var $tag2 = $(initPosition.table_elem).next().find(".layui-table-main tbody").find("tr");
                        if ($tag2 != undefined) {
                            if ($tag2.length > 1) {
                                code = JSON.stringify($($tag2[1]).data('index'));
                                //$($tag2[1]).css({'background-color':'rgb(61 177 166)','color':'#fff','font-weight':'bold'});
                            } else {
                                code = JSON.stringify($($tag2).data('index'));
                                //$($tag2).css({'background-color':'rgb(61 177 166)','color':'#fff','font-weight':'bold'});
                            }
                        }
                        if (code != undefined) {
                            code = code.replace("\"", "").replace("\"", "");
                            console.log(res.data[code]);
                            consultId = res.data[code].consult_key;
                            loadUserEntrust(consultId, code);
                            loadUserEntrustFee(consultId, code);
                        }

                        //点击合约列表格的事件
                        $(initPosition.table_elem).next().find(".layui-table-main tbody").children("tr").on('click', function () {
                            //显示委托列表
                            $('#recordDisplay').hide();
                            $('#entrustDisplay').show();
                            $('#entrustFeeDisplay').show();
                            code = JSON.stringify($(initPosition.table_elem).next().find(".layui-table-main tbody").find(".layui-table-hover").data('index'));
                            console.log(code);
                            code = code.replace("\"", "").replace("\"", "");
                            //选中样式
                            $(initPosition.table_elem).next().find(".layui-table-main tbody").children("tr").css({
                                'background-color': 'white',
                                'color': 'black'
                            });
                            $(initPosition.table_elem).next().find(".layui-table-main tbody").find(".layui-table-hover").css({
                                'background-color': 'rgb(61 177 166)',
                                'color': '#fff',
                                'font-weight': 'bold'
                            });
                            // //重载持仓列表的数据--合约的资金明细
                            var newEntrustIdUrl = ea.url('user.consult/entrust?user_id=' + userId + '&consultId=' + consultId + '&code=' + code);
                            table.reload('userEntrustId', {'url': newEntrustIdUrl});
                            // //重载持仓列表的数据--合约的资金明细
                            var newEntrustFeeIdUrl = ea.url('user.consult/entrustFee?user_id=' + userId + '&consultId=' + consultId + '&code=' + code);
                            table.reload('userEntrustFeeId', {'url': newEntrustFeeIdUrl});

                        })

                        var searchFieldsetId2 = 'searchFieldset_' + 'positionListId';
                        var _that2 = $("#" + searchFieldsetId2);
                        if (_that2.hasClass("layui-hide")) {
                            _that2.removeClass('layui-hide');
                        }
                        _that2.find('legend').addClass('layui-hide');
                        $(".layui-table-tool").addClass('layui-hide');
                    },
                });
            }

            function loadRecord(consultId) {
                ea.table.render({
                    init: initRecord,
                    url: ea.url('user.consult/record?user_id=' + userId + '&consultId=' + consultId),
                    toolbar: [],
                    height: 312,
                    skin: 'line',
                    id: 'recordId',
                    cols: [[
                        {
                            title: '类型', field: 'type', width: 100, selectList: {
                                1: '买入委托',
                                2: '买入成功',
                                3: '卖出成功',
                                4: '买入撤单',
                                5: '卖出撤单',
                                6: '派息',
                                7: '送股',
                                8: '转股',
                                9: '申请',
                                10: '扩大',
                                11: '追加',
                                12: '提盈',
                                13: '结算'
                            }, search: "select"
                        },
                        {
                            title: '变动金额', field: 'amount', search: false, width: 150, templet: function (d) {
                                if (d.direction == 1) {
                                    if (d.amount > 0) {
                                        d.amount = '<span style="color:#b54b4b">+' + d.amount + '</span>';
                                    }
                                } else if (d.direction == 2) {
                                    d.amount = '<span style="color:green">-' + d.amount + '</span>';
                                }
                                return d.amount
                            }
                        },
                        {
                            title: '变动前',
                            field: 'account_before',
                            search: false,
                            width: 150,
                            templet: "<div>{{d.account_before}}</div>"
                        },
                        {
                            title: '变动后',
                            field: 'account_after',
                            search: false,
                            width: 150,
                            templet: "<div>{{d.account_after}}</div>"
                        },
                        {
                            title: '交易时间',
                            field: 'create_time',
                            search: false,
                            width: 150,
                            templet: "<div>{{d.create_time}}</div>"
                        },
                        {
                            title: '订单说明',
                            field: 'remark',
                            fixed: 'right',
                            search: false,
                            width: 120,
                            templet: "<div>{{d.remark}}</div>"
                        },
                    ]],
                    done: function (res, curr, count) {
                        var searchFieldsetId2 = 'searchFieldset_' + 'recordId';
                        var _that2 = $("#" + searchFieldsetId2);
                        if (_that2.hasClass("layui-hide")) {
                            _that2.removeClass('layui-hide');
                        }
                        _that2.find('legend').addClass('layui-hide');
                        $(".layui-table-tool").addClass('layui-hide');
                    }
                });
            }

            function loadUserRecord() {
                ea.table.render({
                    init: initUserRecordTab,
                    url: ea.url('user.consult/userRecord?user_id=' + userId),
                    toolbar: [],
                    height: 312,
                    skin: 'line',
                    id: 'userRecordId',
                    cols: [[
                        {
                            title: '类型', field: 'type', width: 100, selectList: {
                                1: '账号充值',
                                2: '充值返现',
                                3: '申请提现',
                                4: '提现失败',
                                5: '人工转入',
                                6: '人工转出',
                                7: '申请合约',
                                8: '扩大合约'
                                ,
                                9: '追加保证金',
                                10: '合约提盈',
                                11: '合约结算',
                                12: '续期利息',
                                13: '下线返佣'
                            }, search: "select"
                        },
                        {
                            title: '变动金额', field: 'amount', search: false, width: 150, templet: function (d) {
                                if (d.direction == 1) {
                                    if (d.amount > 0) {
                                        d.amount = '<span style="color:#b54b4b">+' + d.amount + '</span>';
                                    }
                                } else if (d.direction == 2) {
                                    d.amount = '<span style="color:green">-' + d.amount + '</span>';
                                }
                                return d.amount
                            }
                        },
                        {
                            title: '变动前',
                            field: 'account_before',
                            search: false,
                            width: 150,
                            templet: "<div>{{d.account_before}}</div>"
                        },
                        {
                            title: '变动后',
                            field: 'account_after',
                            search: false,
                            width: 150,
                            templet: "<div>{{d.account_after}}</div>"
                        },
                        {
                            title: '交易时间',
                            field: 'create_time',
                            search: 'range',
                            width: 150,
                            templet: "<div>{{d.create_time}}</div>"
                        },
                        {
                            title: '订单说明',
                            field: 'remark',
                            fixed: 'right',
                            search: false,
                            width: 120,
                            templet: "<div>{{d.remark}}</div>"
                        },
                    ]],
                    done: function (res, curr, count) {
                        var searchFieldsetId2 = 'searchFieldset_' + 'userRecordId';
                        var _that2 = $("#" + searchFieldsetId2);
                        if (_that2.hasClass("layui-hide")) {
                            _that2.removeClass('layui-hide');
                        }
                        _that2.find('legend').addClass('layui-hide');
                        $(".layui-table-tool").addClass('layui-hide');
                    }
                });
            }

            function loadUserEntrust(consultId, code) {
                ea.table.render({
                    init: initEntrust,
                    url: ea.url('user.consult/entrust?user_id=' + userId + '&consultId=' + consultId + '&code=' + code),
                    toolbar: [],
                    height: 312,
                    skin: 'line',
                    id: 'userEntrustId',
                    cols: [[
                        {
                            title: '股票名称/代码',
                            field: 'code',
                            search: false,
                            width: 200,
                            templet: "<div>{{d.name}}/{{d.code}}</div>"
                        },
                        {
                            title: '订单类型/状态',
                            field: 'state',
                            fieldAlias: 'order.state',
                            searchOp: '=',
                            width: 150,
                            selectList: {
                                1: '委托买入', 2: '委托卖出', 7: '强平', 8: '后台赎回'
                            },
                            search: "select",
                            templet: function (d) {
                                if (d.state == 1 || d.state == 3) {
                                    if (d.state == 1) {
                                        d.state = '<span style="color:#b54b4b">买入</span>/<span style="color:gray">待成交</span>'
                                    } else {
                                        d.state = '<span style="color:#b54b4b">买入</span>/<span style="color:black">已成交</span>'
                                    }
                                } else if (d.state == 2 || d.state == 4 || d.state == 5 || d.state == 6 || d.state == 7 || d.state == 8 || d.state == 9 || d.state == 10) {
                                    if (d.state == 2) {
                                        d.state = '<span style="color:green">卖出</span>/<span style="color:gray">待成交</span>'
                                    } else if (d.state == 4) {
                                        d.state = '<span style="color:green">卖出</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 5) {
                                        d.state = '<span style="color:green">买入撤单</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 6) {
                                        d.state = '<span style="color:green">卖出撤单</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 7) {
                                        d.state = '<span style="color:green">强平</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 9) {
                                        d.state = '<span style="color:green">买入</span>/<span style="color:black">失败</span>'
                                    } else if (d.state == 10) {
                                        d.state = '<span style="color:green">卖出</span>/<span style="color:black">失败</span>'
                                    }
                                }
                                return d.state;

                            }
                        },
                        {
                            title: '委托价/委托量',
                            field: 'entrust_price',
                            width: 150,
                            search: false,
                            templet: "<div>{{d.entrust_price}}<br/>{{d.entrust_hand}}</div>"
                        },
                        {
                            title: '成交价/成交量',
                            field: 'trade_price',
                            width: 150,
                            search: false,
                            templet: "<div>{{d.trade_price}}<br/>{{d.trade_hand}}</div>"
                        },
                        {
                            title: '撤单/成交时间',
                            field: 'over_time',
                            width: 150,
                            search: false,
                            templet: function (d) {
                                return d.update_time = layui.util.toDateString(d.over_time * 1000, 'yyyy-MM-dd HH:mm:ss');
                            }
                        },
                        {field: 'order_id', title: '订单流水号', width: 170, search: false},
                        // {title:'成交额/手续费',field: 'trade_price', width:150,search:false,templet:"<div>{{(parseFloat(d.trade_price)*parseFloat(d.trade_hand)).toFixed(2)}}<br/>{{d.fee}}</div>"},
                        // {title:'剩余持仓',field: 'entrust_hand',search: false, width:150, templet:function(d){
                        //         if(d.state == 1 || d.state == 2){
                        //             return '<span style="color:#b54b4b">' + d.entrust_hand + '</span>';
                        //         }else{
                        //             return '<span style="color:#b54b4b">' + d.hand + '</span>';
                        //         }
                        //     }
                        // },

                    ]],
                    done: function (res, curr, count) {
                        var searchFieldsetId2 = 'searchFieldset_' + 'userEntrustId';
                        var _that2 = $("#" + searchFieldsetId2);
                        if (_that2.hasClass("layui-hide")) {
                            _that2.removeClass('layui-hide');
                        }
                        _that2.find('legend').addClass('layui-hide');
                        $(".layui-table-tool").addClass('layui-hide');
                    }
                });
            }

            function loadUserEntrustFee(consultId, code) {
                ea.table.render({
                    init: initEntrustFee,
                    url: ea.url('user.consult/entrustFee?user_id=' + userId + '&consultId=' + consultId + '&code=' + code),
                    toolbar: [],
                    height: 312,
                    skin: 'line',
                    id: 'userEntrustFeeId',
                    cols: [[
                        {
                            title: '成交股票',
                            field: 'code',
                            search: false,
                            width: 180,
                            templet: "<div>{{d.name}}/{{d.code}}</div>"
                        },
                        {
                            title: '订单类型/状态',
                            field: 'state',
                            fieldAlias: 'order.state',
                            searchOp: '=',
                            width: 150,
                            selectList: {
                                1: '委托买入', 2: '委托卖出', 7: '强平', 8: '后台赎回'
                            },
                            search: "select",
                            templet: function (d) {
                                if (d.state == 1 || d.state == 3) {
                                    if (d.state == 1) {
                                        d.state = '<span style="color:#b54b4b">买入</span>/<span style="color:gray">待成交</span>'
                                    } else {
                                        d.state = '<span style="color:#b54b4b">买入</span>/<span style="color:black">已成交</span>'
                                    }
                                } else if (d.state == 2 || d.state == 4 || d.state == 5 || d.state == 6 || d.state == 7 || d.state == 8 || d.state == 9 || d.state == 10) {
                                    if (d.state == 2) {
                                        d.state = '<span style="color:green">卖出</span>/<span style="color:gray">待成交</span>'
                                    } else if (d.state == 4) {
                                        d.state = '<span style="color:green">卖出</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 5) {
                                        d.state = '<span style="color:green">买入撤单</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 6) {
                                        d.state = '<span style="color:green">卖出撤单</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 7) {
                                        d.state = '<span style="color:green">强平</span>/<span style="color:black">已成交</span>'
                                    } else if (d.state == 9) {
                                        d.state = '<span style="color:green">买入</span>/<span style="color:black">失败</span>'
                                    } else if (d.state == 10) {
                                        d.state = '<span style="color:green">卖出</span>/<span style="color:black">失败</span>'
                                    }
                                }
                                return d.state;

                            }
                        },
                        {
                            title: '成交数量/单价/总额',
                            field: 'entrust_price',
                            width: 180,
                            search: false,
                            templet: "<div>{{d.trade_hand}}/{{d.trade_price}}/{{d.trade_hand*d.trade_price}}</div>"
                        },
                        {
                            title: '费用=佣金+规费+印花税+过户费',
                            field: 'trade_price',
                            width: 230,
                            search: false,
                            templet: "<div>{{d.fee}}= {{d.trade_fee}} + {{d.rule_fee}} + {{d.yinhua_fee}} +{{d.guohu_fee}} </div>"
                        },
                        {
                            title: '成交时间', field: 'over_time', width: 150, search: false, templet: function (d) {
                                return d.update_time = layui.util.toDateString(d.over_time * 1000, 'yyyy-MM-dd HH:mm:ss');
                            }
                        },
                        {field: 'order_id', title: '订单流水号', width: 170, search: false},
                    ]],
                    done: function (res, curr, count) {
                        var searchFieldsetId2 = 'searchFieldset_' + 'userEntrustFeeId';
                        var _that2 = $("#" + searchFieldsetId2);
                        if (_that2.hasClass("layui-hide")) {
                            _that2.removeClass('layui-hide');
                        }
                        _that2.find('legend').addClass('layui-hide');
                        $(".layui-table-tool").addClass('layui-hide');
                    }
                });
            }

            ea.listen();
        },
    };
    return Controller;
});