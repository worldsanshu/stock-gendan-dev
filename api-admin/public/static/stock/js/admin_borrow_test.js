$(function () {
    var trNum = $('.js-table-checkable').find("tbody").children("tr");

    var lens = trNum.length;
    var str = '';
    $.each(trNum, function (index, value) {
        str += $.trim($('.js-table-checkable').find("tbody").children("tr").eq(index).find('th').eq(2).html());
        if (index !== lens - 1) {
            str += ',';
        }
    });
    var url = dolphin.stock_operation_js;
    $.ajax({
        url: url,
        data: {
            str: str
        },
        type: "post",
        dataType: "json",
        success: function (res) {
            //var res = eval(res); //数组     
            var tt = "";
            $.each(res.data, function (index, value) {

                //循环获取数据
                $('.js-table-checkable').find("tbody").children("tr").eq(index).find('th').eq(9).children("p").html(value.losswarn)
                $('.js-table-checkable').find("tbody").children("tr").eq(index).find('th').eq(9).children("p1").html(value.lossclose)
            });
        }
    })
    setInterval(function () {
        pos_oper(url);
    }, 5000);

    function pos_oper(url) {
        $.ajax({
            url: url,
            data: {
                str: str
            },
            type: "post",
            dataType: "json",
            success: function (res) {
                //var res = eval(res); //数组     
                var tt = "";
                $.each(res.data, function (index, value) {
                    //循环获取数据     
                    //alert(value['losswarn']);
                    // $('.js-table-checkable').find("tbody").children("tr").eq(index).find('td').eq(10).html(value.losswarn)
                    // $('.js-table-checkable').find("tbody").children("tr").eq(index).find('td').eq(11).html(value.lossclose)
                    $('.js-table-checkable').find("tbody").children("tr").eq(index).find('th').eq(9).children("p").html(value.losswarn)
                    $('.js-table-checkable').find("tbody").children("tr").eq(index).find('th').eq(9).children("p1").html(value.lossclose)
                });
            }
        })
    }
});