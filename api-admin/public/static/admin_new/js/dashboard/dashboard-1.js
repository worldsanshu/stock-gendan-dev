(function ($) {
    /* "use strict" �������أ�Http://www.bootstrapmb.com */

    var dlabChartlist = function () {

        var screenWidth = $(window).width();

        var donutChart1 = function () {
            $("span.donut1").peity("donut", {
                width: "100",
                height: "100"
            });
        }

        var widgetChart1 = function () {
            var options = {
                series: [
                    {
                        name: 'Net Profit',
                        data: [200, 310, 50, 250, 50, 300, 100, 200, 100, 400],
                        /* radius: 30,	 */
                    },
                ],
                chart: {
                    type: 'line',
                    height: 70,
                    width: 500,
                    toolbar: {
                        show: false,
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }

                },

                //colors:['var(--primary)'],
                dataLabels: {
                    enabled: false,
                },

                legend: {
                    show: false,
                },
                stroke: {
                    show: true,
                    width: 6,
                    curve: 'smooth',
                    colors: ['rgba(163, 199, 241, 1)'],
                },

                grid: {
                    show: false,
                    borderColor: '#eee',
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: -1

                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                xaxis: {
                    categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August', 'Sept', 'Oct'],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            fontSize: '12px',
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: 'front',
                        stroke: {
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: '12px',
                        }
                    }
                },
                yaxis: {
                    show: false,
                },
                tooltip: {
                    enabled: false,
                    style: {
                        fontSize: '12px',
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands"
                        }
                    }
                }
            };
            var chartBar1 = new ApexCharts(document.querySelector("#widgetChart1"), options);
            chartBar1.render();

        }
        var widgetChart5 = function () {
            if (jQuery("#widgetChart5").length > 0) {
                var options = {
                    series: [
                        {
                            name: 'Net Profit',
                            data: [200, 310, 50, 250, 50, 300, 100, 200, 100, 400],
                            /* radius: 30,	 */
                        },
                    ],
                    chart: {
                        type: 'line',
                        height: 70,
                        width: 500,
                        toolbar: {
                            show: false,
                        },
                        zoom: {
                            enabled: false
                        },
                        sparkline: {
                            enabled: true
                        }

                    },

                    //colors:['var(--primary)'],
                    dataLabels: {
                        enabled: false,
                    },

                    legend: {
                        show: false,
                    },
                    stroke: {
                        show: true,
                        width: 6,
                        curve: 'smooth',
                        colors: ['rgba(163, 199, 241, 1)'],
                    },

                    grid: {
                        show: false,
                        borderColor: '#eee',
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: -1

                        }
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    xaxis: {
                        categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August', 'Sept', 'Oct'],
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            show: false,
                            style: {
                                fontSize: '12px',
                            }
                        },
                        crosshairs: {
                            show: false,
                            position: 'front',
                            stroke: {
                                width: 1,
                                dashArray: 3
                            }
                        },
                        tooltip: {
                            enabled: true,
                            formatter: undefined,
                            offsetY: 0,
                            style: {
                                fontSize: '12px',
                            }
                        }
                    },
                    yaxis: {
                        show: false,
                    },
                    tooltip: {
                        enabled: false,
                        style: {
                            fontSize: '12px',
                        },
                        y: {
                            formatter: function (val) {
                                return "$" + val + " thousands"
                            }
                        }
                    }
                };
                var chartBar5 = new ApexCharts(document.querySelector("#widgetChart5"), options);
                chartBar5.render();

            }

        }


        var widgetChart2 = function () {
            var options = {
                series: [
                    {
                        name: 'Net Profit',
                        data: [200, 300, 200, 250, 200, 240, 180, 230, 200, 250, 200],
                        /* radius: 30,	 */
                    },
                ],
                chart: {
                    type: 'line',
                    height: 70,
                    width: 500,
                    toolbar: {
                        show: false,
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }

                },

                colors: ['var(--primary)'],
                dataLabels: {
                    enabled: false,
                },

                legend: {
                    show: false,
                },
                stroke: {
                    show: true,
                    width: 6,
                    curve: 'smooth',
                    colors: ['rgba(148, 150, 176, 1)'],
                },

                grid: {
                    show: false,
                    borderColor: '#eee',
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0

                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                xaxis: {
                    categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August', 'Sept', 'Oct'],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            fontSize: '12px',
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: 'front',
                        stroke: {
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: '12px',
                        }
                    }
                },
                yaxis: {
                    show: false,
                },

                tooltip: {
                    enabled: false,
                    style: {
                        fontSize: '12px',
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands"
                        }
                    }
                }
            };

            var chartBar1 = new ApexCharts(document.querySelector("#widgetChart2"), options);
            chartBar1.render();

        }

        var widgetChart3 = function () {
            var options = {
                series: [
                    {
                        name: 'Net Profit',
                        data: [100, 300, 200, 250, 200, 240, 180, 230, 200, 250, 300],
                        /* radius: 30,	 */
                    },
                ],
                chart: {
                    type: 'line',
                    height: 70,
                    width: 500,
                    toolbar: {
                        show: false,
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }

                },

                //colors:['var(--primary)'],
                dataLabels: {
                    enabled: false,
                },

                legend: {
                    show: false,
                },
                stroke: {
                    show: true,
                    width: 6,
                    curve: 'smooth',
                    colors: ['rgba(247, 215, 168, 1)'],
                },

                grid: {
                    show: false,
                    borderColor: '#eee',
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: -1

                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                xaxis: {
                    categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August', 'Sept', 'Oct'],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            fontSize: '12px',
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: 'front',
                        stroke: {
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: '12px',
                        }
                    }
                },
                yaxis: {
                    show: false,
                },
                tooltip: {
                    enabled: false,
                    style: {
                        fontSize: '12px',
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands"
                        }
                    }
                }
            };

            var chartBar1 = new ApexCharts(document.querySelector("#widgetChart3"), options);
            chartBar1.render();

        }

        var widgetChart4 = function () {
            var options = {
                series: [
                    {
                        name: 'Net Profit',
                        data: [200, 310, 50, 250, 50, 300, 100, 200,],
                        /* radius: 30,	 */
                    },
                ],
                chart: {
                    type: 'line',
                    height: 70,
                    width: 450,
                    toolbar: {
                        show: false,
                    },
                    zoom: {
                        enabled: false
                    },
                    sparkline: {
                        enabled: true
                    }

                },

                //colors:['var(--primary)'],
                dataLabels: {
                    enabled: false,
                },

                legend: {
                    show: false,
                },
                stroke: {
                    show: true,
                    width: 6,
                    curve: 'smooth',
                    colors: ['rgba(229, 159, 241, 1)'],
                },

                grid: {
                    show: false,
                    borderColor: '#eee',
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: -1

                    }
                },
                states: {
                    normal: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    hover: {
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    },
                    active: {
                        allowMultipleDataPointsSelection: false,
                        filter: {
                            type: 'none',
                            value: 0
                        }
                    }
                },
                xaxis: {
                    categories: ['Jan', 'feb', 'Mar', 'Apr', 'May', 'June', 'July', 'August', 'Sept', 'Oct'],
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        show: false,
                        style: {
                            fontSize: '12px',
                        }
                    },
                    crosshairs: {
                        show: false,
                        position: 'front',
                        stroke: {
                            width: 1,
                            dashArray: 3
                        }
                    },
                    tooltip: {
                        enabled: true,
                        formatter: undefined,
                        offsetY: 0,
                        style: {
                            fontSize: '12px',
                        }
                    }
                },
                yaxis: {
                    show: false,
                },
                tooltip: {
                    enabled: false,
                    style: {
                        fontSize: '12px',
                    },
                    y: {
                        formatter: function (val) {
                            return "$" + val + " thousands"
                        }
                    }
                }
            };

            var chartBar1 = new ApexCharts(document.querySelector("#widgetChart4"), options);
            chartBar1.render();

        }
        var widgetChart6 = function () {
            if (jQuery("#widgetChart6").length > 0) {
                var options = {
                    series: [
                        {
                            name: 'Net Profit',
                            data: [200, 310, 50, 250, 50, 300, 100, 200,],
                            /* radius: 30,	 */
                        },
                    ],
                    chart: {
                        type: 'line',
                        height: 70,
                        width: 450,
                        toolbar: {
                            show: false,
                        },
                        zoom: {
                            enabled: false
                        },
                        sparkline: {
                            enabled: true
                        }

                    },

                    //colors:['var(--primary)'],
                    dataLabels: {
                        enabled: false,
                    },

                    legend: {
                        show: false,
                    },
                    stroke: {
                        show: true,
                        width: 6,
                        curve: 'smooth',
                        colors: ['rgba(229, 159, 241, 1)'],
                    },

                    grid: {
                        show: false,
                        borderColor: '#eee',
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: -1

                        }
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    xaxis: {
                        categories:generateCurrentMonthCalendar,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            show: false,
                            style: {
                                fontSize: '12px',
                            }
                        },
                        crosshairs: {
                            show: false,
                            position: 'front',
                            stroke: {
                                width: 1,
                                dashArray: 3
                            }
                        },
                        tooltip: {
                            enabled: true,
                            formatter: undefined,
                            offsetY: 0,
                            style: {
                                fontSize: '12px',
                            }
                        }
                    },
                    yaxis: {
                        show: false,
                    },
                    tooltip: {
                        enabled: false,
                        style: {
                            fontSize: '12px',
                        },
                        y: {
                            formatter: function (val) {
                                return "$" + val + " thousands"
                            }
                        }
                    }
                };

                var chartBar6 = new ApexCharts(document.querySelector("#widgetChart6"), options);
                chartBar6.render();

            }

        }
        var marketChart = function () {
            if (jQuery('#marketChart').length > 0) {
                var options = {
                    series: [{
                        name: '本月',
                        className: 'bg-primary',
                        data: month_member_1
                    }, {
                        name: '上月',
                        className: 'bg-secondary',
                        data:month_member_2
                    }],
                    chart: {
                        height: 300,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ["var(--primary)", "var(--secondary)"],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    legend: {
                        show: false,

                    },
                    markers: {
                        strokeWidth: 2,
                        strokeColors: '#fff',
                        hover: {
                            size: 6,
                        },
                    },
                    grid: {
                        show: true,
                        strokeDashArray: 3,
                        borderColor: 'var(--border)',
                        xaxis: {
                            lines: {
                                show: true
                            },
                        },
                        yaxis: {
                            lines: {
                                show: false
                            },
                        },
                    },
                    yaxis: {
                        show: false,
                        labels: {
                            style: {
                                colors: 'var(--text)',
                                fontSize: '12px',
                                fontFamily: 'Poppins',
                                fontWeight: 400

                            },
                            formatter: function (value) {
                                return value + "";
                            }
                        },
                    },
                    xaxis: {
                        categories:generateCurrentMonthCalendar,
                        labels: {
                            style: {
                                colors: '#B5B5C3',
                                fontSize: '12px',
                                fontFamily: 'Poppins',
                                fontWeight: 400

                            },
                        },
                        axisBorder: {
                            show: false,
                        },
                        tooltip: {
                            enabled: false,
                        }
                    },
                    tooltip: {
                        custom: function ({series, seriesIndex, dataPointIndex, w}) {
                            return '<div class="tooltip_box">' +
                                '<div class="tooltip-data">' +
                                '<span class="data-point ' + w.config.series[0].className + '">' + w.config.series[0].name + ' </span>' +
                                '<span>' + series[0][dataPointIndex] + ' </span>' +
                                '</div>' +
                                '<div class="tooltip-data">' +
                                '<span class="data-point ' + w.config.series[1].className + '">' + w.config.series[1].name + ' </span>' +
                                '<span>' + series[1][dataPointIndex] + ' </span>' +
                                '</div>' +
                                '</div>'
                        }
                    },
                    fill: {
                        type: 'solid',
                        opacity: 0
                    },

                };

                // 新用户注册
                if (jQuery("#marketChart").length > 0) {
                    var chartBar1 = new ApexCharts(document.querySelector("#marketChart"), options);
                    chartBar1.render();

                    jQuery('.nav-pills .nav-link').on('click', function () {
                        if ($(this).attr("href") == "#navpills-1") {
                            chartBar1.updateSeries([{
                                    name: '本月',
                                    className: 'bg-primary',
                                    data:month_member_1
                                },
                                    {
                                        name: '上月',
                                        className: 'bg-secondary',
                                        data: month_member_2
                                    }]
                            )
                        }  else if ($(this).attr("href") == "#navpills-2") {
                            chartBar1.updateSeries([{
                                    name: '本月',
                                    className: 'bg-primary',
                                    data:month_gd_1
                                },
                                    {
                                        name: '上月',
                                        className: 'bg-secondary',
                                        data: month_gd_2
                                    }]
                            )
                        } else if ($(this).attr("href") == "#navpills-3") {
                            chartBar1.updateSeries([{
                                    name: '本月',
                                    className: 'bg-primary',
                                    data:month_hy_1
                                },
                                    {
                                        name: '上月',
                                        className: 'bg-secondary',
                                        data: month_hy_2
                                    }]
                            )
                        } else if ($(this).attr("href") == "#navpills-4") {
                            chartBar1.updateSeries([{
                                    name: '本月',
                                    className: 'bg-primary',
                                    data: [7000, 15000, 8000, 3000, 4000, 5000, 1000, 500, 8000, 2000]
                                },
                                    {
                                        name: '上月',
                                        className: 'bg-secondary',
                                        data: [10000, 25000, 15000, 5000, 8000, 7000, 6000, 5000, 10000, 4000]
                                    }]
                            )
                        } else if ($(this).attr("href") == "#navpills-5") {
                            chartBar1.updateSeries([{
                                    name: '本月',
                                    className: 'bg-primary',
                                    data: [25000, 10000, 15000, 10000, 6000, 7000, 6000, 5000, 10000, 4000]
                                },
                                    {
                                        name: '上月',
                                        className: 'bg-secondary',
                                        data: [10000, 25000, 15000, 5000, 8000, 7000, 6000, 5000, 10000, 4000]
                                    }]
                            )
                        }
                    })

                }
            }
        }

        var marketChart2 = function () {
            if (jQuery('#marketChart2').length > 0) {

                var options = {
                    series: [{
                        name: '充值',
                        data: recharge_1
                    }, {
                        name: '提现',
                        data: withdraw_1
                    }],
                    chart: {
                        type: 'bar',
                        height: 295
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: generateCurrentMonthCalendar,
                    },
                    yaxis: {
                        title: {
                            text: '(元)'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return  val + " 元"
                            }
                        }
                    }
                };

                jQuery('.market-chart .nav-link').on('click', function () {
                    // alert($(this).attr("href"));
                    if ($(this).attr("href") == "#Week") {
                        marketOverviewChart.updateSeries([{
                                name: '充值',
                                data: [31, 40, 28, 51, 42, 109, 100]
                            }, {
                                name: '提现',
                                data: [11, 32, 45, 32, 34, 52, 41]

                        }])
                    } else if ($(this).attr("href") == "#Month") {
                        marketOverviewChart.updateSeries([{

                            name: '充值',
                            data: recharge_1
                        }, {
                            name: '提现',
                            data: withdraw_1

                        }])
                    } else if ($(this).attr("href") == "#Quarter") {
                        marketOverviewChart.updateSeries([{

                            name: '充值',
                            data: [31, 40, 28, 51, 42, 109, 100]
                        }, {
                            name: '提现',
                            data: [11, 32, 45, 32, 34, 52, 41]

                        }])
                    }
                })

                var marketOverviewChart = new ApexCharts(document.querySelector("#marketChart2"), options);
                marketOverviewChart.render();


            }
        }

        var swipercard = function () {

            function carouselDir() {
                if ($('body').attr('direction') == "rtl") {
                    return true;
                } else {
                    return false;
                }
            }

            if (jQuery(".mySwiper-counter").length > 0) {
                var swiper = new Swiper('.mySwiper-counter', {


                    speed: 1500,
                    slidesPerView: 4,
                    spaceBetween: 40,
                    rtl: true,
                    parallax: true,
                    loop: false,
                    autoplay: {
                        delay: 5000,
                    },
                    breakpoints: {

                        300: {
                            slidesPerView: 1,
                            spaceBetween: 30,
                        },
                        480: {
                            slidesPerView: 2,
                            spaceBetween: 30,
                        },
                        768: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        991: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1200: {
                            slidesPerView: 4,
                            spaceBetween: 30,
                        },
                    },
                });
            }
        }


        /* Function ============ */
        return {
            init: function () {
            },


            load: function () {
                donutChart1();
                widgetChart1();
                widgetChart2();
                widgetChart3();
                widgetChart4();
                widgetChart5();
                widgetChart6();
                marketChart();
                marketChart2();

                swipercard();

            },

            resize: function () {

            }
        }

    }();


    jQuery(window).on('load', function () {
        setTimeout(function () {
            dlabChartlist.load();
        }, 1000);

    });


})(jQuery);