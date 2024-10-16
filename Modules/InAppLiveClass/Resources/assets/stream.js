!function (e) {
    var t = {};

    function r(n) {
        if (t[n]) return t[n].exports;
        var o = t[n] = {i: n, l: !1, exports: {}};
        return e[n].call(o.exports, o, o.exports, r), o.l = !0, o.exports
    }

    r.m = e, r.c = t, r.d = function (e, t, n) {
        r.o(e, t) || Object.defineProperty(e, t, {enumerable: !0, get: n})
    }, r.r = function (e) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(e, "__esModule", {value: !0})
    }, r.t = function (e, t) {
        if (1 & t && (e = r(e)), 8 & t) return e;
        if (4 & t && "object" == typeof e && e && e.__esModule) return e;
        var n = Object.create(null);
        if (r.r(n), Object.defineProperty(n, "default", {
            enumerable: !0,
            value: e
        }), 2 & t && "string" != typeof e) for (var o in e) r.d(n, o, function (t) {
            return e[t]
        }.bind(null, o));
        return n
    }, r.n = function (e) {
        var t = e && e.__esModule ? function () {
            return e.default
        } : function () {
            return e
        };
        return r.d(t, "a", t), t
    }, r.o = function (e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }, r.p = "/", r(r.s = 17)
}({
    0: function (e, t, r) {
        e.exports = r(3)
    }, 17: function (e, t, r) {
        e.exports = r(18)
    }, 18: function (e, t, r) {
        "use strict";
        r.r(t);
        var n = r(0), o = r.n(n);

        function a(e, t) {
            return function (e) {
                if (Array.isArray(e)) return e
            }(e) || function (e, t) {
                if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(e))) return;
                var r = [], n = !0, o = !1, a = void 0;
                try {
                    for (var i, c = e[Symbol.iterator](); !(n = (i = c.next()).done) && (r.push(i.value), !t || r.length !== t); n = !0) ;
                } catch (e) {
                    o = !0, a = e
                } finally {
                    try {
                        n || null == c.return || c.return()
                    } finally {
                        if (o) throw a
                    }
                }
                return r
            }(e, t) || function (e, t) {
                if (!e) return;
                if ("string" == typeof e) return i(e, t);
                var r = Object.prototype.toString.call(e).slice(8, -1);
                "Object" === r && e.constructor && (r = e.constructor.name);
                if ("Map" === r || "Set" === r) return Array.from(e);
                if ("Arguments" === r || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r)) return i(e, t)
            }(e, t) || function () {
                throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
            }()
        }

        function i(e, t) {
            (null == t || t > e.length) && (t = e.length);
            for (var r = 0, n = new Array(t); r < t; r++) n[r] = e[r];
            return n
        }

        function c(e, t, r, n, o, a, i) {
            try {
                var c = e[a](i), s = c.value
            } catch (e) {
                return void r(e)
            }
            c.done ? t(s) : Promise.resolve(s).then(n, o)
        }

        function s(e) {
            return function () {
                var t = this, r = arguments;
                return new Promise((function (n, o) {
                    var a = e.apply(t, r);

                    function i(e) {
                        c(a, n, o, i, s, "next", e)
                    }

                    function s(e) {
                        c(a, n, o, i, s, "throw", e)
                    }

                    i(void 0)
                }))
            }
        }

        !function (e) {
            var t = '<div class="no-result default-no-result d-flex align-items-center justify-content-center flex-column w-100 h-100">\n        <div class="no-result-logo">\n            <img src="/assets/default/img/no-results/support.png" alt="">\n        </div>\n        <div class="d-flex align-items-center flex-column mt-30 text-center">\n            <h2 class="text-dark-blue">'.concat(liveEndedLang, '</h2>\n            <p class="mt-5 text-center text-gray font-weight-500">').concat(redirectToPanelInAFewMomentLang, "</p>\n        </div>\n    </div>"),
                r = {width: 20, height: 20}, n = AgoraRTC.createClient({mode: "live", codec: "vp8"}), i = {
                    videoTrack: null,
                    audioTrack: null,
                    screenAudioTrack: null,
                    screenVideoTrack: null,
                    shareScreenActived: !1
                }, c = {}, u = {
                    appid: appId,
                    channel: channelName,
                    uid: null,
                    token: rtcToken,
                    role: streamRole,
                    audienceLatency: 2
                }, l = e("#stream-player"), f = e("#shareScreen");

            function d() {
                return (d = s(o.a.mark((function t() {
                    return o.a.wrap((function (t) {
                        for (; ;) switch (t.prev = t.next) {
                            case 0:
                                return t.prev = 0, "audience" === u.role ? (n.setClientRole(u.role, {level: u.audienceLatency}), n.on("user-published", y), n.on("user-unpublished", m), n.on("user-left", g)) : n.setClientRole(u.role), n.on("user-joined", h), t.next = 5, n.join(u.appid, u.channel, u.token || null, u.uid || null);
                            case 5:
                                if (u.uid = t.sent, "host" !== u.role) {
                                    t.next = 20;
                                    break
                                }
                                return t.next = 9, AgoraRTC.createMicrophoneAudioTrack();
                            case 9:
                                return i.audioTrack = t.sent, t.next = 12, AgoraRTC.createCameraVideoTrack();
                            case 12:
                                return i.videoTrack = t.sent, i.videoTrack.play("stream-player"), t.next = 16, n.publish([i.videoTrack, i.audioTrack]);
                            case 16:
                                x(streamStartAt && streamStartAt > 0 ? (new Date).getTime() / 1e3 - streamStartAt : 0), console.log("publish success"), e(".agora-stream-loading").addClass("d-none");
                            case 20:
                                t.next = 25;
                                break;
                            case 22:
                                t.prev = 22, t.t0 = t.catch(0), console.error(t.t0);
                            case 25:
                            case"end":
                                return t.stop()
                        }
                    }), t, null, [[0, 22]])
                })))).apply(this, arguments)
            }

            function h(e) {
                console.log("#################### Online"), console.log(e)
            }

            function p() {
                return (p = s(o.a.mark((function t(r, a) {
                    var i, c;
                    return o.a.wrap((function (t) {
                        for (; ;) switch (t.prev = t.next) {
                            case 0:
                                return i = r.uid, t.next = 3, n.subscribe(r, a);
                            case 3:
                                console.log("subscribe success"), "video" === a && (c = e('\n              <div id="player-wrapper-'.concat(i, '" class="w-100 h-100">\n                <div id="player-').concat(i, '" class="player"></div>\n              </div>\n            ')), l.html(c), r.videoTrack.play("player-".concat(i))), "audio" === a && r.audioTrack.play(), e(".agora-stream-loading").addClass("d-none"), e("#notStartedAlert").removeClass("d-flex"), e("#notStartedAlert").addClass("d-none"), x(streamStartAt && streamStartAt > 0 ? (new Date).getTime() / 1e3 - streamStartAt : 0);
                            case 11:
                            case"end":
                                return t.stop()
                        }
                    }), t)
                })))).apply(this, arguments)
            }

            function v() {
                return (v = s(o.a.mark((function e() {
                    var t, r;
                    return o.a.wrap((function (e) {
                        for (; ;) switch (e.prev = e.next) {
                            case 0:
                                for (t in i) (r = i[t]) && (r.stop(), r.close(), i[t] = void 0);
                                return e.next = 3, n.leave();
                            case 3:
                                redirectAfterLeave && (window.location = redirectAfterLeave), console.log("client leaves channel success");
                            case 5:
                            case"end":
                                return e.stop()
                        }
                    }), e)
                })))).apply(this, arguments)
            }

            function y(e, t) {
                var r = e.uid;
                c[r] = e, function (e, t) {
                    p.apply(this, arguments)
                }(e, t)
            }

            function m(t, r) {
                if ("video" === r) {
                    var n = t.uid;
                    delete c[n], e("#player-wrapper-".concat(n)).html("")
                }
            }

            function g(r, n) {
                var o = r.uid;
                e("#player-wrapper-".concat(o)).html(t), setTimeout((function () {
                    redirectAfterLeave && (window.location = redirectAfterLeave)
                }), 5e3)
            }

            function b() {
                return (b = s(o.a.mark((function e() {
                    var t, r, c;
                    return o.a.wrap((function (e) {
                        for (; ;) switch (e.prev = e.next) {
                            case 0:
                                if (i.shareScreenActived) {
                                    e.next = 15;
                                    break
                                }
                                return e.next = 3, Promise.all([AgoraRTC.createScreenVideoTrack({
                                    encoderConfig: {
                                        framerate: 30,
                                        height: 720,
                                        width: 1280
                                    }
                                }, "auto")]);
                            case 3:
                                if (r = e.sent, c = a(r, 1), (t = c[0]) instanceof Array ? (i.screenVideoTrack = t[0], i.screenAudioTrack = t[1]) : i.screenVideoTrack = t, !i.screenVideoTrack) {
                                    e.next = 15;
                                    break
                                }
                                return f.prop("disabled", !0), i.screenVideoTrack.play("stream-player"), w(!0), e.next = 13, n.publish([i.screenVideoTrack, i.audioTrack]);
                            case 13:
                                i.shareScreenActived = !0, i.screenVideoTrack.on("track-ended", (function () {
                                    f.prop("disabled", !1), n.unpublish(i.screenVideoTrack).then((function () {
                                        i.screenVideoTrack.stop(), i.screenVideoTrack.close(), i.shareScreenActived = !1, w(!1)
                                    }))
                                }));
                            case 15:
                            case"end":
                                return e.stop()
                        }
                    }), e)
                })))).apply(this, arguments)
            }

            function w() {
                return k.apply(this, arguments)
            }

            function k() {
                return (k = s(o.a.mark((function t() {
                    var a, c, s, u = arguments;
                    return o.a.wrap((function (t) {
                        for (; ;) switch (t.prev = t.next) {
                            case 0:
                                if (a = u.length > 0 && void 0 !== u[0] && u[0], c = e("#cameraEffect"), s = feather.icons.video.toSvg(r), !a) {
                                    t.next = 10;
                                    break
                                }
                                c.removeClass("active"), c.addClass("disabled"), s = feather.icons["video-off"].toSvg(r), i.videoTrack && (i.videoTrack.stop(), i.videoTrack.close(), n.unpublish(i.videoTrack)), t.next = 17;
                                break;
                            case 10:
                                return c.addClass("active"), c.removeClass("disabled"), t.next = 14, AgoraRTC.createCameraVideoTrack();
                            case 14:
                                i.videoTrack = t.sent, i.videoTrack.play("stream-player"), n.publish(i.videoTrack);
                            case 17:
                                c.find(".icon").html(s);
                            case 18:
                            case"end":
                                return t.stop()
                        }
                    }), t)
                })))).apply(this, arguments)
            }

            function x() {
                var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : 0, r = e("#streamTimer"),
                    n = r.find(".hours"), o = r.find(".minutes"), a = r.find(".seconds"), i = t;

                function c() {
                    ++i;
                    var e = s(Math.floor(i % 60)), t = s(Math.floor(i / 60 % 60)), r = s(Math.floor(i / 3600 % 24));
                    n.html(r), o.html(t), a.html(e)
                }

                function s(e) {
                    var t = e + "";
                    return t.length < 2 ? "0" + t : t
                }

                setInterval(c, 1e3)
            }

            !function () {
                d.apply(this, arguments)
            }(), e("body").on("click", "#leave", (function (t) {
                var r = e(this), n = r.attr("data-id");
                r.addClass("loadingbar primary").prop("disabled", !0);
                // var o = "/inappliveclass/meetings-end/" + n;
                var o =endUrl;
                e.get(o, (function (e) {
                    e && 200 === e.code && function () {
                        v.apply(this, arguments)
                    }()
                }))
            })), e("body").on("click", "#shareScreen", (function (e) {
                !function () {
                    b.apply(this, arguments)
                }()
            })), e("body").on("click", "#microphoneEffect", (function (t) {
                var o = e(this), a = feather.icons.mic.toSvg(r);
                i.audioTrack && (o.hasClass("active") ? (o.removeClass("active"), o.addClass("disabled"), a = feather.icons["mic-off"].toSvg(r), n.unpublish(i.audioTrack)) : (o.addClass("active"), o.removeClass("disabled"), n.publish(i.audioTrack))), o.find(".icon").html(a)
            })), e("body").on("click", "#cameraEffect", (function (t) {
                var r = e(this);
                i.shareScreenActived || w(r.hasClass("active"))
            })), e("body").on("click", "#collapseBtn", (function () {
                e(".agora-tabs").toggleClass("show")
            }))
        }(jQuery)
    }, 3: function (e, t, r) {
        var n = function (e) {
            "use strict";
            var t = Object.prototype, r = t.hasOwnProperty, n = "function" == typeof Symbol ? Symbol : {},
                o = n.iterator || "@@iterator", a = n.asyncIterator || "@@asyncIterator",
                i = n.toStringTag || "@@toStringTag";

            function c(e, t, r) {
                return Object.defineProperty(e, t, {value: r, enumerable: !0, configurable: !0, writable: !0}), e[t]
            }

            try {
                c({}, "")
            } catch (e) {
                c = function (e, t, r) {
                    return e[t] = r
                }
            }

            function s(e, t, r, n) {
                var o = t && t.prototype instanceof f ? t : f, a = Object.create(o.prototype), i = new T(n || []);
                return a._invoke = function (e, t, r) {
                    var n = "suspendedStart";
                    return function (o, a) {
                        if ("executing" === n) throw new Error("Generator is already running");
                        if ("completed" === n) {
                            if ("throw" === o) throw a;
                            return A()
                        }
                        for (r.method = o, r.arg = a; ;) {
                            var i = r.delegate;
                            if (i) {
                                var c = w(i, r);
                                if (c) {
                                    if (c === l) continue;
                                    return c
                                }
                            }
                            if ("next" === r.method) r.sent = r._sent = r.arg; else if ("throw" === r.method) {
                                if ("suspendedStart" === n) throw n = "completed", r.arg;
                                r.dispatchException(r.arg)
                            } else "return" === r.method && r.abrupt("return", r.arg);
                            n = "executing";
                            var s = u(e, t, r);
                            if ("normal" === s.type) {
                                if (n = r.done ? "completed" : "suspendedYield", s.arg === l) continue;
                                return {value: s.arg, done: r.done}
                            }
                            "throw" === s.type && (n = "completed", r.method = "throw", r.arg = s.arg)
                        }
                    }
                }(e, r, i), a
            }

            function u(e, t, r) {
                try {
                    return {type: "normal", arg: e.call(t, r)}
                } catch (e) {
                    return {type: "throw", arg: e}
                }
            }

            e.wrap = s;
            var l = {};

            function f() {
            }

            function d() {
            }

            function h() {
            }

            var p = {};
            p[o] = function () {
                return this
            };
            var v = Object.getPrototypeOf, y = v && v(v(S([])));
            y && y !== t && r.call(y, o) && (p = y);
            var m = h.prototype = f.prototype = Object.create(p);

            function g(e) {
                ["next", "throw", "return"].forEach((function (t) {
                    c(e, t, (function (e) {
                        return this._invoke(t, e)
                    }))
                }))
            }

            function b(e, t) {
                var n;
                this._invoke = function (o, a) {
                    function i() {
                        return new t((function (n, i) {
                            !function n(o, a, i, c) {
                                var s = u(e[o], e, a);
                                if ("throw" !== s.type) {
                                    var l = s.arg, f = l.value;
                                    return f && "object" == typeof f && r.call(f, "__await") ? t.resolve(f.__await).then((function (e) {
                                        n("next", e, i, c)
                                    }), (function (e) {
                                        n("throw", e, i, c)
                                    })) : t.resolve(f).then((function (e) {
                                        l.value = e, i(l)
                                    }), (function (e) {
                                        return n("throw", e, i, c)
                                    }))
                                }
                                c(s.arg)
                            }(o, a, n, i)
                        }))
                    }

                    return n = n ? n.then(i, i) : i()
                }
            }

            function w(e, t) {
                var r = e.iterator[t.method];
                if (void 0 === r) {
                    if (t.delegate = null, "throw" === t.method) {
                        if (e.iterator.return && (t.method = "return", t.arg = void 0, w(e, t), "throw" === t.method)) return l;
                        t.method = "throw", t.arg = new TypeError("The iterator does not provide a 'throw' method")
                    }
                    return l
                }
                var n = u(r, e.iterator, t.arg);
                if ("throw" === n.type) return t.method = "throw", t.arg = n.arg, t.delegate = null, l;
                var o = n.arg;
                return o ? o.done ? (t[e.resultName] = o.value, t.next = e.nextLoc, "return" !== t.method && (t.method = "next", t.arg = void 0), t.delegate = null, l) : o : (t.method = "throw", t.arg = new TypeError("iterator result is not an object"), t.delegate = null, l)
            }

            function k(e) {
                var t = {tryLoc: e[0]};
                1 in e && (t.catchLoc = e[1]), 2 in e && (t.finallyLoc = e[2], t.afterLoc = e[3]), this.tryEntries.push(t)
            }

            function x(e) {
                var t = e.completion || {};
                t.type = "normal", delete t.arg, e.completion = t
            }

            function T(e) {
                this.tryEntries = [{tryLoc: "root"}], e.forEach(k, this), this.reset(!0)
            }

            function S(e) {
                if (e) {
                    var t = e[o];
                    if (t) return t.call(e);
                    if ("function" == typeof e.next) return e;
                    if (!isNaN(e.length)) {
                        var n = -1, a = function t() {
                            for (; ++n < e.length;) if (r.call(e, n)) return t.value = e[n], t.done = !1, t;
                            return t.value = void 0, t.done = !0, t
                        };
                        return a.next = a
                    }
                }
                return {next: A}
            }

            function A() {
                return {value: void 0, done: !0}
            }

            return d.prototype = m.constructor = h, h.constructor = d, d.displayName = c(h, i, "GeneratorFunction"), e.isGeneratorFunction = function (e) {
                var t = "function" == typeof e && e.constructor;
                return !!t && (t === d || "GeneratorFunction" === (t.displayName || t.name))
            }, e.mark = function (e) {
                return Object.setPrototypeOf ? Object.setPrototypeOf(e, h) : (e.__proto__ = h, c(e, i, "GeneratorFunction")), e.prototype = Object.create(m), e
            }, e.awrap = function (e) {
                return {__await: e}
            }, g(b.prototype), b.prototype[a] = function () {
                return this
            }, e.AsyncIterator = b, e.async = function (t, r, n, o, a) {
                void 0 === a && (a = Promise);
                var i = new b(s(t, r, n, o), a);
                return e.isGeneratorFunction(r) ? i : i.next().then((function (e) {
                    return e.done ? e.value : i.next()
                }))
            }, g(m), c(m, i, "Generator"), m[o] = function () {
                return this
            }, m.toString = function () {
                return "[object Generator]"
            }, e.keys = function (e) {
                var t = [];
                for (var r in e) t.push(r);
                return t.reverse(), function r() {
                    for (; t.length;) {
                        var n = t.pop();
                        if (n in e) return r.value = n, r.done = !1, r
                    }
                    return r.done = !0, r
                }
            }, e.values = S, T.prototype = {
                constructor: T, reset: function (e) {
                    if (this.prev = 0, this.next = 0, this.sent = this._sent = void 0, this.done = !1, this.delegate = null, this.method = "next", this.arg = void 0, this.tryEntries.forEach(x), !e) for (var t in this) "t" === t.charAt(0) && r.call(this, t) && !isNaN(+t.slice(1)) && (this[t] = void 0)
                }, stop: function () {
                    this.done = !0;
                    var e = this.tryEntries[0].completion;
                    if ("throw" === e.type) throw e.arg;
                    return this.rval
                }, dispatchException: function (e) {
                    if (this.done) throw e;
                    var t = this;

                    function n(r, n) {
                        return i.type = "throw", i.arg = e, t.next = r, n && (t.method = "next", t.arg = void 0), !!n
                    }

                    for (var o = this.tryEntries.length - 1; o >= 0; --o) {
                        var a = this.tryEntries[o], i = a.completion;
                        if ("root" === a.tryLoc) return n("end");
                        if (a.tryLoc <= this.prev) {
                            var c = r.call(a, "catchLoc"), s = r.call(a, "finallyLoc");
                            if (c && s) {
                                if (this.prev < a.catchLoc) return n(a.catchLoc, !0);
                                if (this.prev < a.finallyLoc) return n(a.finallyLoc)
                            } else if (c) {
                                if (this.prev < a.catchLoc) return n(a.catchLoc, !0)
                            } else {
                                if (!s) throw new Error("try statement without catch or finally");
                                if (this.prev < a.finallyLoc) return n(a.finallyLoc)
                            }
                        }
                    }
                }, abrupt: function (e, t) {
                    for (var n = this.tryEntries.length - 1; n >= 0; --n) {
                        var o = this.tryEntries[n];
                        if (o.tryLoc <= this.prev && r.call(o, "finallyLoc") && this.prev < o.finallyLoc) {
                            var a = o;
                            break
                        }
                    }
                    a && ("break" === e || "continue" === e) && a.tryLoc <= t && t <= a.finallyLoc && (a = null);
                    var i = a ? a.completion : {};
                    return i.type = e, i.arg = t, a ? (this.method = "next", this.next = a.finallyLoc, l) : this.complete(i)
                }, complete: function (e, t) {
                    if ("throw" === e.type) throw e.arg;
                    return "break" === e.type || "continue" === e.type ? this.next = e.arg : "return" === e.type ? (this.rval = this.arg = e.arg, this.method = "return", this.next = "end") : "normal" === e.type && t && (this.next = t), l
                }, finish: function (e) {
                    for (var t = this.tryEntries.length - 1; t >= 0; --t) {
                        var r = this.tryEntries[t];
                        if (r.finallyLoc === e) return this.complete(r.completion, r.afterLoc), x(r), l
                    }
                }, catch: function (e) {
                    for (var t = this.tryEntries.length - 1; t >= 0; --t) {
                        var r = this.tryEntries[t];
                        if (r.tryLoc === e) {
                            var n = r.completion;
                            if ("throw" === n.type) {
                                var o = n.arg;
                                x(r)
                            }
                            return o
                        }
                    }
                    throw new Error("illegal catch attempt")
                }, delegateYield: function (e, t, r) {
                    return this.delegate = {
                        iterator: S(e),
                        resultName: t,
                        nextLoc: r
                    }, "next" === this.method && (this.arg = void 0), l
                }
            }, e
        }(e.exports);
        try {
            regeneratorRuntime = n
        } catch (e) {
            Function("r", "regeneratorRuntime = r")(n)
        }
    }
});
