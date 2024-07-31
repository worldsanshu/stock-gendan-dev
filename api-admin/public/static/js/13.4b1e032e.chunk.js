(window.webpackJsonp = window.webpackJsonp || []).push([[13], {
    240: function (e, t, n) {
        "use strict";
        var a = n(3)
            , o = n(0)
            , i = n.n(o);

        function r() {
            var e = Object(a.a)(["\n    display: flex;\n    height: 45px;\n    line-height: 45px;\n    background-color: ", ";\n    color: #fff;\n    & > div {\n        flex: 1;\n    }\n    > .navbar-left {\n        display: flex;\n        align-items: center;\n        padding-left: 10px;\n        a {\n            color: #fff;\n            display: flex;\n            align-items: center;\n        }\n    }\n    > .navbar-title {\n        display: -webkit-box;\n        -webkit-box-align: center;\n        -webkit-box-pack: center;\n        font-size: 18px;\n        justify-content: center;\n        white-space: nowrap;\n    }\n    > .navbar-right {\n        display: flex;\n        padding-right: 15px;\n        align-items: center;\n        justify-content: flex-end;\n    }\n"]);
            return r = function () {
                return e
            }
                ,
                e
        }

        var l = n(2).b.div(r(), function (e) {
            return e.background ? e.background : "#ff4500"
        });
        t.a = function (e) {
            var t = e.left
                , n = e.onLeftClick
                , a = e.children
                , o = e.right
                , r = e.background;
            return i.a.createElement(l, {
                background: r
            }, i.a.createElement("div", {
                className: "navbar-left",
                onClick: n
            }, t), i.a.createElement("div", {
                className: "navbar-title"
            }, a), i.a.createElement("div", {
                className: "navbar-right"
            }, o))
        }
    },
    253: function (e, t, n) {
        "use strict";
        n(138),
            n(360)
    },
    255: function (e, t, n) {
        "use strict";
        Object.defineProperty(t, "__esModule", {
            value: !0
        });
        var a = d(n(23))
            , o = d(n(12))
            , i = d(n(13))
            , r = d(n(14))
            , l = d(n(15))
            , c = d(n(73))
            , u = d(n(0))
            , s = d(n(364));

        function d(e) {
            return e && e.__esModule ? e : {
                default: e
            }
        }

        var f = function (e, t) {
            var n = {};
            for (var a in e)
                Object.prototype.hasOwnProperty.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
            if (null != e && "function" === typeof Object.getOwnPropertySymbols) {
                var o = 0;
                for (a = Object.getOwnPropertySymbols(e); o < a.length; o++)
                    t.indexOf(a[o]) < 0 && (n[a[o]] = e[a[o]])
            }
            return n
        }
            , p = function (e) {
            function t() {
                return (0,
                    o.default)(this, t),
                    (0,
                        r.default)(this, (t.__proto__ || Object.getPrototypeOf(t)).apply(this, arguments))
            }

            return (0,
                l.default)(t, e),
                (0,
                    i.default)(t, [{
                    key: "render",
                    value: function () {
                        var e = this.props
                            , t = e.prefixCls
                            , n = e.children
                            , o = e.className
                            , i = e.style
                            , r = e.renderHeader
                            , l = e.renderFooter
                            , s = f(e, ["prefixCls", "children", "className", "style", "renderHeader", "renderFooter"])
                            , d = (0,
                            c.default)(t, o);
                        return u.default.createElement("div", (0,
                            a.default)({
                            className: d,
                            style: i
                        }, s), r ? u.default.createElement("div", {
                            className: t + "-header"
                        }, "function" === typeof r ? r() : r) : null, n ? u.default.createElement("div", {
                            className: t + "-body"
                        }, n) : null, l ? u.default.createElement("div", {
                            className: t + "-footer"
                        }, "function" === typeof l ? l() : l) : null)
                    }
                }]),
                t
        }(u.default.Component);
        t.default = p,
            p.Item = s.default,
            p.defaultProps = {
                prefixCls: "am-list"
            },
            e.exports = t.default
    },
    266: function (e, t, n) {
        "use strict";
        n.r(t);
        var a = n(23)
            , o = n.n(a)
            , i = n(12)
            , r = n.n(i)
            , l = n(13)
            , c = n.n(l)
            , u = n(14)
            , s = n.n(u)
            , d = n(15)
            , f = n.n(d)
            , p = n(0)
            , h = n.n(p)
            , m = n(73)
            , v = n.n(m)
            , g = function (e) {
            function t() {
                r()(this, t);
                var e = s()(this, (t.__proto__ || Object.getPrototypeOf(t)).apply(this, arguments));
                return e.state = {
                    active: !1
                },
                    e.onTouchStart = function (t) {
                        e.triggerEvent("TouchStart", !0, t)
                    }
                    ,
                    e.onTouchMove = function (t) {
                        e.triggerEvent("TouchMove", !1, t)
                    }
                    ,
                    e.onTouchEnd = function (t) {
                        e.triggerEvent("TouchEnd", !1, t)
                    }
                    ,
                    e.onTouchCancel = function (t) {
                        e.triggerEvent("TouchCancel", !1, t)
                    }
                    ,
                    e.onMouseDown = function (t) {
                        e.triggerEvent("MouseDown", !0, t)
                    }
                    ,
                    e.onMouseUp = function (t) {
                        e.triggerEvent("MouseUp", !1, t)
                    }
                    ,
                    e.onMouseLeave = function (t) {
                        e.triggerEvent("MouseLeave", !1, t)
                    }
                    ,
                    e
            }

            return f()(t, e),
                c()(t, [{
                    key: "componentDidUpdate",
                    value: function () {
                        this.props.disabled && this.state.active && this.setState({
                            active: !1
                        })
                    }
                }, {
                    key: "triggerEvent",
                    value: function (e, t, n) {
                        var a = "on" + e
                            , o = this.props.children;
                        o.props[a] && o.props[a](n),
                        t !== this.state.active && this.setState({
                            active: t
                        })
                    }
                }, {
                    key: "render",
                    value: function () {
                        var e = this.props
                            , t = e.children
                            , n = e.disabled
                            , a = e.activeClassName
                            , i = e.activeStyle
                            , r = n ? void 0 : {
                            onTouchStart: this.onTouchStart,
                            onTouchMove: this.onTouchMove,
                            onTouchEnd: this.onTouchEnd,
                            onTouchCancel: this.onTouchCancel,
                            onMouseDown: this.onMouseDown,
                            onMouseUp: this.onMouseUp,
                            onMouseLeave: this.onMouseLeave
                        }
                            , l = h.a.Children.only(t);
                        if (!n && this.state.active) {
                            var c = l.props
                                , u = c.style
                                , s = c.className;
                            return !1 !== i && (i && (u = o()({}, u, i)),
                                s = v()(s, a)),
                                h.a.cloneElement(l, o()({
                                    className: s,
                                    style: u
                                }, r))
                        }
                        return h.a.cloneElement(l, r)
                    }
                }]),
                t
        }(h.a.Component)
            , y = g;
        g.defaultProps = {
            disabled: !1
        },
            n.d(t, "default", function () {
                return y
            })
    },
    290: function (e, t, n) {
        "use strict";
        t.__esModule = !0,
            t.default = function (e, t) {
                var n = {};
                for (var a in e)
                    t.indexOf(a) >= 0 || Object.prototype.hasOwnProperty.call(e, a) && (n[a] = e[a]);
                return n
            }
    },
    360: function (e, t, n) {
    },
    364: function (e, t, n) {
        "use strict";
        Object.defineProperty(t, "__esModule", {
            value: !0
        }),
            t.Brief = void 0;
        var a = f(n(23))
            , o = f(n(139))
            , i = f(n(12))
            , r = f(n(13))
            , l = f(n(14))
            , c = f(n(15))
            , u = f(n(73))
            , s = f(n(0))
            , d = f(n(266));

        function f(e) {
            return e && e.__esModule ? e : {
                default: e
            }
        }

        var p = function (e, t) {
            var n = {};
            for (var a in e)
                Object.prototype.hasOwnProperty.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
            if (null != e && "function" === typeof Object.getOwnPropertySymbols) {
                var o = 0;
                for (a = Object.getOwnPropertySymbols(e); o < a.length; o++)
                    t.indexOf(a[o]) < 0 && (n[a[o]] = e[a[o]])
            }
            return n
        }
            , h = t.Brief = function (e) {
            function t() {
                return (0,
                    i.default)(this, t),
                    (0,
                        l.default)(this, (t.__proto__ || Object.getPrototypeOf(t)).apply(this, arguments))
            }

            return (0,
                c.default)(t, e),
                (0,
                    r.default)(t, [{
                    key: "render",
                    value: function () {
                        return s.default.createElement("div", {
                            className: "am-list-brief",
                            style: this.props.style
                        }, this.props.children)
                    }
                }]),
                t
        }(s.default.Component)
            , m = function (e) {
            function t(e) {
                (0,
                    i.default)(this, t);
                var n = (0,
                    l.default)(this, (t.__proto__ || Object.getPrototypeOf(t)).call(this, e));
                return n.onClick = function (e) {
                    var t = n.props
                        , a = t.onClick
                        , o = t.platform;
                    if (a && "android" === o) {
                        n.debounceTimeout && (clearTimeout(n.debounceTimeout),
                            n.debounceTimeout = null);
                        var i = e.currentTarget
                            , r = Math.max(i.offsetHeight, i.offsetWidth)
                            , l = e.currentTarget.getBoundingClientRect()
                            , c = {
                            width: r + "px",
                            height: r + "px",
                            left: e.clientX - l.left - i.offsetWidth / 2 + "px",
                            top: e.clientY - l.top - i.offsetWidth / 2 + "px"
                        };
                        n.setState({
                            coverRippleStyle: c,
                            RippleClicked: !0
                        }, function () {
                            n.debounceTimeout = setTimeout(function () {
                                n.setState({
                                    coverRippleStyle: {
                                        display: "none"
                                    },
                                    RippleClicked: !1
                                })
                            }, 1e3)
                        })
                    }
                    a && a(e)
                }
                    ,
                    n.state = {
                        coverRippleStyle: {
                            display: "none"
                        },
                        RippleClicked: !1
                    },
                    n
            }

            return (0,
                c.default)(t, e),
                (0,
                    r.default)(t, [{
                    key: "componentWillUnmount",
                    value: function () {
                        this.debounceTimeout && (clearTimeout(this.debounceTimeout),
                            this.debounceTimeout = null)
                    }
                }, {
                    key: "render",
                    value: function () {
                        var e, t, n, i = this, r = this.props, l = r.prefixCls, c = r.className, f = r.activeStyle,
                            h = r.error, m = r.align, v = r.wrap, g = r.disabled, y = r.children, b = r.multipleLine,
                            E = r.thumb, w = r.extra, O = r.arrow, x = r.onClick,
                            k = p(r, ["prefixCls", "className", "activeStyle", "error", "align", "wrap", "disabled", "children", "multipleLine", "thumb", "extra", "arrow", "onClick"]),
                            S = (k.platform,
                                p(k, ["platform"])), C = this.state, T = C.coverRippleStyle, _ = C.RippleClicked,
                            j = (0,
                                u.default)(l + "-item", c, (e = {},
                                (0,
                                    o.default)(e, l + "-item-disabled", g),
                                (0,
                                    o.default)(e, l + "-item-error", h),
                                (0,
                                    o.default)(e, l + "-item-top", "top" === m),
                                (0,
                                    o.default)(e, l + "-item-middle", "middle" === m),
                                (0,
                                    o.default)(e, l + "-item-bottom", "bottom" === m),
                                e)), M = (0,
                                u.default)(l + "-ripple", (0,
                                o.default)({}, l + "-ripple-animate", _)), N = (0,
                                u.default)(l + "-line", (t = {},
                                (0,
                                    o.default)(t, l + "-line-multiple", b),
                                (0,
                                    o.default)(t, l + "-line-wrap", v),
                                t)), P = (0,
                                u.default)(l + "-arrow", (n = {},
                                (0,
                                    o.default)(n, l + "-arrow-horizontal", "horizontal" === O),
                                (0,
                                    o.default)(n, l + "-arrow-vertical", "down" === O || "up" === O),
                                (0,
                                    o.default)(n, l + "-arrow-vertical-up", "up" === O),
                                n)), D = s.default.createElement("div", (0,
                                a.default)({}, S, {
                                onClick: function (e) {
                                    i.onClick(e)
                                },
                                className: j
                            }), E ? s.default.createElement("div", {
                                className: l + "-thumb"
                            }, "string" === typeof E ? s.default.createElement("img", {
                                src: E
                            }) : E) : null, s.default.createElement("div", {
                                className: N
                            }, void 0 !== y && s.default.createElement("div", {
                                className: l + "-content"
                            }, y), void 0 !== w && s.default.createElement("div", {
                                className: l + "-extra"
                            }, w), O && s.default.createElement("div", {
                                className: P,
                                "aria-hidden": "true"
                            })), s.default.createElement("div", {
                                style: T,
                                className: M
                            })), L = {};
                        return Object.keys(S).forEach(function (e) {
                            /onTouch/i.test(e) && (L[e] = S[e],
                                delete S[e])
                        }),
                            s.default.createElement(d.default, (0,
                                a.default)({}, L, {
                                disabled: g || !x,
                                activeStyle: f,
                                activeClassName: l + "-item-active"
                            }), D)
                    }
                }]),
                t
        }(s.default.Component);
        m.defaultProps = {
            prefixCls: "am-list",
            align: "middle",
            error: !1,
            multipleLine: !1,
            wrap: !1,
            platform: "ios"
        },
            m.Brief = h,
            t.default = m
    },
    814: function (e, t, n) {
        "use strict";
        n.r(t);
        n(137);
        var a = n(71)
            , o = n.n(a)
            , i = (n(274),
            n(275))
            , r = n.n(i)
            , l = n(76)
            , c = n(34)
            , u = n(35)
            , s = n(37)
            , d = n(36)
            , f = n(38)
            , p = n(3)
            , h = (n(253),
            n(255))
            , m = n.n(h)
            , v = n(39)
            , g = n.n(v)
            , y = n(0)
            , b = n.n(y)
            , E = n(72)
            , w = n.n(E)
            , O = n(827)
            , x = n(240)
            , k = n(487)
            , S = n(2)
            , C = n(30)
            , T = n(31)
            , _ = n.n(T);

        function j() {
            var e = Object(p.a)(["\n    display: block;\n    color: #252525;\n"]);
            return j = function () {
                return e
            }
                ,
                e
        }

        var M = m.a.Item
            , N = Object(S.b)(k.a)(j())
            , P = function (e) {
            function t(e) {
                var n;
                Object(c.a)(this, t),
                    (n = Object(s.a)(this, Object(d.a)(t).call(this, e)))._fetchData = function (e, t) {
                        return _.a.get("".concat(C.j, "?id=").concat(e, "&page=").concat(t))
                    }
                    ,
                    n.onEndReached = function () {
                        n.state.isLoading || (n.setState({
                            isLoading: !0
                        }),
                            n._fetchData(n.props.match.params.id, ++n.pageIndex).then(function (e) {
                                n._isMount && (n.rData = [].concat(Object(l.a)(n.rData), Object(l.a)(e.data.data)),
                                    n.setState({
                                        dataSource: n.state.dataSource.cloneWithRows(n.rData),
                                        isLoading: !1
                                    }))
                            }))
                    }
                ;
                var a = new r.a.DataSource({
                    rowHasChanged: function (e, t) {
                        return e !== t
                    }
                });

                var searArr = location.href.split('/');
                //if(searArr[searArr .length-1]=='2'){
                if (searArr[5] == '2') {
                    return n.pageIndex = 0,
                        n.state = {
                            dataSource: a,
                            refreshing: !0,
                            isLoading: !0,
                            height: document.documentElement.clientHeight,
                            useBodyScroll: !0,
                            pageTitle: n.props.location.state ? n.props.location.state.title : "公告"
                        },
                        n
                }

                return n.pageIndex = 0,
                    n.state = {
                        dataSource: a,
                        refreshing: !0,
                        isLoading: !0,
                        height: document.documentElement.clientHeight,
                        useBodyScroll: !0,
                        pageTitle: n.props.location.state ? n.props.location.state.title : "\u5e2e\u52a9\u4e2d\u5fc3"
                    },
                    n
            }

            return Object(f.a)(t, e),
                Object(u.a)(t, [{
                    key: "componentDidMount",
                    value: function () {
                        var e = this;
                        this._isMount = !0;
                        var t = this.state.height - g.a.findDOMNode(this.lv).offsetTop;
                        this._fetchData(this.props.match.params.id, ++this.pageIndex).then(function (n) {
                            e._isMount && (e.rData = n.data.data,
                                e.setState({
                                    dataSource: e.state.dataSource.cloneWithRows(e.rData),
                                    height: t,
                                    refreshing: !1,
                                    isLoading: !1
                                }))
                        })
                    }
                }, {
                    key: "componentWillUnmount",
                    value: function () {
                        this._isMount = !1
                    }
                }, {
                    key: "render",
                    value: function () {
                        var e = this;
                        return b.a.createElement(w.a, {
                            title: this.state.pageTitle
                        }, b.a.createElement(y.Fragment, null, b.a.createElement(x.a, {
                            left: b.a.createElement(o.a, {
                                type: "left",
                                style: {
                                    width: "30px",
                                    height: "30px"
                                }
                            }),
                            onLeftClick: function () {
                                return window.history.go(-1)
                            }
                        }, this.state.pageTitle), b.a.createElement(r.a, {
                            ref: function (t) {
                                return e.lv = t
                            },
                            dataSource: this.state.dataSource,
                            renderFooter: function () {
                                return b.a.createElement("div", {
                                    style: {
                                        textAlign: "center"
                                    }
                                }, e.state.isLoading ? "\u52a0\u8f7d\u4e2d..." : "---- \u5df2\u5230\u5e95\u90e8 ----")
                            },
                            renderRow: function (e) {
                                return b.a.createElement(M, {
                                    key: e.id,
                                    arrow: "horizontal"
                                }, b.a.createElement(N, {
                                    to: "/article/detail/".concat(e.id, "/").concat(e.model)
                                }, e.title))
                            },
                            useBodyScroll: !0,
                            onEndReached: this.onEndReached,
                            pageSize: 10
                        })))
                    }
                }]),
                t
        }(y.PureComponent);
        t.default = Object(O.a)(P)
    }
}]);
//# sourceMappingURL=13.4b1e032e.chunk.js.map
