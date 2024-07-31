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

// 筛选
var first_load = true;

function setFilterItem(sfilter_time) {
    (first_load == true) && (sfilter_time[0] = sfilter_time[1]);
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
    filter += '<div class="weui-flex__item"><div class="placeholder"><input name="filter_time_from" class="weui-input date-picker-input" id="dateStart" type="date" value="' + timetrans(sfilter_time[0]) + '"></div></div>'
    filter += '<div class="weui-flex__item celuo-filter-middle"><div class="placeholder grayb5b5b5">—</div></div>'
    filter += '<div class="weui-flex__item"><div class="placeholder"><input name="filter_time_to" class="weui-input date-picker-input" id="dateEnd" type="date" value="' + timetrans(sfilter_time[1]) + '"></div></div>'
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
        capital(1, pagesize, filter_time, 0, 0);
        capitalMatch(1, pagesize, filter_time, 0, 1);
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
