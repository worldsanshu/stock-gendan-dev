setInterval(function () {
    myTimer()
}, 1000);

function myTimer2() {
    $(".fGDaYV").css("display", "none");
    $(".charge-group a").eq(1).remove();
    $(".abc").css("display", "none");
    $(".abc").remove();
    $('.charge-group').append('<div class="ab3c" style="width:2.1333rem;line-height:0.9333rem;height: 0.9333rem; margin-left:0.4rem;background-color: rgb(245,72,56);font-size: 16px;text-align: center;box-shadow: rgba(0,0,0,0.4) 1px 1px 2px; border-radius: 4px;"><a color="#F54838" class="abc" href="/payment/members/index" style="color: rgb(255,255,255);">充值</a></div>');
}

function myTimer() {
    //  $(".fGDaYV").attr("href", "/payment/members/index");
    $(".ab3c").remove();
    $(".fGDaYV").remove();
    $('.charge-group').append('<div class="ab3c" style="width:2.1333rem;line-height:0.9333rem;height: 0.9333rem; margin-left:0.4rem;background-color: rgb(245,72,56);font-size: 16px;text-align: center;box-shadow: rgba(0,0,0,0.4) 1px 1px 2px; border-radius: 4px;"><a color="#F54838" class="abc" href="/payment/members/index" style="color: rgb(255,255,255);">充值</a></div>');
}