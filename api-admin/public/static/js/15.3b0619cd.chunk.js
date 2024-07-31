(window.webpackJsonp = window.webpackJsonp || []).push([
    [15], {
        250: function (r, e, n) {
            "use strict";
            var b = {
                mobile: function (r) {
                    return /^1[345789]\d{9}$/.test(r)
                },
                paypass: function (r) {
                    return /^(\d){6}$/.test(r)
                },
                password: function (r) {
                    return /^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]{6,16}$/.test(r)
                },
                money: function (r) {
                    return /^\d+(\.\d{1,2})?$/.test(r)
                },
                positiveInteger: function (r) {
                    return /^[0-9]*[1-9][0-9]*$/.test(r)
                },
                integer: function (r) {
                    return /^[1-9]\d*$/.test(r)
                },
                bankCard: function (r) {
                    if (r.length < 16 || r.length > 19) return !1;
                    if (!/^\d*$/.exec(r)) return !1;
                    if (-1 === "10,18,30,35,37,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,58,60,62,65,68,69,84,87,88,94,95,98,99".indexOf(r.substring(0, 2))) return !1;
                    for (var e = r.substr(r.length - 1, 1), n = r.substr(0, r.length - 1), b = [], t = n.length - 1; t > -1; t--) b.push(n.substr(t, 1));
                    for (var w = [], U = [], a = [], i = 0; i < b.length; i++) (i + 1) % 2 === 1 ? 2 * parseInt(b[i], 10) < 9 ? w.push(2 * parseInt(b[i], 10)) : U.push(2 * parseInt(b[i], 10)) : a.push(b[i]);
                    for (var A = [], c = [], o = 0; o < U.length; o++) A.push(parseInt(U[o], 10) % 10),
                        c.push(parseInt(U[o], 10) / 10);
                    for (var u, s = 0, l = 0, f = 0, d = 0, p = 0; p < w.length; p++) s += parseInt(w[p], 10);
                    for (var h = 0; h < a.length; h++) l += parseInt(a[h], 10);
                    for (var m = 0; m < A.length; m++) f += parseInt(A[m], 10),
                        d += parseInt(c[m], 10);
                    u = parseInt(s, 10) + parseInt(l, 10) + parseInt(f, 10) + parseInt(d, 10);
                    var g = 10 - (parseInt(u, 10) % 10 === 0 ? 10 : parseInt(u, 10) % 10);
                    return parseInt(e, 10) === g
                },
                idCard: function (r) {
                    var e = 0;
                    if (!/(^\d{15}$)|(^\d{17}([0-9]|X)$)/i.test(r)) return !1;
                    if (r = r.replace(/x$/i, "a"), void 0 === {
                        11: "北京",
                        12: "天津",
                        13: "河北",
                        14: "山西",
                        15: "内蒙古",
                        21: "辽宁",
                        22: "吉林",
                        23: "黑龙江",
                        31: "上海",
                        32: "江苏",
                        33: "浙江",
                        34: "安徽",
                        35: "福建",
                        36: "江西",
                        37: "山东",
                        41: "河南",
                        42: "湖北",
                        43: "湖南",
                        44: "广东",
                        45: "广西",
                        46: "海南",
                        50: "重庆",
                        51: "四川",
                        52: "贵州",
                        53: "云南",
                        54: "西藏",
                        61: "陕西",
                        62: "甘肃",
                        63: "青海",
                        64: "宁夏",
                        65: "新疆",
                        71: "台湾",
                        81: "香港",
                        82: "澳门",
                        91: "国外"
                    }[parseInt(r.substr(0, 2), 10)]) return !1;
                    var n = r.substr(6, 4) + "-" + Number(r.substr(10, 2)) + "-" + Number(r.substr(12, 2)),
                        b = new Date(n.replace(/-/g, "/"));
                    if (n !== b.getFullYear() + "-" + (b.getMonth() + 1) + "-" + b.getDate()) return !1;
                    for (var t = 17; t >= 0; t--) e += Math.pow(2, t) % 11 * parseInt(r.charAt(17 - t), 11);
                    return e % 11 === 1
                },
                email: function (r) {
                    return /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/.test(r)
                },
                captcha: function (r) {
                    return /[0-9|a-z|A-Z]{4}/.test(r)
                },
                msgCode: function (r) {
                    return /^\d{6}$/.test(r)
                }
            };
            e.a = b
        },
        303: function (r, e, n) {
            "use strict";
            n.d(e, "a", function () {
                return t
            });
            var b = n(4),
                t = function (r, e, n) {
                    return {
                        type: b.b,
                        payload: {
                            token: r,
                            memberId: e,
                            mobile: n
                        }
                    }
                }
        },
        330: function (r, e, n) {
            "use strict";
            n(241);
            var b = n(242),
                t = n.n(b),
                w = n(34),
                U = n(35),
                a = n(37),
                i = n(36),
                A = n(38),
                c = n(3),
                o = n(0),
                u = n.n(o);

            function s() {
                var r = Object(c.a)(["\n    background-color: #ff3f4c;\n  display: inline-block;\n    width: auto;\n    height: 100%;\n    min-height: 26px;\n    border: none;\n  ", ";\n    color: #fff;\n    font-size: 14px;\n    text-align: center;\n    padding: 0 10px;\n    border-radius: 6px;\n    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);\n"]);
                return s = function () {
                    return r
                },
                    r
            }

            var l = n(2).b.button(s(), function (r) {
                    return r.disable ? "#C8C8C8" : "#fbc02d"
                }),
                f = function (r) {
                    function e(r) {
                        var n;
                        return Object(w.a)(this, e), (n = Object(a.a)(this, Object(i.a)(e).call(this, r))).onClick = function (r, e) {
                            if (e.preventDefault(), !n.state.disable) {
                                n.setState({
                                    disable: !0
                                });
                                var b = r();
                                b && "function" === typeof b.then ? b.then(function (r) {
                                    "1" === r.data.status ? n.timmer = setInterval(function () {
                                        var r = n.state.second;
                                        0 === r ? (clearInterval(n.timmer), n.setState({
                                            second: n.props.duration,
                                            disable: !1
                                        })) : n.setState({
                                            second: r - 1
                                        })
                                    }, 1e3) : (t.a.fail(r.data.message), n.setState({
                                        disable: !1
                                    }))
                                }) : n.setState({
                                    disable: !1
                                })
                            }
                        },
                            n.state = {
                                disable: !1,
                                second: n.props.duration
                            },
                            n
                    }

                    return Object(A.a)(e, r),
                        Object(U.a)(e, [{
                            key: "render",
                            value: function () {
                                var r = this;
                                return u.a.createElement(l, {
                                    disable: this.state.disable,
                                    onClick: function (e) {
                                        return r.onClick(r.props.onSend, e)
                                    }
                                }, this.state.disable ? "重新发送(".concat(this.state.second, ")") : "获取验证码")
                            }
                        }]),
                        e
                }(u.a.PureComponent);
            e.a = f
        },
        805: function (r, e, n) {
            r.exports = n.p + "static/media/zhuce1@2x.8f7726d0.png"
        },
        806: function (r, e) {
            r.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB0AAAAfCAMAAAAshTY2AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAABhlBMVEXwbUr////wbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUoAAABor/3EAAAAgHRSTlMAAA9Nd4B4ThAfn/mkIlr15fj2XWP6kTIwj2Y0+9spJ9j8OMnmGhjizgE+R0KQ0cuXx4J9zeBcWFlW53t1mcO/nkw6UALUDQvQ3AQUEsBKf+hrEWmDfP1+CersbUTesMLdSHrXaBwbZYjuXo4gHdMk3xX3qC4l/pKKF4fIt0+EX0bzwmAAAAABYktHRIESuq7+AAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH4gcMCg8CC6+LtAAAAW9JREFUKM9t0mdTAjEQBuBEwK7YGweIDc+CDUVRsQEq1rOcWMDee+/6/nRzKEeCtx8ymzzJTLIbQlKRYTJbMrOyaSpSlpObBy3y8gv+a6FVk6JiNpaUpmsZUF5RyZKqarahRlSbBLsjkVHqrIWrTtB6NDSSP6VNbjTLnLYAJqIrbQXaOG2Hh3BKO9DJaRe6Be2Bl9Ne9AnqQwOnbvQLOgA/px0YFHQIHk6HERB0BKOcjgHjnNqACU6DtQiFdZ2cwnSErxV7f2DmT2fngHmxCwvAorKkNXnZD6ykd3BVBaTQXEgC1LX0DkaVdSRjYzMq6lZMW44F4ts7WtK7y6m8x1b2Dw4Ttzo69rKZRdb1BDg9C+ovkn0bwHlSj9nBC6FWl1eA8qvXVtw0EUHprR13zoTew2UjaUofyhHX9PEJz+Sf0hfAQbUqqZcG+irhjek7RoiB0g/2e0jkDhZDzYQUJWEViqF+AkeE+L7Chhoxf9MfhF5gNvHSSmUAAAAASUVORK5CYII="
        },
        807: function (r, e) {
            r.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAcCAMAAABS8b9vAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAA21BMVEXwbUr////wbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUrwbUoAAADMqwplAAAAR3RSTlMAADmKr8C/qXckEbGoFw7T7bq56eEmpuhfBwJR5NsPOMkVFtWWneP1/B7qb316H/SJOgVVnCFTYAo0veLQwrugtsW1sLjHN3gnvmgAAAABYktHREjwAtTqAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH4gcMCg8CC6+LtAAAAL1JREFUKM/V0FcWgjAQheGxoIK9YC/Ye+8Idr3735EYUARxAf4vOed7mElCpOf2eDmfP8CTi6WjEIRRKGxyJKpBLJ5IakdKfHMayGRzRPlCESiVDa4AUlWfVqsDDYObaAnGZuLb6HR17qFP7wbAkPEIEE0WxpgwngIzk13zxZLxCqAPft17/ZM3X7yVd0BUscRVSIZDKinA/mDpCCgan4isK8+ML3Y+/AdLdr4yxu1iSX0+3uf8VXdO+UqdPgCzWUFuijRouQAAAABJRU5ErkJggg=="
        },
        808: function (r, e) {
            r.exports = "data:image/png;base64,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"
        },
        819: function (r, e, n) {
            "use strict";
            n.r(e);
            var b = n(34),
                t = n(35),
                w = n(37),
                U = n(36),
                a = n(38),
                i = n(0),
                A = n.n(i),
                c = n(827),
                o = n(72),
                u = n.n(o),
                s = n(3),
                l = n(2);

            function f() {
                var r = Object(s.a)(["\n    margin-bottom: 15px;\n    position: relative;\n"]);
                return f = function () {
                    return r
                },
                    r
            }

            function d() {
                var r = Object(s.a)(["\n    img{\n        max-width: 100%;\n    }\n"]);
                return d = function () {
                    return r
                },
                    r
            }

            function p() {
                var r = Object(s.a)(["\n    background-color: #fff9eb;\n    min-height: 100%\n"]);
                return p = function () {
                    return r
                },
                    r
            }

            function h() {
                var r = Object(s.a)(["\n    font-size: 18px;\n    line-height: 40px;\n    text-align: center;\n    color: #ffffff;\n"]);
                return h = function () {
                    return r
                },
                    r
            }

            var m = l.b.div(h()),
                g = l.b.div(p()),
                x = l.b.div(d()),
                C = l.b.div(f()),
                E = n(805),
                v = n.n(E),
                O = (n(241), n(242)),
                S = n.n(O),
                I = n(78);

            function R() {
                var r = Object(s.a)(["\n    width: 120px;\n    padding-left: 20px;\n    button {\n        width: calc(100%);\n    }\n"]);
                return R = function () {
                    return r
                },
                    r
            }

            function B() {
                var r = Object(s.a)(["\n    background-color: #fff;\n border: #efe6d5 1px solid;\n    border-radius: 5px;\n    display: inline-flex;\n    flex: 1 1 20px;\n    .label {\n        display: flex;\n        width: 30px;\n        align-items: center;\n        justify-content: center;\n\n        img {\n            flex-grow: 0;\n            width: 15px;\n        }\n    }\n    input {\n        flex: 1;\n        font-size: 14px;\n        border: none;\n        line-height: 35px;\n        padding: 3px 5px;\n        background-color: transparent;\n        color: #000;\n        &::placeholder {\n            color: #ff8372;\n        }\n    }\n"]);
                return B = function () {
                    return r
                },
                    r
            }

            function j() {
                var r = Object(s.a)(["\n    display: flex;\n    margin-bottom: 20px;\n"]);
                return j = function () {
                    return r
                },
                    r
            }

            var k = l.b.div(j()),
                F = function (r) {
                    function e(r) {
                        var n;
                        return Object(b.a)(this, e), (n = Object(w.a)(this, Object(U.a)(e).call(this, r))).onFocus = function (r) {
                            setTimeout(function () {
                                n.el.current.scrollIntoView(!0)
                            }, 200)
                        },
                            n.el = A.a.createRef(),
                            n
                    }

                    return Object(a.a)(e, r),
                        Object(t.a)(e, [{
                            key: "render",
                            value: function () {
                                var r = this,
                                    e = this.props,
                                    n = e.label,
                                    b = e.extra,
                                    t = (e.children, Object(I.a)(e, ["label", "extra", "children"]));
                                return A.a.createElement(k, {
                                    ref: function (e) {
                                        return r.node = e
                                    }
                                }, A.a.createElement(T, null, n ? A.a.createElement("span", {
                                    className: "label"
                                }, " ", n) : null, A.a.createElement("input", Object.assign({
                                    ref: this.el
                                }, t, {
                                    onFocus: this.onFocus
                                }))), b ? A.a.createElement(K, null, b) : null)
                            }
                        }]),
                        e
                }(i.Component),
                T = l.b.div(B()),
                K = l.b.div(R()),
                M = n(806),
                J = n.n(M),
                D = n(807),
                P = n.n(D),
                y = n(808),
                L = n.n(y),
                z = n(330),
                Q = n(31),
                V = n.n(Q),
                X = n(30),
                Z = n(250),
                W = n(24),
                Y = n(303);

            function G() {
                var r = Object(s.a)(["\n    background-color:#ff3f4c;\n color:#fff;\n width: 100%;\n    border: none;\n    border-radius: 5px;\n    line-height: 44px;\n    margin-top: 10px;\n   ", ";\n    ", ";\n    font-size: 16px;\n    box-shadow: 0 4px 0 ", ';\n\n    ::before {\n        content: " ";\n\n\n    }\n']);
                return G = function () {
                    return r
                },
                    r
            }

            function H() {
                var r = Object(s.a)(["\n   position: relative; width: 80%;margin: 0 auto;margin-top: -50px;\n"]);
                return H = function () {
                    return r
                },
                    r
            }

            var q = function (r) {
                    function e(r) {
                        var n;
                        return Object(b.a)(this, e), (n = Object(w.a)(this, Object(U.a)(e).call(this, r))).sendSms = function () {
                            var r = n.mobileRef.current.el.current.value;
                            return !!n._checkSendSms(r) && V.a.post("".concat(X.wb), {
                                mobile: r,
                                template: "sms_tp01"
                            })
                        },
                            n.submit = function (r) {
                                if (r.preventDefault(), n.state.canSubmit) {
                                    var e = n.props,
                                        b = e.match,
                                        t = e.actLogin,
                                        w = e.history;
                                    if ("" === b.params.code) return S.a.fail("注册链接错误，请重新获取链接注册");
                                    var U = n.mobileRef.current.el.current.value,
                                        a = n.codeRef.current.el.current.value,
                                        i = n.passwordRef.current.el.current.value,
                                        A = n.rePasswordRef.current.el.current.value;
                                    if (!n._checkSubmit(U, a, i, A)) return !1;
                                    n.setState({
                                        canSubmit: !1
                                    }),
                                        V.a.post("".concat(X.rb), {
                                            mobile: U,
                                            sms_code: a,
                                            password: i,
                                            re_password: A,
                                            recom_id: b.params.code
                                        }).then(function (r) {
                                            "1" === r.data.status ? (t(r.data.data.token, r.data.data.uid, r.data.data.mobile), S.a.success("注册成功", 1, function () {
                                                w.push("/member/index")
                                            })) : S.a.fail(r.data.message),
                                                n.setState({
                                                    canSubmit: !0
                                                })
                                        }).catch(function (r) {
                                            n.setState({
                                                canSubmit: !0
                                            })
                                        })
                                }
                            },
                            n._checkSendSms = function (r) {
                                return !!n._checkMobile(r)
                            },
                            n._checkSubmit = function (r, e, b, t) {
                                return !!(n._checkMobile(r) && n._checkCode(e) && n._checkPassword(b) && n._checkRePassword(b, t))
                            },
                            n._checkMobile = function (r) {
                                return 0 === r.length ? S.a.info("请输入手机号码", 1, null, !1) : !!Z.a.mobile(r) || S.a.info("手机号码错误", 1, null, !1)
                            },
                            n._checkPassword = function (r) {
                                return 0 === r.length ? S.a.info("请输入登录密码", 1, null, !1) : !!Z.a.password(r) || S.a.info("密码格式错误，密码为6-16位，必须包含数字和字母", 2, null, !1)
                            },
                            n._checkCode = function (r) {
                                return 0 === r.length ? S.a.info("请输入短信验证码", 1, null, !1) : !!Z.a.msgCode(r) || S.a.info("短信验证码错误", 1, null, !1)
                            },
                            n.mobileRef = A.a.createRef(),
                            n.codeRef = A.a.createRef(),
                            n.passwordRef = A.a.createRef(),
                            n.rePasswordRef = A.a.createRef(),
                            n.state = {
                                canSubmit: !0
                            },
                            n
                    }

                    return Object(a.a)(e, r),
                        Object(t.a)(e, [{
                            key: "_checkRePassword",
                            value: function (r, e) {
                                return 0 === e.length ? S.a.info("请重复输入登录密码", 1, null, !1) : r === e || S.a.info("登录密码不一致", 1, null, !1)
                            }
                        }, {
                            key: "render",
                            value: function () {
                                return A.a.createElement(_, {
                                    onSubmit: this.submit
                                }, A.a.createElement(C, null, A.a.createElement(F, {
                                    ref: this.mobileRef,
                                    label: A.a.createElement("img", {
                                        src: J.a,
                                        alt: "icon"
                                    }),
                                    type: "phone",
                                    placeholder: "请输入11位中国大陆手机号"
                                }), A.a.createElement(F, {
                                    ref: this.codeRef,
                                    placeholder: "请输入短信验证码",
                                    label: A.a.createElement("img", {
                                        src: L.a,
                                        alt: "icon"
                                    }),
                                    extra: A.a.createElement(z.a, {
                                        duration: 60,
                                        onSend: this.sendSms
                                    })
                                }), A.a.createElement(F, {
                                    ref: this.passwordRef,
                                    label: A.a.createElement("img", {
                                        src: P.a,
                                        alt: "icon"
                                    }),
                                    type: "password",
                                    placeholder: "请输入您的登录密码"
                                }), A.a.createElement(F, {
                                    ref: this.rePasswordRef,
                                    label: A.a.createElement("img", {
                                        src: P.a,
                                        alt: "icon"
                                    }),
                                    type: "password",
                                    placeholder: "请重复输入登录密码"
                                }), A.a.createElement($, {
                                    type: "submit",
                                    value: "立即注册",
                                    disabled: this.state.canSubmit ? "" : "disabled"
                                })))
                            }
                        }]),
                        e
                }(i.Component),
                N = Object(c.a)(Object(W.b)(null, function (r) {
                    return {
                        actLogin: function (e, n, b) {
                            return r(Object(Y.a)(e, n, b))
                        }
                    }
                })(q)),
                _ = l.b.form(H()),
                $ = l.b.input(G(), function (r) {
                    return r.disabled ? "#f19427" : "#FBC02D"
                }, function (r) {
                    return r.disabled ? "#fff" : "#ff4500"
                }, function (r) {
                    return r.disabled ? "#b3521a" : "#ff4500"
                }),
                rr = function (r) {
                    function e() {
                        return Object(b.a)(this, e),
                            Object(w.a)(this, Object(U.a)(e).apply(this, arguments))
                    }

                    return Object(a.a)(e, r),
                        Object(t.a)(e, [{
                            key: "render",
                            value: function () {
                                return A.a.createElement(u.a, {
                                    title: "注册交易平台"
                                }, A.a.createElement(g, null, '', A.a.createElement(x, null, A.a.createElement("img", {
                                    src: v.a,
                                    alt: "注册推广"
                                })), A.a.createElement(N, null)))
                            }
                        }]),
                        e
                }(i.Component);
            e.default = Object(c.a)(rr)
        }
    }
]);
//# sourceMappingURL=15.3b0619cd.chunk.js.map