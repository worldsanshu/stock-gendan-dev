$("#morerule").click(function () {
    layer.open({
        title: "比赛规则",
        type: 2,
        area: ['720px', '800px'],
        shadeClose: true,
        content: "http://m.cnzjcf.com/SubjectPage/simulation/mnds.html"
    });
});

var total = 0;
var size = pagesize;
var page = 1;

template.helper("PaiMing", function (i) {
    return (page - 1) * size + i + 1;
});

function getRank(_page, _pagesize, _type) {
    type = _type;
    page = _page;
    $.post("/wap.php/strategy/match/get_gain_rank", {page: _page, pagesize: _pagesize, type: _type}).done(function (d) {
        var d = $.parseJSON(d)
        if (d.status != 0) {
            type = Number(d.data.type);
            var compare = function (obj1, obj2) {
                var val1 = obj1.RateData;
                var val2 = obj2.RateData;
                if (val1 < val2) {
                    return 1;
                } else if (val1 > val2) {
                    return -1;
                } else {
                    return 0;
                }
            }
            d.data.list.sort(compare);
            $("#rankBox").html(template("rankTemplate", {
                List: d.data
            }));
        } else {
            $("#rankBox").html("");
            layer.msg(d.message);
        }
    }, 'json');
}

getRank(1, size, 0);
$(".ind-tit > a").click(function () {
    $(".ind-tit > a").removeClass("weui-bar__item--on");
    $(this).addClass("weui-bar__item--on");
    getRank(1, size, $(this).index());
});


function ResetVirtualMoney() {
    layer.open({
        title: "重置模拟资金",
        content: '重置模拟资金后将无法参加任何比赛排名!!!<br/>是否要重置模拟资金？',
        btn: ['确定', '取消'],//按钮
        yes: function (index) {
            $.get("/Strategy/ResetVirtualMoney").done(function (result) {
                if (result.IsSuccess) {
                    layer.msg(result.SuccessData, {
                        end: function () {
                            window.location.reload();
                        }
                    });
                } else {
                    layer.msg(result.ErrorMessage);
                }
            });
        }
    });
}