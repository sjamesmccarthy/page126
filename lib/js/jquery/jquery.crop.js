var CROP = function() {
        return function() {
            this.eles = {
                ele: undefined,
                container: undefined,
                img: undefined,
                overlay: undefined
            };
            this.img = undefined;
            this.imgInfo = {
                aw: 0,
                ah: 0,
                w: 0,
                h: 0,
                at: 0,
                al: 0,
                t: 0,
                l: 0,
                s: 1
            };
            this.init = function(e) {
                this.settings = {
                    slider: e + " .cropSlider"
                };
                var e = $(e + " .cropMain"),
                    t, n, r, i = this;
                n = $("<div />").attr({
                    "class": "crop-container"
                }).css({
                    width: e.width(),
                    height: e.height()
                });
                t = $("<img />").attr({
                    "class": "crop-img"
                }).css({
                    zIndex: 5999,
                    top: 0,
                    left: 0
                });
                r = $("<div />").attr({
                    "class": "crop-overlay"
                }).css({
                    zIndex: 6e3
                });
                n.append(r);
                n.append(t);
                e.append(n);
                this.eles.ele = e;
                this.eles.container = n;
                this.eles.img = t;
                this.eles.overlay = r;
                n.resize(function() {
                    i.imgSize()
                });
                r.bind(document.ontouchstart !== null ? "mousedown" : "touchstart", function(e) {
                    var t = $(this),
                        n = {
                            x: e.pageX || e.originalEvent.pageX,
                            y: e.pageY || e.originalEvent.pageY
                        },
                        s = {
                            x: t.parent().offset().left,
                            y: t.parent().offset().top
                        };
                    e.preventDefault();
                    $(document).bind(document.ontouchmove !== null ? "mousemove" : "touchmove", function(e) {
                        if (e.pageX || typeof e.originalEvent.changedTouches[0] !== undefined) {
                            var r = {
                                x: e.pageX || e.originalEvent.changedTouches[0].pageX,
                                y: e.pageY || e.originalEvent.changedTouches[0].pageY
                            };
                            if (parseInt(t.css("top")) == 0) {
                                t.css({
                                    top: i.eles.ele.offset().top,
                                    left: i.eles.ele.offset().left
                                })
                            }
                            i.imgMove({
                                t: parseInt(t.css("top")) - (s.y - (n.y - r.y)),
                                l: parseInt(t.css("left")) - (s.x - (n.x - r.x))
                            });
                            t.css({
                                left: s.x - (n.x - r.x),
                                top: s.y - (n.y - r.y)
                            })
                        }
                    });
                    $(document).bind(document.ontouchend !== null ? "mouseup" : "touchend", function(e) {
                        $(document).unbind(document.ontouchmove !== null ? "mousemove" : "touchmove");
                        r.css({
                            top: 0,
                            left: 0
                        })
                    });
                    return false
                });
                this.slider()
            };
            this.loadImg = function(e) {
                var t = this;
                this.eles.img.attr("src", e).load(function() {
                    t.imgSize()
                })
            };
            this.imgSize = function() {
                var e = this.eles.img,
                    t = {
                        w: e.css("width", "").width(),
                        h: e.css("height", "").height()
                    },
                    n = this.eles.container;
                var r = {
                    wh: this.eles.container.width() / this.eles.container.height(),
                    hw: this.eles.container.height() / this.eles.container.width()
                };
                this.imgInfo.aw = t.w;
                this.imgInfo.ah = t.h;
                if (t.w * r.hw < t.h * r.wh) {
                    this.imgInfo.w = n.width() - 40 * 2;
                    this.imgInfo.h = this.imgInfo.w * (t.h / t.w);
                    this.imgInfo.al = 40
                } else {
                    this.imgInfo.h = n.height() - 40 * 2;
                    this.imgInfo.w = this.imgInfo.h * (t.w / t.h);
                    this.imgInfo.at = 40
                }
                this.imgResize()
            };
            this.imgResize = function(e) {
                var t = this.eles.img,
                    n = this.imgInfo,
                    r = n.s;
                n.s = e || n.s;
                t.css({
                    width: n.w * n.s,
                    height: n.h * n.s
                });
                this.imgMove({
                    t: -(n.h * r - n.h * n.s) / 2,
                    l: -(n.w * r - n.w * n.s) / 2
                })
            };
            this.imgMove = function(e) {
                var t = this.eles.img,
                    n = this.imgInfo,
                    r = this.eles.container;
                n.t += e.t;
                n.l += e.l;
                var i = n.at - n.t,
                    s = n.al - n.l;
                if (i > 40) {
                    i = 40;
                    n.t = n.at == 40 ? 0 : -40
                } else if (i < -(n.h * n.s - (r.height() - 40))) {
                    i = -(n.h * n.s - (r.height() - 40));
                    n.t = n.at == 40 ? n.h * n.s - (r.height() - 80) : n.h * n.s - (r.height() - 40)
                }
                if (s > 40) {
                    s = 40;
                    n.l = n.al == 40 ? 0 : -40
                } else if (s < -(n.w * n.s - (r.width() - 40))) {
                    s = -(n.w * n.s - (r.width() - 40));
                    n.l = n.al == 40 ? n.w * n.s - (r.width() - 80) : n.w * n.s - (r.width() - 40)
                }
                t.css({
                    top: i,
                    left: s
                })
            };
            this.slider = function() {
                var e = this;
                $(this.settings.slider).noUiSlider({
                    range: [1, 4],
                    start: 1,
                    step: .002,
                    handles: 1,
                    slide: function() {
                        var t = $(this).val();
                        e.imgResize(t)
                    }
                })
            };
            coordinates = function e(t) {
                var n = t.imgInfo,
                    r = t.eles.container,
                    i = t.eles.img,
                    s = i.attr("src"),
                    e = {
                        x: -(parseInt(i.css("left")) - 40) * (n.aw / (n.w * n.s)),
                        y: -(parseInt(i.css("top")) - 40) * (n.ah / (n.h * n.s)),
                        w: (r.width() - 40 * 2) * (n.aw / (n.w * n.s)),
                        h: (r.height() - 40 * 2) * (n.ah / (n.h * n.s)),
                        image: s
                    };
                return e
            }
        }
    }();
(function(e, t) {
    if (e.zepto && !e.fn.removeData) throw new ReferenceError("Zepto is loaded without the data module.");
    e.fn.noUiSlider = function(n) {
        function r(t, n, r) {
            e.isArray(t) || (t = [t]);
            e.each(t, function(e, t) {
                "function" === typeof t && t.call(n, r)
            })
        }
        function i(t) {
            return t instanceof e || e.zepto && e.zepto.isZ(t)
        }
        function s(n) {
            n.preventDefault();
            var r = 0 === n.type.indexOf("touch"),
                i = 0 === n.type.indexOf("mouse"),
                s = 0 === n.type.indexOf("pointer"),
                o, u, a = n;
            0 === n.type.indexOf("MSPointer") && (s = !0);
            n.originalEvent && (n = n.originalEvent);
            r && (o = n.changedTouches[0].pageX, u = n.changedTouches[0].pageY);
            if (i || s) s || window.pageXOffset !== t || (window.pageXOffset = document.documentElement.scrollLeft, window.pageYOffset = document.documentElement.scrollTop), o = n.clientX + window.pageXOffset, u = n.clientY + window.pageYOffset;
            return e.extend(a, {
                x: o,
                y: u
            })
        }
        function o(t, n, r, i, o) {
            t = t.replace(/\s/g, E + " ") + E;
            if (o) return 1 < o && (i = e.extend(n, i)), n.on(t, e.proxy(r, i));
            i.handler = r;
            return n.on(t, e.proxy(function(e) {
                if (this.target.is('[class*="noUi-state-"], [disabled]')) return !1;
                this.handler(s(e))
            }, i))
        }
        function u(e) {
            return !isNaN(parseFloat(e)) && isFinite(e)
        }
        function a(e) {
            return parseFloat(this.style[e])
        }
        function f(t, n) {
            function r(e) {
                return i(e) || "string" === typeof e || !1 === e
            }
            var s = {
                handles: {
                    r: !0,
                    t: function(e) {
                        e = parseInt(e, 10);
                        return 1 === e || 2 === e
                    }
                },
                range: {
                    r: !0,
                    t: function(e, t, n) {
                        if (2 !== e.length) return !1;
                        e = [parseFloat(e[0]), parseFloat(e[1])];
                        if (!u(e[0]) || !u(e[1]) || "range" === n && e[0] === e[1] || e[1] < e[0]) return !1;
                        t[n] = e;
                        return !0
                    }
                },
                start: {
                    r: !0,
                    t: function(t, n, r) {
                        return 1 === n.handles ? (e.isArray(t) && (t = t[0]), t = parseFloat(t), n.start = [t], u(t)) : this.parent.range.t(t, n, r)
                    }
                },
                connect: {
                    t: function(e, t) {
                        return !0 === e || !1 === e || "lower" === e && 1 === t.handles || "upper" === e && 1 === t.handles
                    }
                },
                orientation: {
                    t: function(e) {
                        return "horizontal" === e || "vertical" === e
                    }
                },
                margin: {
                    r: !0,
                    t: function(e, t, n) {
                        e = parseFloat(e);
                        t[n] = e;
                        return u(e)
                    }
                },
                serialization: {
                    r: !0,
                    t: function(t, n) {
                        if (t.resolution) switch (t.resolution) {
                        case 1:
                        case.1:
                        case.01:
                        case.001:
                        case 1e-4:
                        case 1e-5:
                            break;
                        default:
                            return !1
                        } else n.serialization.resolution = .01;
                        if (t.mark) return "." === t.mark || "," === t.mark;
                        n.serialization.mark = ".";
                        return t.to ? 1 === n.handles ? (e.isArray(t.to) || (t.to = [t.to]), n.serialization.to = t.to, r(t.to[0])) : 2 === t.to.length && r(t.to[0]) && r(t.to[1]) : !1
                    }
                },
                slide: {
                    t: function(e) {
                        return "function" === typeof e
                    }
                },
                set: {
                    t: function(e, t) {
                        return this.parent.slide.t(e, t)
                    }
                },
                step: {
                    t: function(e, t, n) {
                        return this.parent.margin.t(e, t, n)
                    }
                },
                init: function() {
                    var t = this;
                    e.each(t, function(e, n) {
                        n.parent = t
                    });
                    delete this.init;
                    return this
                }
            }.init();
            e.each(s, function(e, r) {
                if (r.r && !t[e] && 0 !== t[e] || (t[e] || 0 === t[e]) && !r.t(t[e], t, e)) throw console && console.log && console.group && (console.group("Invalid noUiSlider initialisation:"), console.log("Option:    ", e), console.log("Value:	", t[e]), console.log("Slider:	", n[0]), console.groupEnd()), new RangeError("noUiSlider")
            })
        }
        function l(e, t) {
            e = e.toFixed(t.data("decimals"));
            return e.replace(".", t.data("mark"))
        }
        function c(e, t, n) {
            var r = e.data("nui").options,
                i = e.data("nui").base.data("handles"),
                s = e.data("nui").style;
            if (!u(t) || t === e[0].gPct(s)) return !1;
            t = 0 > t ? 0 : 100 < t ? 100 : t;
            if (r.step && !n) {
                var o = A.from(r.range, r.step);
                t = Math.round(t / o) * o
            }
            if (t === e[0].gPct(s) || e.siblings("." + N[1]).length && !n && i && (e.data("nui").number ? (n = i[0][0].gPct(s) + r.margin, t = t < n ? n : t) : (n = i[1][0].gPct(s) - r.margin, t = t > n ? n : t), t === e[0].gPct(s))) return !1;
            0 === e.data("nui").number && 95 < t ? e.addClass(N[13]) : e.removeClass(N[13]);
            e.css(s, t + "%");
            e.data("store").val(l(A.is(r.range, t), e.data("nui").target));
            return !0
        }
        function h(n, r) {
            var s = n.data("nui").number,
                u = {
                    target: n.data("nui").target,
                    options: n.data("nui").options,
                    handle: n,
                    i: s
                };
            if (i(r.to[s])) return o("change blur", r.to[s], O[0], u, 2), o("change", r.to[s], u.options.set, u.target, 1), r.to[s];
            if ("string" === typeof r.to[s]) return e('<input type="hidden" name="' + r.to[s] + '">').appendTo(n).addClass(N[3]).change(O[1]);
            if (!1 === r.to[s]) return {
                val: function(e) {
                    if (e === t) return this.handleElement.data("nui-val");
                    this.handleElement.data("nui-val", e)
                },
                hasClass: function() {
                    return !1
                },
                handleElement: n
            }
        }
        function d(e) {
            var t = this.base,
                n = t.data("style"),
                i = e.x - this.startEvent.x,
                s = "left" === n ? t.width() : t.height();
            "top" === n && (i = e.y - this.startEvent.y);
            i = this.position + 100 * i / s;
            c(this.handle, i);
            r(t.data("options").slide, t.data("target"))
        }
        function v() {
            var t = this.base,
                n = this.handle;
            n.children().removeClass(N[4]);
            S.off(x.move);
            S.off(x.end);
            e("body").off(E);
            t.data("target").change();
            r(n.data("nui").options.set, t.data("target"))
        }
        function m(t) {
            var n = this.handle,
                r = n[0].gPct(n.data("nui").style);
            n.children().addClass(N[4]);
            o(x.move, S, d, {
                startEvent: t,
                position: r,
                base: this.base,
                target: this.target,
                handle: n
            });
            o(x.end, S, v, {
                base: this.base,
                target: this.target,
                handle: n
            });
            e("body").on("selectstart" + E, function() {
                return !1
            })
        }
        function g(e) {
            e.stopPropagation();
            v.call(this)
        }
        function y(e) {
            if (!this.base.find("." + N[4]).length) {
                var t, n, i = this.base;
                n = this.handles;
                var s = i.data("style");
                e = e["left" === s ? "x" : "y"];
                var o = "left" === s ? i.width() : i.height(),
                    u = [],
                    a = {
                        left: i.offset().left,
                        top: i.offset().top
                    };
                for (t = 0; t < n.length; t++) u.push({
                    left: n[t].offset().left,
                    top: n[t].offset().top
                });
                t = 1 === n.length ? 0 : (u[0][s] + u[1][s]) / 2;
                n = 1 === n.length || e < t ? n[0] : n[1];
                i.addClass(N[5]);
                setTimeout(function() {
                    i.removeClass(N[5])
                }, 300);
                c(n, 100 * (e - a[s]) / o);
                r([n.data("nui").options.slide, n.data("nui").options.set], i.data("target"));
                i.data("target").change()
            }
        }
        function b() {
            var t = [];
            e.each(e(this).data("handles"), function(e, n) {
                t.push(n.data("store").val())
            });
            return 1 === t.length ? t[0] : t
        }
        function w(n, i) {
            if (n === t) return b.call(this);
            i = !0 === i ? {
                trigger: !0
            } : i || {};
            e.isArray(n) || (n = [n]);
            return this.each(function(s, o) {
                o = e(o);
                e.each(e(this).data("handles"), function(s, u) {
                    if (null !== n[s] && n[s] !== t) {
                        var a, f;
                        f = u.data("nui").options.range;
                        a = n[s];
                        i.trusted = !0;
                        if (!1 === i.trusted || 1 === n.length) i.trusted = !1;
                        2 === n.length && 0 <= e.inArray(null, n) && (i.trusted = !1);
                        "string" === e.type(a) && (a = a.replace(",", "."));
                        a = A.to(f, parseFloat(a));
                        a = c(u, a, i.trusted);
                        i.trigger && r(u.data("nui").options.set, o);
                        a || (a = u.data("store").val(), f = A.is(f, u[0].gPct(u.data("nui").style)), a !== f && u.data("store").val(l(f, o)))
                    }
                })
            })
        }
        var E = ".nui",
            S = e(document),
            x = {
                start: "mousedown touchstart",
                move: "mousemove touchmove",
                end: "mouseup touchend"
            },
            T = e.fn.val,
            N = "noUi-base noUi-origin noUi-handle noUi-input noUi-active noUi-state-tap noUi-target -lower -upper noUi-connect noUi-vertical noUi-horizontal noUi-background noUi-z-index".split(" "),
            C = [N[0]],
            k = [N[1]],
            L = [N[2]],
            A = {
                to: function(e, t) {
                    t = 0 > e[0] ? t + Math.abs(e[0]) : t - e[0];
                    return 100 * t / this.len(e)
                },
                from: function(e, t) {
                    return 100 * t / this.len(e)
                },
                is: function(e, t) {
                    return t * this.len(e) / 100 + e[0]
                },
                len: function(e) {
                    return e[0] > e[1] ? e[0] - e[1] : e[1] - e[0]
                }
            },
            O = [function() {
                this.target.val([this.i ? null : this.val(), this.i ? this.val() : null], {
                    trusted: !1
                })
            }, function(e) {
                e.stopPropagation()
            }];
        window.navigator.pointerEnabled ? x = {
            start: "pointerdown",
            move: "pointermove",
            end: "pointerup"
        } : window.navigator.msPointerEnabled && (x = {
            start: "MSPointerDown",
            move: "MSPointerMove",
            end: "MSPointerUp"
        });
        e.fn.val = function() {
            return this.hasClass(N[6]) ? w.apply(this, arguments) : T.apply(this, arguments)
        };
        return function(t) {
            return this.each(function(n, r) {
                r = e(r);
                r.addClass(N[6]);
                var i, s, u, l, p = e("<div/>").appendTo(r),
                    d = [],
                    v = [k.concat([N[1] + N[7]]), k.concat([N[1] + N[8]])],
                    b = [L.concat([N[2] + N[7]]), L.concat([N[2] + N[8]])];
                t = e.extend({
                    handles: 2,
                    margin: 0,
                    orientation: "horizontal"
                }, t) || {};
                t.serialization || (t.serialization = {
                    to: [!1, !1],
                    resolution: .01,
                    mark: "."
                });
                f(t, r);
                t.S = t.serialization;
                t.connect ? "lower" === t.connect ? (C.push(N[9], N[9] + N[7]), v[0].push(N[12])) : (C.push(N[9] + N[8], N[12]), v[0].push(N[9])) : C.push(N[12]);
                s = "vertical" === t.orientation ? "top" : "left";
                u = t.S.resolution.toString().split(".");
                u = "1" === u[0] ? 0 : u[1].length;
                "vertical" === t.orientation ? C.push(N[10]) : C.push(N[11]);
                p.addClass(C.join(" ")).data("target", r);
                r.data({
                    base: p,
                    mark: t.S.mark,
                    decimals: u
                });
                for (i = 0; i < t.handles; i++) l = e("<div><div/></div>").appendTo(p), l.addClass(v[i].join(" ")), l.children().addClass(b[i].join(" ")), o(x.start, l.children(), m, {
                    base: p,
                    target: r,
                    handle: l
                }), o(x.end, l.children(), g, {
                    base: p,
                    target: r,
                    handle: l
                }), l.data("nui", {
                    target: r,
                    decimals: u,
                    options: t,
                    base: p,
                    style: s,
                    number: i
                }).data("store", h(l, t.S)), l[0].gPct = a, d.push(l), c(l, A.to(t.range, t.start[i]));
                p.data({
                    options: t,
                    handles: d,
                    style: s
                });
                r.data({
                    handles: d
                });
                o(x.end, p, y, {
                    base: p,
                    target: r,
                    handles: d
                })
            })
        }.call(this, n)
    }
})($)