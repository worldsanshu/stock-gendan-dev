/**
 * @file load baiduid send send log
 * @author guowei<guowei14@baidu.com>
 */
// format request params
function formatParams(data) {
    var arr = [];
    for (var name in data) {
        arr.push(encodeURIComponent(name) + '=' + encodeURIComponent(data[name]));
    }
    arr.push(('v=' + Math.random()).replace('.', ''));
    return arr.join('&');
}

// jsonp 公共函数
function jsonp(options) {
    options = options || {};
    if (!options.url || !options.callback) {
        throw new Error('参数不合法');
    }
    var callbackName = ('jsonp_' + Math.random()).replace('.', '');
    var oHead = document.getElementsByTagName('head')[0];
    options.data[options.callback] = callbackName;
    var params = formatParams(options.data);
    var oS = document.createElement('script');
    oHead.appendChild(oS);

    window[callbackName] = function (json) {
        oHead.removeChild(oS);
        clearTimeout(oS.timer);
        window[callbackName] = null;
        options.success && options.success(json);
    };

    oS.src = options.url + '?' + params;

    if (options.time) {
        oS.timer = setTimeout(function () {
            window[callbackName] = null;
            oHead.removeChild(oS);
            options.fail && options.fail({message: '超时'});
        }, options.time);
    }
}

// 创建http request对象
function createXMLHttpRequest() {
    var xmlHttp;
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
        if (xmlHttp.overrideMimeType) {
            xmlHttp.overrideMimeType('text/xml');
        }
    } else if (window.ActiveXObject) {
        try {
            xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
            try {
                xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (e) {
            }
        }
    }
    return xmlHttp;
}

var retryCount = 0;
var jsonpCount = 0;

// 发送日志
function stat() {
    var baiduidIdx = document.cookie.indexOf('BAIDUID=');
    if (baiduidIdx === -1) {
        if (retryCount > 10) {
            return;
        }
        setTimeout('stat()', 50);
        retryCount++;
        return;
    }
    var xmlHttp = createXMLHttpRequest();
    var queryStr = location.search;
    if (queryStr.length === 0) {
        queryStr = '?t=' + (new Date()).getTime();
    } else {
        queryStr += '&t=' + (new Date()).getTime();
    }
    var url = '/log';
    xmlHttp.open('GET', url + queryStr, true);// 异步处理返回
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    xmlHttp.send();
}

// 强制设置
function requestBaiduId() {
    var baiduidIdx = document.cookie.indexOf('BAIDUID=');
    if (baiduidIdx === -1) {
        jsonp({
            'url': '//nuomiwappassport.baidu.com/webapp/user/crossdomainsync',
            'data': {'ac': 2},
            'callback': 'callback',
            'success': function (res) {
                if (res.data) {
                    var when = new Date();
                    when.setDate(when.getDate() + 2592000);
                    document.cookie = 'BAIDUID=' + res.data.baiduid + '; expires=' + when.toUTCString()
                        + ';path=/; domain=nuomi.com';
                    stat();
                } else if (jsonpCount === 0) {
                    requestBaiduId(); // 如果为空，再请求一次
                }
                jsonpCount++;
            }
        });
    } else {
        stat(); // 存在baiduid也请求
    }
}

requestBaiduId();
