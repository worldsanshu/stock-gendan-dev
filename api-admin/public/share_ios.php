<?php
$url = "http://wap.6789ht.com/wap/invite/" . $_GET['url'];
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>用浏览器打开</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="format-detection" content="telephone=no">
    <meta content="false" name="twcClient" id="twcClient">
    <meta name="aplus-touch" content="1">
    <style>
        body, html {
            sans-serif;
            width: 100%;
            height: 100%
        }

        * {
            margin: 0;
            padding: 0
        }

        body {
            background-color: #fff
        }

        #contens {
            text-align: center;
            font-size: 20px;
            margin-bottom: 125px;
        }

        .top-bar-guidance {
            font-size: 15px;
            color: #fff;
            height: 70%;
            line-height: 1.8;
            padding-left: 20px;
            padding-top: 20px;
            background: url(//gw.alicdn.com/tfs/TB1eSZaNFXXXXb.XXXXXXXXXXXX-750-234.png) center top/contain no-repeat
        }

        .top-bar-guidance .icon-safari {
            width: 25px;
            height: 25px;
            vertical-align: middle;
            margin: 0 .2em
        }

        .app-download-tip {
            margin: 0 auto;
            width: 290px;
            text-align: center;
            font-size: 15px;
            color: #2466f4;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAcAQMAAACak0ePAAAABlBMVEUAAAAdYfh+GakkAAAAAXRSTlMAQObYZgAAAA5JREFUCNdjwA8acEkAAAy4AIE4hQq/AAAAAElFTkSuQmCC) left center/auto 15px repeat-x
        }

        .app-download-tip .guidance-desc {
            background-color: #fff;
            padding: 0 5px
        }

        .app-download-btn {
            display: block;
            width: 214px;
            height: 40px;
            line-height: 40px;
            margin: 18px auto 0 auto;
            text-align: center;
            font-size: 18px;
            color: #2466f4;
            border-radius: 20px;
            border: .5px #2466f4 solid;
            text-decoration: none
        }

        #browser {
            margin: 0px 10px;
            text-align: center;
        }

        #browser img {
            width: 50px;
        }

        .app-download-tip .guidance-desc {
            background-color: #fff;
            padding: 0 5px
        }
    </style>
</head>
<body oncontextmenu="return false" onselectstart="return false">
<div class="top-bar-guidance">
    <p>请点击右上角 <font size="1" style="top:-2px;position:relative;">●●●</font> 按钮<br/>
        <span id="openm">用Safari<img src="//ae01.alicdn.com/kf/H2612bb22e03245cc951c65f0daab67074.png"
                                      class="icon-safari">打开</span></p>
</div>
<a style="display: none;" href="http://<?php echo $url; ?>" id="vurl" rel="noreferrer"></a>
<img src="//ae01.alicdn.com/kf/Hb8a76c7238ac4b068155d99177b00a080.png"
     style="position:absolute;top:7px;width:100%;="><br/><br/>
<div class="app-download-tip">
    <span class="guidance-desc">点击下方浏览器图标直接打开</span>
</div>
<div id="browser">
    <a href="ucbrowser://<?php echo $url; ?>"><img
                src="//ae01.alicdn.com/kf/H38bea5e5d49b4fb8bc7cff3884040ffeA.png"></a>
    <a href="googlechrome://<?php echo $url; ?>"><img src="//ae01.alicdn.com/kf/H9663316a326342a7b6d5f65579e51c0ei.png"></a>
    <a href="mttbrowser://url=<?php echo $url; ?>"><img
                src="//ae01.alicdn.com/kf/H5e64cbf903694e7690f14927b886518aX.png"></a>
    <a href="QihooBrowser://<?php echo $url; ?>"><img src="//ae01.alicdn.com/kf/H5439881c04704463adf136c31c9dcbed8.png"></a>
    <a href="baiduboxapp://browse?url=http://<?php echo $url; ?>"><img
                src="//ae01.alicdn.com/kf/H0f3f64d29db94267b8a4a40a26537ea49.png"></a>
</div>
<script>
    function openu(u) {
        document.getElementById("vurl").href = u;
        document.getElementById("vurl").click();
    }

    var url = "<?php echo $url;?>";
    if (navigator.userAgent.indexOf("QQ/") > -1) {
        openu("ucbrowser://" + url);
        openu("mttbrowser://url=" + url);
        openu("googlechrome://" + url);
        openu("mibrowser:" + url);
        openu("QihooBrowser://" + url);
        openu("baiduboxapp://browse?url=http://" + url);
        $("html").on("click", function () {
            openu("ucbrowser://" + url);
            openu("mttbrowser://url=" + url);
            openu("googlechrome://" + url);
            openu("mibrowser:" + url);
            openu("QihooBrowser://" + url);
            openu("baiduboxapp://browse?url=http://" + url);
        });
    }


    var ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        //return "weixin";
        // alert("微信")
        openu("ucbrowser://" + url);
        openu("mttbrowser://url=" + url);
        openu("googlechrome://" + url);
        openu("mibrowser:" + url);
        openu("QihooBrowser://" + url);
        openu("baiduboxapp://browse?url=http://" + url);
        $("html").on("click", function () {
            openu("ucbrowser://" + url);
            openu("mttbrowser://url=" + url);
            openu("googlechrome://" + url);
            openu("mibrowser:" + url);
            openu("QihooBrowser://" + url);
            openu("baiduboxapp://browse?url=http://" + url);
        });
    } else if (ua.match(/QQ/i) == "qq") {
        openu("ucbrowser://" + url);
        openu("mttbrowser://url=" + url);
        openu("googlechrome://" + url);
        openu("mibrowser:" + url);
        openu("QihooBrowser://" + url);
        openu("baiduboxapp://browse?url=http://" + url);
        $("html").on("click", function () {
            openu("ucbrowser://" + url);
            openu("mttbrowser://url=" + url);
            openu("googlechrome://" + url);
            openu("mibrowser:" + url);
            openu("QihooBrowser://" + url);
            openu("baiduboxapp://browse?url=http://" + url);
        });
    } else {
        window.location = url
    }
</script>

</html>