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
        <span id="openm">在浏览器<img src="//ae01.alicdn.com/kf/Hab0533e02e284d348f2ad86da7342189n.png"
                                      class="icon-safari">中打开</span></p>
</div>
<a style="display: none;" href="<?php echo $url; ?>" id="vurl" rel="noreferrer"></a>
<img src="//ae01.alicdn.com/kf/H010256ad9f354c8ab07b735e6b0bd208e.png"
     style="position:absolute;top:7px;width:100%;"><br/><br/>

<script>
    function openu(u) {
        document.getElementById("vurl").href = u;
        document.getElementById("vurl").click();
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