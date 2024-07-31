(window.webpackJsonp = window.webpackJsonp || []).push([[7], {
    240: function (e, t, n) {
        "use strict";
        var a = n(3),
            i = n(0),
            o = n.n(i);

        function r() {
            var e = Object(a.a)(["\n    display: flex;\n    height: 45px;\n    line-height: 45px;\n    background-color: ", ";\n    color: #fff;\n    & > div {\n        flex: 1;\n    }\n    > .navbar-left {\n        display: flex;\n        align-items: center;\n        padding-left: 10px;\n        a {\n            color: #fff;\n            display: flex;\n            align-items: center;\n        }\n    }\n    > .navbar-title {\n        display: -webkit-box;\n        -webkit-box-align: center;\n        -webkit-box-pack: center;\n        font-size: 18px;\n        justify-content: center;\n        white-space: nowrap;\n    }\n    > .navbar-right {\n        display: flex;\n        padding-right: 15px;\n        align-items: center;\n        justify-content: flex-end;\n    }\n"]);
            return r = function () {
                return e
            },
                e
        }

        var l = n(2).b.div(r(),
            function (e) {
                return e.background ? e.background : "#ff4500"
            });
        t.a = function (e) {
            var t = e.left,
                n = e.onLeftClick,
                a = e.children,
                i = e.right,
                r = e.background;
            return o.a.createElement(l, {
                    background: r
                },
                o.a.createElement("div", {
                        className: "navbar-left",
                        onClick: n
                    },
                    t), o.a.createElement("div", {
                        className: "navbar-title"
                    },
                    a), o.a.createElement("div", {
                        className: "navbar-right"
                    },
                    i))
        }
    },
    252: function (e, t, n) {
        "use strict";
        n.d(t, "g",
            function () {
                return o
            }),
            n.d(t, "f",
                function () {
                    return r
                }),
            n.d(t, "e",
                function () {
                    return l
                }),
            n.d(t, "a",
                function () {
                    return c
                }),
            n.d(t, "b",
                function () {
                    return u
                }),
            n.d(t, "d",
                function () {
                    return m
                }),
            n.d(t, "c",
                function () {
                    return s
                });
        var a = n(322),
            i = n.n(a),
            o = function (e) {
                var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 2;
                return Number(Math.round(e + "e" + t) + "e-" + t)
            },
            r = function () {
                var e = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0,
                    t = Number(e);
                return t >= 1e8 ? t = o(t / 1e8, 2) + "\u4ebf" : t >= 1e4 ? t = o(t / 1e4, 2) + "\u4e07" : t >= 1e3 && (t = t.toString().slice(0, 1) + "," + t.toString().slice(1)),
                    t
            },
            l = function (e) {
                return isNaN(e) ? "--" : 0 === e || e ? e : "--"
            },
            c = function (e) {
                return e.getFullYear() + "-" + i()(e.getMonth() + 1, 2, 0) + "-" + i()(e.getDate(), 2, 0)
            },
            u = function (e) {
                return e.getFullYear() + "-" + i()(e.getMonth() + 1, 2, 0) + "-" + i()(e.getDate(), 2, 0) + " " + e.getHours() + ":" + e.getMinutes() + ":" + e.getSeconds()
            },
            m = function (e) {
                return e.toString().substr(0, 3) + "****" + e.toString().substr(-3)
            },
            s = function (e, t) {
                var n = e.toString();
                switch (t) {
                    case "telephone":
                    case "mobile":
                        return n.substr(0, 3) + "****" + n.substr(-4);
                    case "idCard":
                        return n.substr(0, 8) + "****" + n.substr(-4);
                    case "bank":
                        return n.substr(0, 4) + "****" + n.substr(-4);
                    default:
                        return n
                }
            }
    },
    259: function (e, t, n) {
        "use strict";
        var a = n(3);

        function i() {
            var e = Object(a.a)(["\n    color: #ff4500;\n"]);
            return i = function () {
                return e
            },
                e
        }

        var o = n(2).b.span(i());
        t.a = o
    },
    268: function (e, t, n) {
        "use strict";
        var a = n(3),
            i = n(0),
            o = n.n(i);

        function r() {
            var e = Object(a.a)(["\n    margin-bottom: 20px;\n    &:last-child{\n        margin-bottom: 0;\n    }\n    .title{\n        font-size: 16px;\n        color: #252525;\n        line-height: 30px;\n        text-align: center;\n        margin: 6px 0;\n    }\n\n"]);
            return r = function () {
                return e
            },
                e
        }

        var l = n(2).b.div(r());
        t.a = function (e) {
            var t = e.title,
                n = e.children;
            return o.a.createElement(l, null, o.a.createElement("div", {
                    className: "title"
                },
                t), n)
        }
    },
    285: function (e, t, n) {
        "use strict";
        var a = n(3),
            i = n(2),
            o = n(323);

        function r() {
            var e = Object(a.a)(["\n    padding-left: 0;\n    border-radius: 4px;\n"]);
            return r = function () {
                return e
            },
                e
        }

        var l = Object(i.b)(o.a)(r());
        t.a = l
    },
    294: function (e, t, n) {
        "use strict";
        n(269);
        var a = n(270),
            i = n.n(a),
            o = n(34),
            r = n(35),
            l = n(37),
            c = n(36),
            u = n(38),
            m = n(0),
            s = n.n(m),
            p = n(306),
            f = n.n(p),
            d = function (e) {
                function t() {
                    var e, n;
                    Object(o.a)(this, t);
                    for (var a = arguments.length,
                             i = new Array(a), r = 0; r < a; r++) i[r] = arguments[r];
                    return (n = Object(l.a)(this, (e = Object(c.a)(t)).call.apply(e, [this].concat(i)))).state = {
                        show: !1
                    },
                        n
                }

                return Object(u.a)(t, e),
                    Object(r.a)(t, [{
                        key: "render",
                        value: function () {
                            var e = this,
                                t = this.props,
                                n = t.text,
                                a = t.info;
                            return s.a.createElement(m.Fragment, null, n, s.a.createElement("img", {
                                onClick: function () {
                                    e.setState({
                                        show: !0
                                    })
                                },
                                style: {
                                    width: "0.4rem",
                                    height: "0.4rem",
                                    position: "relative",
                                    left: "0.04rem"
                                },
                                src: f.a,
                                alt: "???"
                            }), s.a.createElement(i.a, {
                                    visible: this.state.show,
                                    transparent: !0,
                                    title: n,
                                    footer: [{
                                        text: "\u786e\u8ba4",
                                        onPress: function () {
                                            e.setState({
                                                show: !1
                                            })
                                        }
                                    }]
                                },
                                a))
                        }
                    }]),
                    t
            }(m.PureComponent);
        t.a = d
    },
    306: function (e, t) {
        e.exports = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAMAAADXqc3KAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAA3lBMVEWOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpOOjpMAAADAoxyZAAAASHRSTlMAHni54/jz17ghpvz6/U7ymUgUAxlGlFlv/p0VEppxV/ldWlsREKVF9kSnHxOWkXc17u84lQYE5GT7GEeYIJzbDVhcm3OXAiJc2JqmAAAAAWJLR0RJhwXkfAAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB+IGCA8mKs6VwKkAAAEUSURBVCjPZVLrWsIwDM02VyeuOm5qFF2BKTrQeR+ICKho3v+JXOk68kl/rOfkfEvSkwCY47jeji92A3cP+HFq+0QU6o+sOZv4wSGJqN6AZqt9VMBjGz+ReHpmSeec8MLAGFWX5+0pjNf5BfZNJLk0d5+ErnNFkeED//rGoIhSgKEUI0Nvie4MGgk5hMz+APcPj08lfKYMAqrbqi+vFrUpgJwalo7HVc+Ugx9WfSpVQV9xgcii5kTxVBvhrUjl0XRb0MUzet8WdLszKTr/heKBM4CUPko+nwOzhJm4WJQm4tpEWKL65LbHCpfVoL6qnpMI8ZuPdjVN4Kc10KPtsWVIZbEHaqKXIXV4Xr0+eRjmnvtbBv4A8tkhotnooa8AAAAASUVORK5CYII="
    },
    323: function (e, t, n) {
        "use strict";
        var a = n(3),
            i = n(0),
            o = n.n(i),
            r = n(2);

        function l() {
            var e = Object(a.a)(['\n    position: relative;\n    min-height: 44px;\n    display: flex;\n    align-items: center;\n    padding: 0 10px;\n    font-size: 14px;\n\n    :not(:last-child)::after {\n        content: " ";\n        position: absolute;\n        width: 100%;\n        height: 1px;\n        bottom: 0;\n        left: 0;\n        transform: scale(1, 0.5);\n        background-color: #e8e8e8;\n    }\n    > .title {\n        font-size: 16px;\n        flex: 1;\n        color: #252525;\n        @media (max-width: 330px){\n            font-size: 14px;\n        }\n    }\n    > .bd {\n        flex-basis: 65%;\n        color: #8e8e93;\n    }\n']);
            return l = function () {
                return e
            },
                e
        }

        function c() {
            var e = Object(a.a)(["\n    margin: 0 0 15px;\n    padding-left: 15px;\n    background-color: #fff;\n"]);
            return c = function () {
                return e
            },
                e
        }

        var u = r.b.div(c()),
            m = r.b.div(l());
        u.Item = function (e) {
            var t = e.title,
                n = e.onLeftClick,
                a = e.align,
                i = e.flexBasis,
                r = e.children;
            return o.a.createElement(m, null, o.a.createElement("div", {
                    className: "title",
                    onClick: n
                },
                t), o.a.createElement("div", {
                    className: "bd",
                    style: {
                        textAlign: a || "",
                        flexBasis: i ? "".concat(i) : ""
                    }
                },
                r))
        },
            t.a = u
    },
    366: function (e, t, n) {
        "use strict";
        n(137);
        var a = n(71),
            i = n.n(a),
            o = n(34),
            r = n(35),
            l = n(37),
            c = n(36),
            u = n(38),
            m = n(0),
            s = n.n(m),
            p = n(72),
            f = n.n(p),
            d = n(240),
            h = n(827),
            b = n(30),
            v = n(31),
            g = n.n(v),
            O = n(24),
            y = function (e) {
                function t() {
                    var e, n;
                    Object(o.a)(this, t);
                    for (var a = arguments.length,
                             i = new Array(a), r = 0; r < a; r++) i[r] = arguments[r];
                    return (n = Object(l.a)(this, (e = Object(c.a)(t)).call.apply(e, [this].concat(i)))).state = {
                        agreement: ""
                    },
                        n.componentDidMount = function () {
                            var e = n.props,
                                t = e.token,
                                a = e.match;
                            n._fetchData(a.params.id, t)
                        },
                        n
                }

                return Object(u.a)(t, e),
                    Object(r.a)(t, [{
                        key: "_fetchData",
                        value: function (e, t) {
                            var n = this;
                            g.a.post("".concat(b.lb), {
                                id: e,
                                token: t
                            }).then(function (e) {
                                n.setState({
                                    agreement: e.data.data
                                })
                            })
                        }
                    },
                        {
                            key: "render",
                            value: function () {
                                return s.a.createElement(f.a, {
                                        title: "\u64cd\u76d8\u534f\u8bae"
                                    },
                                    s.a.createElement(m.Fragment, null, s.a.createElement(d.a, {
                                            left: s.a.createElement(i.a, {
                                                type: "left",
                                                style: {
                                                    width: "30px",
                                                    height: "30px"
                                                }
                                            }),
                                            onLeftClick: function () {
                                                return window.history.go(-1)
                                            }
                                        },
                                        "\u64cd\u76d8\u534f\u8bae"), s.a.createElement("div", {
                                        style: {
                                            background: "#fff",
                                            padding: "10px 15px"
                                        },
                                        dangerouslySetInnerHTML: {
                                            __html: this.state.agreement
                                        }
                                    })))
                            }
                        }]),
                    t
            }(m.PureComponent);
        t.a = Object(h.a)(Object(O.b)(function (e) {
            return {
                token: e.token
            }
        })(y))
    },
    818: function (e, t, n) {
        "use strict";
        n.r(t);
        var a = n(0),
            i = n.n(a),
            o = (n(245), n(246)),
            r = n.n(o),
            l = (n(137), n(71)),
            c = n.n(l),
            u = (n(247), n(248)),
            m = n.n(u),
            s = (n(241), n(242)),
            p = n.n(s),
            f = n(34),
            d = n(35),
            h = n(37),
            b = n(36),
            v = n(38),
            g = n(24),
            O = n(487),
            y = n(827),
            x = n(72),
            E = n.n(x),
            j = n(3),
            k = n(268),
            S = n(2);

        function A() {
            var e = Object(j.a)(["\n    font-size: 16px;\n    color: #252525;\n    padding: 5px 8px;\n    text-align: center;\n    line-height: 30px;\n    border: 1px solid #e8e8e8;\n    border-radius: 8px;\n    width: 100%;\n    box-sizing: border-box;\n    &::-webkit-input-placeholder {\n        color: #c7c7c7;\n    }\n"]);
            return A = function () {
                return e
            },
                e
        }

        var M = S.b.input(A()),
            D = function (e) {
                function t() {
                    var e, n;
                    Object(f.a)(this, t);
                    for (var a = arguments.length,
                             i = new Array(a), o = 0; o < a; o++) i[o] = arguments[o];
                    return (n = Object(h.a)(this, (e = Object(b.a)(t)).call.apply(e, [this].concat(i)))).state = {
                        money: ""
                    },
                        n
                }

                return Object(v.a)(t, e),
                    Object(d.a)(t, [{
                        key: "render",
                        value: function () {
                            var e = this,
                                t = this.props,
                                n = t.max,
                                a = t.min,
                                o = t.onChange;
                            return i.a.createElement(k.a, {
                                    title: "\u8bf7\u8f93\u5165\u4fdd\u8bc1\u91d1\u91d1\u989d"
                                },
                                i.a.createElement(M, {
                                    type: "number",
                                    onKeyUp: function () {
                                        o(e.money.value)
                                    },
                                    placeholder: "\u4fdd\u8bc1\u91d1\u4ecb\u4e8e ".concat(a || "0", " - ").concat(n || "0", " \u5143\u4e4b\u95f4"),
                                    ref: function (t) {
                                        return e.money = t
                                    }
                                }))
                        }
                    }]),
                    t
            }(i.a.PureComponent),
            w = (n(284), n(293)),
            _ = n.n(w);

        function C() {
            var e = Object(j.a)(["\n    box-sizing: border-box;\n    background-color: #fff;\n    border-radius: 8px;\n    position: relative;\n    border: 1px solid #e8e8e8;\n    padding: 5px 8px;\n    text-align: center;\n    line-height: 30px;\n    width: 100%;\n    line-height: 30px;\n    font-size: 16px;\n    color: #5a5a62;\n"]);
            return C = function () {
                return e
            },
                e
        }

        var R = S.b.button(C()),
            z = function (e) {
                var t = e.title,
                    n = e.placeholder,
                    a = e.items,
                    o = e.activeItem,
                    r = e.suffix,
                    l = e.readOnly,
                    u = e.onSelectItem;
                return i.a.createElement(k.a, {
                        title: t
                    },
                    i.a.createElement(_.a, {
                            data: a,
                            cols: 1,
                            onChange: function (e) {
                                return u(e)
                            },
                            disabled: l
                        },
                        i.a.createElement(R, null, o ? o + r : n, " ", l ? null : i.a.createElement(c.a, {
                            type: "down",
                            size: "xxs"
                        }))))
            },
            N = n(78),
            I = n(252);

        function F() {
            var e = Object(j.a)(["\n    width: 30%;\n    background-color: #e8e8e8;\n    color: #8e8e93;\n    font-size: 14px;\n    border-radius: 5px;\n    text-align: center;\n    margin-bottom: 15px;\n    padding: 20px 0;\n    .hd {\n        color: #8e8e93;\n    }\n    .num {\n        font-size: 16px;\n        line-height: 20px;\n    }\n"]);
            return F = function () {
                return e
            },
                e
        }

        function P() {
            var e = Object(j.a)(["\n    width: 30%;\n    background-color: #fff;\n    color: #8e8e93;\n    font-size: 14px;\n    border-radius: 5px;\n    text-align: center;\n    margin-bottom: 15px;\n    padding: 20px 0;\n    border: 1px solid #eaeaea;\n    &.active {\n        border-color: #ff4500;\n        color: #ff4500;\n        .hd {\n            color: inherit;\n        }\n    }\n    .hd {\n        font-size: 18px;\n        line-height: 30px;\n        color: #252525;\n    }\n    .num {\n        font-size: 16px;\n        line-height: 20px;\n    }\n"]);
            return P = function () {
                return e
            },
                e
        }

        var L = S.b.div(P()),
            V = Object(S.b)(L)(F()),
            B = function (e) {
                var t = e.ratio,
                    n = e.active,
                    a = e.money,
                    o = e.onSelect;
                return i.a.createElement(L, {
                        className: n ? "active" : null,
                        onClick: function () {
                            return o(t)
                        }
                    },
                    i.a.createElement("div", {
                            className: "hd"
                        },
                        Object(I.f)(t * a)), i.a.createElement("div", {
                            className: "num"
                        },
                        t, "\u500d"))
            },
            U = function (e) {
                var t = e.ratio;
                return i.a.createElement(V, null, i.a.createElement("div", {
                        className: "hd"
                    },
                    "\u914d\u8d44\u91d1\u989d"), i.a.createElement("div", {
                        className: "num"
                    },
                    t, "\u500d"))
            },
            K = function (e) {
                var t = e.ratio,
                    n = e.money,
                    a = Object(N.a)(e, ["ratio", "money"]);
                return n ? i.a.createElement(B, Object.assign({
                        money: n,
                        ratio: t
                    },
                    a)) : i.a.createElement(U, {
                    ratio: t
                })
            };

        function J() {
            var e = Object(j.a)(["\n    display: flex;\n    flex-wrap: wrap;\n    justify-content: space-between;\n"]);
            return J = function () {
                return e
            },
                e
        }

        var G = S.b.div(J()),
            X = function (e) {
                var t = e.rate,
                    n = e.money,
                    a = e.onSelect,
                    o = e.activeMultiple,
                    r = e.readOnly;
                return i.a.createElement(k.a, {
                        title: r ? "\u914d\u8d44\u91d1\u989d" : "\u8bf7\u9009\u62e9\u914d\u8d44\u91d1\u989d"
                    },
                    i.a.createElement(G, null, t.map(function (e) {
                        return i.a.createElement(K, {
                            key: e,
                            active: e === o,
                            ratio: e,
                            money: n,
                            onSelect: a
                        })
                    })))
            },
            W = n(240);

        function q() {
            var e = Object(j.a)(["\n    position: fixed;\n    width: 100%;\n    bottom: 0;\n    left: 0;\n    height: 47px;\n    & > div{\n    }\n"]);
            return q = function () {
                return e
            },
                e
        }

        var H = S.b.div.attrs({
                className: "footer-fixed"
            })(q()),
            T = function (e) {
                var t = e.children;
                return i.a.createElement(H, null, t)
            },
            Y = n(74),
            Q = n(813);

        function Z() {
            var e = Object(j.a)(['\n    position: relative;\n    overflow: hidden;\n    padding-bottom: 5px;\n    &::before {\n        content: "";\n        width: 100%;\n        height: 50%;\n        background-color: #ff4500;\n        position: absolute;\n        top: 0;\n        left: 0;\n        z-index: 0;\n    }\n']);
            return Z = function () {
                return e
            },
                e
        }

        var $ = S.b.div(Z());

        function ee() {
            var e = Object(j.a)(["\n    position: relative;\n    z-index: 1;\n    display: flex;\n    margin: 0 15px;\n    padding: 0.3rem 0.4rem;\n    background-color: #fff;\n    border-radius: 10px;\n    display: flex;\n    box-shadow: 0 0 4px rgba(0, 0, 0, 0.3);\n    .item {\n        flex: 1;\n        text-align: center;\n        a {\n            display: inline-block;\n            font-size: 0.3733rem;\n            color: #ff4500;\n            border: 1px solid #ff4500;\n            line-height: 0.7733rem;\n            border-radius: 5px;\n            padding: 0 0.1067rem;\n            &.active {\n                background-color: #ff4500;\n                color: #fff;\n            }\n        }\n    }\n"]);
            return ee = function () {
                return e
            },
                e
        }

        var te = S.b.div(ee()),
            ne = Object(y.a)(function (e) {
                e.match;
                return i.a.createElement($, null, i.a.createElement(te, null, i.a.createElement("div", {
                        className: "item"
                    },
                    i.a.createElement(Q.a, {
                            to: "/peizi/day"
                        },
                        "\u6309\u5929\u914d\u8d44")), i.a.createElement("div", {
                        className: "item"
                    },
                    i.a.createElement(Q.a, {
                            to: "/peizi/month"
                        },
                        "\u6309\u6708\u914d\u8d44")), i.a.createElement("div", {
                        className: "item"
                    },
                    i.a.createElement(Q.a, {
                            to: "/peizi/free"
                        },
                        "\u514d\u606f\u914d\u8d44"))))
            }),
            ae = n(31),
            ie = n.n(ae),
            oe = n(30),
            re = 1,
            le = function (e) {
                function t(e) {
                    var n;
                    return Object(f.a)(this, t),
                        (n = Object(h.a)(this, Object(b.a)(t).call(this, e))).state = {
                            money: null,
                            accountMoney: 0,
                            moneyRange: [],
                            duration: [],
                            activeDuration: null,
                            multiple: [],
                            activeMultiple: null,
                            maxDuration: 0,
                            minDuration: 0,
                            rate: [],
                            multipleRate: [],
                            lineSetting: [],
                            position: []
                        },
                        n.onMoneyChange = function (e) {
                            n.setState({
                                money: parseInt(e, 10)
                            })
                        },
                        n.onSelectMultiple = function (e) {
                            n.setState({
                                activeMultiple: e
                            })
                        },
                        n.onSelectDuration = function (e) {
                            n.setState({
                                activeDuration: e[0]
                            })
                        },
                        n.onSubmit = function () {
                            var e = n.props,
                                t = e.token,
                                a = e.history,
                                i = n.state,
                                o = i.activeMultiple,
                                r = i.activeDuration,
                                l = i.money,
                                c = i.lineSetting,
                                u = i.accountMoney,
                                m = i.multipleRate,
                                s = i.position;
                            n._checkSubmit() && (p.a.loading(null, 0), ie.a.post("".concat(oe.kb), {
                                token: t,
                                type: re,
                                multiple: o,
                                borrow_duration: r,
                                deposit_money: l
                            }).then(function (e) {
                                p.a.hide(),
                                    "1" === e.data.status ? a.push({
                                        pathname: "/peizi/confirm",
                                        state: {
                                            money: l,
                                            activeDuration: r,
                                            activeMultiple: o,
                                            type: re,
                                            lineSetting: c,
                                            accountMoney: u,
                                            rate: m[o],
                                            position: s
                                        }
                                    }) : p.a.info(e.data.message)
                            }).catch(function (e) {
                                p.a.hide()
                            }))
                        },
                        n._checkSubmit = function () {
                            var e = n.state,
                                t = e.money,
                                a = e.moneyRange,
                                i = e.activeMultiple,
                                o = e.activeDuration;
                            return null === t ? (p.a.info("\u8bf7\u8f93\u5165\u4fdd\u8bc1\u91d1\u91d1\u989d", 1, null, !1), !1) : t < a[0] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u5c11\u4e3a".concat(a[0], "\u5143"), 1, null, !1), !1) : t > a[1] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u591a\u4e3a".concat(a[1], "\u5143"), 1, null, !1), !1) : t % a[2] !== 0 ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u5fc5\u987b\u662f".concat(a[2], "\u7684\u6574\u6570\u500d"), 1, null, !1), !1) : null === o ? (p.a.info("\u8bf7\u9009\u62e9\u64cd\u76d8\u671f\u9650", 1, null, !1), !1) : null !== i || (p.a.info("\u8bf7\u9009\u62e9\u914d\u8d44\u91d1\u989d", 1, null, !1), !1)
                        },
                        n._fetchPageData(e.token),
                        n
                }

                return Object(v.a)(t, e),
                    Object(d.a)(t, [{
                        key: "_fetchPageData",
                        value: function (e) {
                            var t = this;
                            ie.a.post("".concat(oe.cb), {
                                token: e
                            }).then(function (e) {
                                var n = e.data.data,
                                    a = n.money_range,
                                    i = n.day_rate_a,
                                    o = n.account_money,
                                    r = n.day_loss,
                                    l = n.day_use_time,
                                    c = n.max_use_time,
                                    u = n.min_use_time,
                                    m = n.day_rate,
                                    s = n.position;
                                t.setState({
                                    accountMoney: o,
                                    moneyRange: a,
                                    maxDuration: c,
                                    minDuration: u,
                                    duration: l,
                                    lineSetting: r,
                                    multiple: i,
                                    rate: i,
                                    multipleRate: m,
                                    position: s
                                })
                            })
                        }
                    },
                        {
                            key: "render",
                            value: function () {
                                var e = this.state,
                                    t = e.money,
                                    n = e.moneyRange,
                                    o = e.maxDuration,
                                    l = e.minDuration,
                                    u = e.duration,
                                    s = e.activeDuration,
                                    p = e.activeMultiple,
                                    f = e.rate,
                                    d = u.map(function (e) {
                                        return {
                                            value: e,
                                            label: e + "\u5929"
                                        }
                                    });
                                return i.a.createElement(E.a, {
                                        title: "\u6309\u5929\u914d\u8d44"
                                    },
                                    i.a.createElement(a.Fragment, null, i.a.createElement(T, null, i.a.createElement(m.a, {
                                            type: "primary",
                                            onClick: this.onSubmit,
                                            style: {
                                                borderRadius: 0,
                                                background: "#FE4500",
                                                border: "none",
                                                boxShadow: "none"
                                            }
                                        },
                                        "\u4e0b\u4e00\u6b65")), i.a.createElement(Y.a, null, i.a.createElement(W.a, {
                                            left: i.a.createElement(O.a, {
                                                    to: "/"
                                                },
                                                i.a.createElement(c.a, {
                                                    type: "left",
                                                    style: {
                                                        width: "30px",
                                                        height: "30px"
                                                    }
                                                }))
                                        },
                                        "\u6309\u5929\u914d\u8d44"), i.a.createElement(ne, null), i.a.createElement(r.a, null, i.a.createElement(D, {
                                        max: n[1],
                                        min: n[0],
                                        step: n[2],
                                        onChange: this.onMoneyChange
                                    }), i.a.createElement(z, {
                                        title: "\u8bf7\u9009\u62e9\u64cd\u76d8\u671f\u9650",
                                        suffix: "\u5929",
                                        items: d,
                                        placeholder: "\u64cd\u76d8\u671f\u9650\u4ecb\u4e8e ".concat(l, " - ").concat(o, " \u5929\u4e4b\u95f4"),
                                        onSelectItem: this.onSelectDuration,
                                        activeItem: s
                                    }), i.a.createElement(X, {
                                        rate: f,
                                        money: t,
                                        onSelect: this.onSelectMultiple,
                                        activeMultiple: p
                                    })))))
                            }
                        }]),
                    t
            }(a.PureComponent),
            ce = Object(y.a)(Object(g.b)(function (e) {
                return {
                    token: e.token,
                    isLogin: e.isLogin
                }
            })(le)),
            ue = 2,
            me = function (e) {
                function t(e) {
                    var n;
                    return Object(f.a)(this, t),
                        (n = Object(h.a)(this, Object(b.a)(t).call(this, e))).state = {
                            money: null,
                            accountMoney: 0,
                            moneyRange: [],
                            duration: [],
                            activeDuration: null,
                            multiple: [],
                            activeMultiple: null,
                            maxDuration: 0,
                            minDuration: 0,
                            rate: [],
                            multipleRate: [],
                            lineSetting: [],
                            position: []
                        },
                        n.onMoneyChange = function (e) {
                            n.setState({
                                money: parseInt(e, 10)
                            })
                        },
                        n.onSelectMultiple = function (e) {
                            n.setState({
                                activeMultiple: e
                            })
                        },
                        n.onSelectDuration = function (e) {
                            n.setState({
                                activeDuration: e[0]
                            })
                        },
                        n.onSubmit = function () {
                            var e = n.props,
                                t = e.token,
                                a = e.history,
                                i = n.state,
                                o = i.activeMultiple,
                                r = i.activeDuration,
                                l = i.money,
                                c = i.lineSetting,
                                u = i.accountMoney,
                                m = i.multipleRate,
                                s = i.position;
                            n._checkSubmit() && (p.a.loading(null, 0), ie.a.post("".concat(oe.kb), {
                                token: t,
                                type: ue,
                                multiple: o,
                                borrow_duration: r,
                                deposit_money: l
                            }).then(function (e) {
                                p.a.hide(),
                                    "1" === e.data.status ? a.push({
                                        pathname: "/peizi/confirm",
                                        state: {
                                            money: l,
                                            activeDuration: r,
                                            activeMultiple: o,
                                            type: ue,
                                            lineSetting: c,
                                            accountMoney: u,
                                            rate: m[o],
                                            position: s
                                        }
                                    }) : p.a.info(e.data.message)
                            }).catch(function (e) {
                                p.a.hide()
                            }))
                        },
                        n._checkSubmit = function () {
                            var e = n.state,
                                t = e.money,
                                a = e.moneyRange,
                                i = e.activeMultiple,
                                o = e.activeDuration;
                            return null === t ? (p.a.info("\u8bf7\u8f93\u5165\u4fdd\u8bc1\u91d1\u91d1\u989d", 1, null, !1), !1) : t < a[0] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u5c11\u4e3a".concat(a[0], "\u5143"), 1, null, !1), !1) : t > a[1] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u591a\u4e3a".concat(a[1], "\u5143"), 1, null, !1), !1) : t % a[2] !== 0 ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u5fc5\u987b\u662f".concat(a[2], "\u7684\u6574\u6570\u500d"), 1, null, !1), !1) : null === o ? (p.a.info("\u8bf7\u9009\u62e9\u64cd\u76d8\u671f\u9650", 1, null, !1), !1) : null !== i || (p.a.info("\u8bf7\u9009\u62e9\u914d\u8d44\u91d1\u989d", 1, null, !1), !1)
                        },
                        n._fetchPageData(e.token),
                        n
                }

                return Object(v.a)(t, e),
                    Object(d.a)(t, [{
                        key: "_fetchPageData",
                        value: function (e) {
                            var t = this;
                            ie.a.post("".concat(oe.ib), {
                                token: e
                            }).then(function (e) {
                                var n = e.data.data,
                                    a = n.money_range,
                                    i = n.week_rate_a,
                                    o = n.account_money,
                                    r = n.week_loss,
                                    l = n.week_use_time,
                                    c = n.max_use_time,
                                    u = n.min_use_time,
                                    m = n.week_rate,
                                    s = n.position;
                                t.setState({
                                    accountMoney: o,
                                    moneyRange: a,
                                    maxDuration: c,
                                    minDuration: u,
                                    duration: l,
                                    lineSetting: r,
                                    multiple: i,
                                    rate: i,
                                    multipleRate: m,
                                    position: s
                                })
                            })
                        }
                    },
                        {
                            key: "render",
                            value: function () {
                                var e = this.state,
                                    t = e.money,
                                    n = e.moneyRange,
                                    o = e.maxDuration,
                                    l = e.minDuration,
                                    u = e.duration,
                                    s = e.activeDuration,
                                    p = e.activeMultiple,
                                    f = e.rate,
                                    d = u.map(function (e) {
                                        return {
                                            value: e,
                                            label: e + "\u5468"
                                        }
                                    });
                                return i.a.createElement(E.a, {
                                        title: "\u6309\u5468\u914d\u8d44"
                                    },
                                    i.a.createElement(a.Fragment, null, i.a.createElement(T, null, i.a.createElement(m.a, {
                                            type: "primary",
                                            onClick: this.onSubmit,
                                            style: {
                                                borderRadius: 0,
                                                background: "#FE4500",
                                                border: "none",
                                                boxShadow: "none"
                                            }
                                        },
                                        "\u4e0b\u4e00\u6b65")), i.a.createElement(Y.a, null, i.a.createElement(W.a, {
                                            left: i.a.createElement(O.a, {
                                                    to: "/"
                                                },
                                                i.a.createElement(c.a, {
                                                    type: "left",
                                                    style: {
                                                        width: "30px",
                                                        height: "30px"
                                                    }
                                                }))
                                        },
                                        "\u6309\u5468\u914d\u8d44"), i.a.createElement(ne, null), i.a.createElement(r.a, null, i.a.createElement(D, {
                                        max: n[1],
                                        min: n[0],
                                        step: n[2],
                                        onChange: this.onMoneyChange
                                    }), i.a.createElement(z, {
                                        title: "\u8bf7\u9009\u62e9\u64cd\u76d8\u671f\u9650",
                                        suffix: "\u5468",
                                        items: d,
                                        placeholder: "\u64cd\u76d8\u671f\u9650\u4ecb\u4e8e ".concat(l, " - ").concat(o, " \u5468\u4e4b\u95f4"),
                                        onSelectItem: this.onSelectDuration,
                                        activeItem: s
                                    }), i.a.createElement(X, {
                                        rate: f,
                                        money: t,
                                        onSelect: this.onSelectMultiple,
                                        activeMultiple: p
                                    })))))
                            }
                        }]),
                    t
            }(a.PureComponent),
            se = Object(y.a)(Object(g.b)(function (e) {
                return {
                    token: e.token,
                    isLogin: e.isLogin
                }
            })(me)),
            pe = 3,
            fe = function (e) {
                function t(e) {
                    var n;
                    return Object(f.a)(this, t),
                        (n = Object(h.a)(this, Object(b.a)(t).call(this, e))).state = {
                            money: null,
                            accountMoney: 0,
                            moneyRange: [],
                            duration: [],
                            activeDuration: null,
                            multiple: [],
                            activeMultiple: null,
                            maxDuration: 0,
                            minDuration: 0,
                            rate: [],
                            multipleRate: [],
                            lineSetting: [],
                            position: []
                        },
                        n.onMoneyChange = function (e) {
                            n.setState({
                                money: parseInt(e, 10)
                            })
                        },
                        n.onSelectMultiple = function (e) {
                            n.setState({
                                activeMultiple: e
                            })
                        },
                        n.onSelectDuration = function (e) {
                            n.setState({
                                activeDuration: e[0]
                            })
                        },
                        n.onSubmit = function () {
                            var e = n.props,
                                t = e.token,
                                a = e.history,
                                i = n.state,
                                o = i.activeMultiple,
                                r = i.activeDuration,
                                l = i.money,
                                c = i.lineSetting,
                                u = i.accountMoney,
                                m = i.multipleRate,
                                s = i.position;
                            n._checkSubmit() && (p.a.loading(null, 0), ie.a.post("".concat(oe.kb), {
                                token: t,
                                type: pe,
                                multiple: o,
                                borrow_duration: r,
                                deposit_money: l
                            }).then(function (e) {
                                p.a.hide(),
                                    "1" === e.data.status ? a.push({
                                        pathname: "/peizi/confirm",
                                        state: {
                                            money: l,
                                            activeDuration: r,
                                            activeMultiple: o,
                                            type: pe,
                                            lineSetting: c,
                                            accountMoney: u,
                                            rate: m[o],
                                            position: s
                                        }
                                    }) : p.a.info(e.data.message)
                            }).catch(function (e) {
                                p.a.hide()
                            }))
                        },
                        n._checkSubmit = function () {
                            var e = n.state,
                                t = e.money,
                                a = e.moneyRange,
                                i = e.activeMultiple,
                                o = e.activeDuration;
                            return null === t ? (p.a.info("\u8bf7\u8f93\u5165\u4fdd\u8bc1\u91d1\u91d1\u989d", 1, null, !1), !1) : t < a[0] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u5c11\u4e3a".concat(a[0], "\u5143"), 1, null, !1), !1) : t > a[1] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u591a\u4e3a".concat(a[1], "\u5143"), 1, null, !1), !1) : t % a[2] !== 0 ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u5fc5\u987b\u662f".concat(a[2], "\u7684\u6574\u6570\u500d"), 1, null, !1), !1) : null === o ? (p.a.info("\u8bf7\u9009\u62e9\u64cd\u76d8\u671f\u9650", 1, null, !1), !1) : null !== i || (p.a.info("\u8bf7\u9009\u62e9\u914d\u8d44\u91d1\u989d", 1, null, !1), !1)
                        },
                        n._fetchPageData(e.token),
                        n
                }

                return Object(v.a)(t, e),
                    Object(d.a)(t, [{
                        key: "_fetchPageData",
                        value: function (e) {
                            var t = this;
                            ie.a.post("".concat(oe.gb), {
                                token: e
                            }).then(function (e) {
                                var n = e.data.data,
                                    a = n.money_range,
                                    i = n.month_rate_a,
                                    o = n.account_money,
                                    r = n.month_loss,
                                    l = n.month_use_time,
                                    c = n.max_use_time,
                                    u = n.min_use_time,
                                    m = n.month_rate,
                                    s = n.position;
                                t.setState({
                                    accountMoney: o,
                                    moneyRange: a,
                                    maxDuration: c,
                                    minDuration: u,
                                    duration: l,
                                    lineSetting: r,
                                    multiple: i,
                                    rate: i,
                                    multipleRate: m,
                                    position: s
                                })
                            })
                        }
                    },
                        {
                            key: "render",
                            value: function () {
                                var e = this.state,
                                    t = e.money,
                                    n = e.moneyRange,
                                    o = e.maxDuration,
                                    l = e.minDuration,
                                    u = e.duration,
                                    s = e.activeDuration,
                                    p = e.activeMultiple,
                                    f = e.rate,
                                    d = u.map(function (e) {
                                        return {
                                            value: e,
                                            label: e + "\u6708"
                                        }
                                    });
                                return i.a.createElement(E.a, {
                                        title: "\u6309\u6708\u914d\u8d44"
                                    },
                                    i.a.createElement(a.Fragment, null, i.a.createElement(T, null, i.a.createElement(m.a, {
                                            type: "primary",
                                            onClick: this.onSubmit,
                                            style: {
                                                borderRadius: 0,
                                                background: "#FE4500",
                                                border: "none",
                                                boxShadow: "none"
                                            }
                                        },
                                        "\u4e0b\u4e00\u6b65")), i.a.createElement(Y.a, null, i.a.createElement(W.a, {
                                            left: i.a.createElement(O.a, {
                                                    to: "/"
                                                },
                                                i.a.createElement(c.a, {
                                                    type: "left",
                                                    style: {
                                                        width: "30px",
                                                        height: "30px"
                                                    }
                                                }))
                                        },
                                        "\u6309\u6708\u914d\u8d44"), i.a.createElement(ne, null), i.a.createElement(r.a, null, i.a.createElement(D, {
                                        max: n[1],
                                        min: n[0],
                                        step: n[2],
                                        onChange: this.onMoneyChange
                                    }), i.a.createElement(z, {
                                        title: "\u8bf7\u9009\u62e9\u64cd\u76d8\u671f\u9650",
                                        suffix: "\u6708",
                                        items: d,
                                        placeholder: "\u64cd\u76d8\u671f\u9650\u4ecb\u4e8e ".concat(l, " - ").concat(o, " \u6708\u4e4b\u95f4"),
                                        onSelectItem: this.onSelectDuration,
                                        activeItem: s
                                    }), i.a.createElement(X, {
                                        rate: f,
                                        money: t,
                                        onSelect: this.onSelectMultiple,
                                        activeMultiple: p
                                    })))))
                            }
                        }]),
                    t
            }(a.PureComponent),
            de = Object(y.a)(Object(g.b)(function (e) {
                return {
                    token: e.token,
                    isLogin: e.isLogin
                }
            })(fe)),
            he = (n(243), n(244)),
            be = n.n(he);

        function ve() {
            var e = Object(j.a)(["\n    position: relative;\n    z-index: 1;\n    display: flex;\n    margin: 0 15px;\n    padding: 0.3rem 0.4rem;\n    background-color: #fff;\n    border-radius: 10px;\n    display: flex;\n    box-shadow: 0 0 4px rgba(0, 0, 0, 0.3);\n    .item {\n        flex: 1;\n        text-align: center;\n        border-right: 1px solid #E2E2E2;\n        .hd{\n            color: #8e8e93;\n            font-size: 15px;\n        }\n        .num{\n            font-size: 17px;\n            color: #FF4500;\n        }\n        &:last-child {\n            border-right: none;\n        }\n    }\n"]);
            return ve = function () {
                return e
            },
                e
        }

        var ge = S.b.div(ve()),
            Oe = function (e) {
                var t = e.total,
                    n = e.depositMoney,
                    a = e.gainMoney;
                return i.a.createElement($, null, i.a.createElement(ge, null, i.a.createElement("div", {
                        className: "item"
                    },
                    i.a.createElement("div", {
                            className: "hd"
                        },
                        "\u603b\u64cd\u76d8\u8d44\u91d1"), i.a.createElement("div", {
                            className: "num"
                        },
                        t, "\u5143")), i.a.createElement("div", {
                        className: "item"
                    },
                    i.a.createElement("div", {
                            className: "hd"
                        },
                        "\u4fdd\u8bc1\u91d1"), i.a.createElement("div", {
                            className: "num"
                        },
                        n, "\u5143")), i.a.createElement("div", {
                        className: "item"
                    },
                    i.a.createElement("div", {
                            className: "hd"
                        },
                        "\u914d\u8d44\u91d1\u989d"), i.a.createElement("div", {
                            className: "num"
                        },
                        a, "\u5143"))))
            },
            ye = (n(517), n(520)),
            xe = n.n(ye);

        function Ee() {
            var e = Object(j.a)(["\n    font-size: 14px;\n    .link {\n        padding-left: 10px;\n        color: #8e8e93;\n    }\n    a {\n        color: #459df5;\n    }\n\n    .am-checkbox.am-checkbox-checked .am-checkbox-inner {\n        background-color: #ff4500;\n        border-color: #ff4500;\n    }\n"]);
            return Ee = function () {
                return e
            },
                e
        }

        var je = S.b.div(Ee()),
            ke = function (e) {
                return i.a.createElement(je, null, i.a.createElement(xe.a, e), i.a.createElement("span", {
                        className: "link"
                    },
                    "\u6211\u5df2\u9605\u8bfb\u5e76\u540c\u610f", " ", i.a.createElement(O.a, {
                            to: "/agreement/caopan"
                        },
                        "\u300a\u5b9e\u76d8\u4ea4\u6613\u5e73\u53f0\u64cd\u76d8\u534f\u8bae\u300b")))
            },
            Se = n(285),
            Ae = n(294),
            Me = n(259);

        function De() {
            var e = Object(j.a)(["\n    color: #fff;\n    background-color: #fa4400;\n    border-radius: 3px;\n    padding: 0 3px;\n    font-size: 13px;\n    display: inline-block;\n    line-height: 20px;\n    margin-left: 20px;\n    position: relative;\n    top: -2px;\n"]);
            return De = function () {
                return e
            },
                e
        }

        function we() {
            var e = Object(j.a)(["\n    font-size: 13px;\n"]);
            return we = function () {
                return e
            },
                e
        }

        var _e = function (e) {
                function t() {
                    var e, n;
                    Object(f.a)(this, t);
                    for (var a = arguments.length,
                             i = new Array(a), o = 0; o < a; o++) i[o] = arguments[o];
                    return (n = Object(h.a)(this, (e = Object(b.a)(t)).call.apply(e, [this].concat(i)))).state = {
                        agreement: !0
                    },
                        n.onSubmit = function () {
                            var e = n.props,
                                t = e.token,
                                a = e.location,
                                i = e.history,
                                o = a.state;
                            n._checkSubmit() && ie.a.post("".concat(oe.jb), {
                                token: t,
                                type: o.type,
                                multiple: o.activeMultiple,
                                borrow_duration: o.activeDuration,
                                deposit_money: o.money
                            }).then(function (e) {
                                "1" === e.data.status ? p.a.success("\u7533\u8bf7\u6210\u529f", 1,
                                    function () {
                                        i.push("/member/peizi/list/index")
                                    }) : p.a.fail(e.data.message)
                            }).catch(function (e) {
                                p.a.fail("\u7533\u8bf7\u5931\u8d25\uff0c\u8bf7\u8054\u7cfb\u7ba1\u7406\u5458")
                            })
                        },
                        n._checkSubmit = function () {
                            return !!n.state.agreement || (p.a.info("\u8bf7\u9605\u8bfb\u5e76\u540c\u610f\u64cd\u76d8\u534f\u8bae", 1, null, !1), !1)
                        },
                        n.onChangeAgreement = function (e) {
                            n.setState({
                                agreement: e.target.checked
                            })
                        },
                        n
                }

                return Object(v.a)(t, e),
                    Object(d.a)(t, [{
                        key: "render",
                        value: function () {
                            var e, t = this.props.location.state,
                                n = 0,
                                o = 0;
                            switch (t.type) {
                                case 1:
                                    e = "\u5929",
                                        n = (o = t.money * t.activeMultiple * t.activeDuration * t.rate / 100) + t.money;
                                    break;
                                case 2:
                                    e = "\u5468",
                                        n = (o = t.money * t.activeMultiple * t.activeDuration * t.rate / 100) + t.money;
                                    break;
                                case 3:
                                    e = "\u6708",
                                        n = t.money * t.activeMultiple * t.rate / 100 + t.money,
                                        o = t.money * t.activeMultiple * t.rate / 100;
                                    break;
                                default:
                                    n = t.money * t.activeMultiple * t.activeDuration * t.rate / 100 + t.money,
                                        o = 0,
                                        e = "\u5929"
                            }
                            var l = t.position[t.activeMultiple];
                            return i.a.createElement(E.a, {
                                    title: "\u786e\u8ba4\u7533\u8bf7"
                                },
                                i.a.createElement(a.Fragment, null, i.a.createElement(T, null, i.a.createElement(m.a, {
                                        type: "primary",
                                        onClick: this.onSubmit,
                                        style: {
                                            borderRadius: 0,
                                            background: "#FE4500",
                                            border: "none",
                                            boxShadow: "none"
                                        }
                                    },
                                    "\u63d0\u4ea4\u7533\u8bf7\u4fe1\u606f")), i.a.createElement(Y.a, null, i.a.createElement(W.a, {
                                        left: i.a.createElement(c.a, {
                                            type: "left",
                                            style: {
                                                width: "30px",
                                                height: "30px"
                                            }
                                        }),
                                        onLeftClick: function () {
                                            return window.history.go(-1)
                                        }
                                    },
                                    "\u786e\u8ba4\u7533\u8bf7"), i.a.createElement(Oe, {
                                    depositMoney: t.money,
                                    gainMoney: t.money * t.activeMultiple,
                                    total: t.money * (+t.activeMultiple + 1)
                                }), i.a.createElement(be.a, null), i.a.createElement(r.a, null, i.a.createElement(Se.a, null, i.a.createElement(Se.a.Item, {
                                        title: i.a.createElement(Ae.a, {
                                            text: "\u9884\u8b66\u7ebf",
                                            info: "\u914d\u8d44\u8d44\u91d1 + \u4fdd\u8bc1\u91d1 X \u9884\u8b66\u7ebf\u6bd4\u4f8b"
                                        })
                                    },
                                    i.a.createElement(Me.a, null, t.money * t.activeMultiple + t.money * t.lineSetting[0] / 100, "\u5143"), i.a.createElement(Re, null, "(\u914d\u8d44\u8d44\u91d1+\u4fdd\u8bc1\u91d1 x", " ", t.lineSetting[0], "%)")), i.a.createElement(Se.a.Item, {
                                        title: i.a.createElement(Ae.a, {
                                            text: "\u5e73\u4ed3\u7ebf",
                                            info: "\u914d\u8d44\u8d44\u91d1 + \u4fdd\u8bc1\u91d1 X \u5e73\u4ed3\u7ebf\u6bd4\u4f8b"
                                        })
                                    },
                                    i.a.createElement(Me.a, null, t.money * t.activeMultiple + t.money * t.lineSetting[1] / 100, "\u5143", " "), i.a.createElement(Re, null, "(\u914d\u8d44\u8d44\u91d1+\u4fdd\u8bc1\u91d1 x", " ", t.lineSetting[1], "%)")), i.a.createElement(Se.a.Item, {
                                        title: "\u64cd\u76d8\u65f6\u95f4"
                                    },
                                    i.a.createElement(Me.a, null, t.activeDuration, " ", e), 5 !== t.type && 4 !== t.type ? i.a.createElement(Re, null, "(\u9ed8\u8ba4\u5f00\u542f\u5230\u671f\u81ea\u52a8\u7eed\u671f)") : null), 5 === t.type ? null : i.a.createElement(Se.a.Item, {
                                        title: 3 === t.type ? "\u9996\u6708\u7ba1\u7406\u8d39" : "\u914d\u8d44\u7ba1\u7406\u8d39"
                                    },
                                    i.a.createElement(Me.a, null, o, "\u5143"), i.a.createElement(Re, null, "(\u914d\u8d44\u8d44\u91d1 x \u64cd\u76d8\u671f\u9650 x", " ", t.rate, "%)")), i.a.createElement(Se.a.Item, {
                                        title: "\u64cd\u76d8\u987b\u77e5"
                                    },
                                    5 === t.type ? "\u76c8\u5229 ".concat(t.freeSet[2], "% \u5f52\u60a8\uff0c\u5176\u4f59\u5f52\u5e73\u53f0\u3002") : "\u5355\u53ea\u80a1\u7968\u6700\u5927\u6301\u4ed3\u6bd4\u4f8b\u4e3a ".concat(l, "%")), i.a.createElement(Se.a.Item, {
                                        title: "\u8d26\u6237\u4f59\u989d"
                                    },
                                    i.a.createElement(Me.a, null, t.accountMoney, "\u5143"), i.a.createElement(ze, {
                                            to: "/member/charge",
                                            style: {
                                                display: "none"
                                            }
                                        },
                                        "\u5145\u503c")), i.a.createElement(Se.a.Item, {
                                        title: "\u786e\u8ba4\u652f\u4ed8"
                                    },
                                    i.a.createElement("span", {
                                            className: "text-primary"
                                        },
                                        n, "\u5143"))), i.a.createElement(ke, {
                                    defaultChecked: this.state.agreement,
                                    onChange: this.onChangeAgreement
                                })))))
                        }
                    }]),
                    t
            }(a.PureComponent),
            Ce = Object(y.a)(Object(g.b)(function (e) {
                return {
                    token: e.token
                }
            })(_e)),
            Re = S.b.span(we()),
            ze = Object(S.b)(O.a)(De()),
            Ne = n(366),
            Ie = 5,
            Fe = function (e) {
                function t(e) {
                    var n;
                    return Object(f.a)(this, t),
                        (n = Object(h.a)(this, Object(b.a)(t).call(this, e))).state = {
                            money: null,
                            accountMoney: 0,
                            moneyRange: [],
                            duration: [],
                            activeDuration: null,
                            multiple: [],
                            activeMultiple: null,
                            maxDuration: 0,
                            minDuration: 0,
                            rate: [],
                            multipleRate: [],
                            lineSetting: [],
                            freeSet: [],
                            position: []
                        },
                        n.onMoneyChange = function (e) {
                            n.setState({
                                money: parseInt(e, 10)
                            })
                        },
                        n.onSubmit = function () {
                            var e = n.props,
                                t = e.token,
                                a = e.history,
                                i = n.state,
                                o = i.activeMultiple,
                                r = i.activeDuration,
                                l = i.money,
                                c = i.lineSetting,
                                u = i.accountMoney,
                                m = i.freeSet,
                                s = i.position;
                            n._checkSubmit() && (p.a.loading(null, 0), ie.a.post("".concat(oe.kb), {
                                token: t,
                                type: Ie,
                                multiple: o,
                                borrow_duration: r,
                                deposit_money: l
                            }).then(function (e) {
                                p.a.hide(),
                                    "1" === e.data.status ? a.push({
                                        pathname: "/peizi/confirm",
                                        state: {
                                            money: l,
                                            activeDuration: r,
                                            activeMultiple: +o,
                                            type: Ie,
                                            lineSetting: c,
                                            accountMoney: u,
                                            freeSet: m,
                                            rate: 0,
                                            position: s
                                        }
                                    }) : p.a.info(e.data.message)
                            }).catch(function (e) {
                                p.a.hide()
                            }))
                        },
                        n._checkSubmit = function () {
                            var e = n.state,
                                t = e.money,
                                a = e.moneyRange,
                                i = e.activeMultiple,
                                o = e.activeDuration;
                            return null === t ? (p.a.info("\u8bf7\u8f93\u5165\u4fdd\u8bc1\u91d1\u91d1\u989d", 1, null, !1), !1) : t < a[0] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u5c11\u4e3a".concat(a[0], "\u5143"), 1, null, !1), !1) : t > a[1] ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u6700\u591a\u4e3a".concat(a[1], "\u5143"), 1, null, !1), !1) : t % a[2] !== 0 ? (p.a.info("\u4fdd\u8bc1\u91d1\u91d1\u989d\u5fc5\u987b\u662f".concat(a[2], "\u7684\u6574\u6570\u500d"), 1, null, !1), !1) : null === o ? (p.a.info("\u8bf7\u9009\u62e9\u64cd\u76d8\u671f\u9650", 1, null, !1), !1) : null !== i || (p.a.info("\u8bf7\u9009\u62e9\u914d\u8d44\u91d1\u989d", 1, null, !1), !1)
                        },
                        n._fetchPageData(e.token),
                        n
                }

                return Object(v.a)(t, e),
                    Object(d.a)(t, [{
                        key: "_fetchPageData",
                        value: function (e) {
                            var t = this;
                            ie.a.post("".concat(oe.db), {
                                token: e
                            }).then(function (e) {
                                var n = e.data.data,
                                    a = n.money_range,
                                    i = n.account_money,
                                    o = n.free_loss,
                                    r = n.free_set,
                                    l = n.position;
                                t.setState({
                                    accountMoney: i,
                                    moneyRange: a,
                                    activeDuration: r[1],
                                    lineSetting: o,
                                    multipleRate: r[0],
                                    rate: [r[0]],
                                    freeSet: r,
                                    activeMultiple: r[0],
                                    position: l
                                })
                            })
                        }
                    },
                        {
                            key: "render",
                            value: function () {
                                var e = this.state,
                                    t = e.money,
                                    n = e.moneyRange,
                                    o = e.activeDuration,
                                    l = e.activeMultiple,
                                    u = e.rate;
                                return i.a.createElement(E.a, {
                                        title: "\u514d\u606f\u914d\u8d44"
                                    },
                                    i.a.createElement(a.Fragment, null, i.a.createElement(T, null, i.a.createElement(m.a, {
                                            type: "primary",
                                            onClick: this.onSubmit,
                                            style: {
                                                borderRadius: 0,
                                                background: "#FE4500",
                                                border: "none",
                                                boxShadow: "none"
                                            }
                                        },
                                        "\u4e0b\u4e00\u6b65")), i.a.createElement(Y.a, null, i.a.createElement(W.a, {
                                            left: i.a.createElement(O.a, {
                                                    to: "/"
                                                },
                                                i.a.createElement(c.a, {
                                                    type: "left",
                                                    style: {
                                                        width: "30px",
                                                        height: "30px"
                                                    }
                                                }))
                                        },
                                        "\u514d\u606f\u914d\u8d44"), i.a.createElement(ne, null), i.a.createElement(r.a, null, i.a.createElement(D, {
                                        max: n[1],
                                        min: n[0],
                                        step: n[2],
                                        onChange: this.onMoneyChange
                                    }), i.a.createElement(z, {
                                        title: "\u64cd\u76d8\u671f\u9650",
                                        suffix: "\u5929",
                                        readOnly: !0,
                                        activeItem: o
                                    }), i.a.createElement(X, {
                                        rate: u,
                                        money: t,
                                        readOnly: !0,
                                        onSelect: function () {
                                        },
                                        activeMultiple: l
                                    })))))
                            }
                        }]),
                    t
            }(a.PureComponent),
            Pe = Object(y.a)(Object(g.b)(function (e) {
                return {
                    token: e.token,
                    isLogin: e.isLogin
                }
            })(Fe)),
            Le = n(488);
        t.default = Object(y.a)(function (e) {
            var t = e.match;
            return i.a.createElement(a.Fragment, null, i.a.createElement(Le.a, {
                path: "".concat(t.path, "/day"),
                component: ce
            }), i.a.createElement(Le.a, {
                path: "".concat(t.path, "/week"),
                component: se
            }), i.a.createElement(Le.a, {
                path: "".concat(t.path, "/month"),
                component: de
            }), i.a.createElement(Le.a, {
                path: "".concat(t.path, "/free"),
                component: Pe
            }), i.a.createElement(Le.a, {
                path: "".concat(t.path, "/confirm"),
                component: Ce
            }), i.a.createElement(Le.a, {
                path: "".concat(t.path, "/agreement/:id"),
                component: Ne.a
            }))
        })
    }
}]);
//# sourceMappingURL=7.6bd8ae68.chunk.js.map
