! function (e, t) {
    "use strict";
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function (e) {
        if (!e.document) throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function (T, e) {
    "use strict";
    var t = [],
        x = T.document,
        i = Object.getPrototypeOf,
        a = t.slice,
        g = t.concat,
        l = t.push,
        r = t.indexOf,
        n = {},
        o = n.toString,
        m = n.hasOwnProperty,
        s = m.toString,
        u = s.call(Object),
        v = {},
        y = function (e) {
            return "function" == typeof e && "number" != typeof e.nodeType
        },
        _ = function (e) {
            return null != e && e === e.window
        },
        c = {
            type: !0,
            src: !0,
            noModule: !0
        };

    function b(e, t, n) {
        var i, r = (t = t || x).createElement("script");
        if (r.text = e, n)
            for (i in c) n[i] && (r[i] = n[i]);
        t.head.appendChild(r).parentNode.removeChild(r)
    }

    function w(e) {
        return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? n[o.call(e)] || "object" : typeof e
    }
    var C = function (e, t) {
            return new C.fn.init(e, t)
        },
        f = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;

    function d(e) {
        var t = !!e && "length" in e && e.length,
            n = w(e);
        return !y(e) && !_(e) && ("array" === n || 0 === t || "number" == typeof t && 0 < t && t - 1 in e)
    }
    C.fn = C.prototype = {
        jquery: "3.3.1",
        constructor: C,
        length: 0,
        toArray: function () {
            return a.call(this)
        },
        get: function (e) {
            return null == e ? a.call(this) : e < 0 ? this[e + this.length] : this[e]
        },
        pushStack: function (e) {
            var t = C.merge(this.constructor(), e);
            return t.prevObject = this, t
        },
        each: function (e) {
            return C.each(this, e)
        },
        map: function (n) {
            return this.pushStack(C.map(this, function (e, t) {
                return n.call(e, t, e)
            }))
        },
        slice: function () {
            return this.pushStack(a.apply(this, arguments))
        },
        first: function () {
            return this.eq(0)
        },
        last: function () {
            return this.eq(-1)
        },
        eq: function (e) {
            var t = this.length,
                n = +e + (e < 0 ? t : 0);
            return this.pushStack(0 <= n && n < t ? [this[n]] : [])
        },
        end: function () {
            return this.prevObject || this.constructor()
        },
        push: l,
        sort: t.sort,
        splice: t.splice
    }, C.extend = C.fn.extend = function () {
        var e, t, n, i, r, o, s = arguments[0] || {},
            a = 1,
            l = arguments.length,
            u = !1;
        for ("boolean" == typeof s && (u = s, s = arguments[a] || {}, a++), "object" == typeof s || y(s) || (s = {}), a === l && (s = this, a--); a < l; a++)
            if (null != (e = arguments[a]))
                for (t in e) n = s[t], s !== (i = e[t]) && (u && i && (C.isPlainObject(i) || (r = Array.isArray(i))) ? (o = r ? (r = !1, n && Array.isArray(n) ? n : []) : n && C.isPlainObject(n) ? n : {}, s[t] = C.extend(u, o, i)) : void 0 !== i && (s[t] = i));
        return s
    }, C.extend({
        expando: "jQuery" + ("3.3.1" + Math.random()).replace(/\D/g, ""),
        isReady: !0,
        error: function (e) {
            throw new Error(e)
        },
        noop: function () {},
        isPlainObject: function (e) {
            var t, n;
            return !(!e || "[object Object]" !== o.call(e) || (t = i(e)) && ("function" != typeof (n = m.call(t, "constructor") && t.constructor) || s.call(n) !== u))
        },
        isEmptyObject: function (e) {
            var t;
            for (t in e) return !1;
            return !0
        },
        globalEval: function (e) {
            b(e)
        },
        each: function (e, t) {
            var n, i = 0;
            if (d(e))
                for (n = e.length; i < n && !1 !== t.call(e[i], i, e[i]); i++);
            else
                for (i in e)
                    if (!1 === t.call(e[i], i, e[i])) break;
            return e
        },
        trim: function (e) {
            return null == e ? "" : (e + "").replace(f, "")
        },
        makeArray: function (e, t) {
            var n = t || [];
            return null != e && (d(Object(e)) ? C.merge(n, "string" == typeof e ? [e] : e) : l.call(n, e)), n
        },
        inArray: function (e, t, n) {
            return null == t ? -1 : r.call(t, e, n)
        },
        merge: function (e, t) {
            for (var n = +t.length, i = 0, r = e.length; i < n; i++) e[r++] = t[i];
            return e.length = r, e
        },
        grep: function (e, t, n) {
            for (var i = [], r = 0, o = e.length, s = !n; r < o; r++) !t(e[r], r) !== s && i.push(e[r]);
            return i
        },
        map: function (e, t, n) {
            var i, r, o = 0,
                s = [];
            if (d(e))
                for (i = e.length; o < i; o++) null != (r = t(e[o], o, n)) && s.push(r);
            else
                for (o in e) null != (r = t(e[o], o, n)) && s.push(r);
            return g.apply([], s)
        },
        guid: 1,
        support: v
    }), "function" == typeof Symbol && (C.fn[Symbol.iterator] = t[Symbol.iterator]), C.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (e, t) {
        n["[object " + t + "]"] = t.toLowerCase()
    });
    var h = function (n) {
        var e, h, b, o, r, p, f, g, w, l, u, E, T, s, x, m, a, c, v, C = "sizzle" + 1 * new Date,
            y = n.document,
            D = 0,
            i = 0,
            d = se(),
            _ = se(),
            S = se(),
            A = function (e, t) {
                return e === t && (u = !0), 0
            },
            N = {}.hasOwnProperty,
            t = [],
            k = t.pop,
            I = t.push,
            O = t.push,
            j = t.slice,
            L = function (e, t) {
                for (var n = 0, i = e.length; n < i; n++)
                    if (e[n] === t) return n;
                return -1
            },
            P = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            H = "[\\x20\\t\\r\\n\\f]",
            R = "(?:\\\\.|[\\w-]|[^\0-\\xa0])+",
            q = "\\[" + H + "*(" + R + ")(?:" + H + "*([*^$|!~]?=)" + H + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + R + "))|)" + H + "*\\]",
            W = ":(" + R + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + q + ")*)|.*)\\)|)",
            M = new RegExp(H + "+", "g"),
            F = new RegExp("^" + H + "+|((?:^|[^\\\\])(?:\\\\.)*)" + H + "+$", "g"),
            B = new RegExp("^" + H + "*," + H + "*"),
            U = new RegExp("^" + H + "*([>+~]|" + H + ")" + H + "*"),
            K = new RegExp("=" + H + "*([^\\]'\"]*?)" + H + "*\\]", "g"),
            $ = new RegExp(W),
            Q = new RegExp("^" + R + "$"),
            V = {
                ID: new RegExp("^#(" + R + ")"),
                CLASS: new RegExp("^\\.(" + R + ")"),
                TAG: new RegExp("^(" + R + "|[*])"),
                ATTR: new RegExp("^" + q),
                PSEUDO: new RegExp("^" + W),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + H + "*(even|odd|(([+-]|)(\\d*)n|)" + H + "*(?:([+-]|)" + H + "*(\\d+)|))" + H + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + P + ")$", "i"),
                needsContext: new RegExp("^" + H + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + H + "*((?:-\\d)?\\d*)" + H + "*\\)|)(?=[^-]|$)", "i")
            },
            Y = /^(?:input|select|textarea|button)$/i,
            z = /^h\d$/i,
            X = /^[^{]+\{\s*\[native \w/,
            G = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
            J = /[+~]/,
            Z = new RegExp("\\\\([\\da-f]{1,6}" + H + "?|(" + H + ")|.)", "ig"),
            ee = function (e, t, n) {
                var i = "0x" + t - 65536;
                return i != i || n ? t : i < 0 ? String.fromCharCode(i + 65536) : String.fromCharCode(i >> 10 | 55296, 1023 & i | 56320)
            },
            te = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g,
            ne = function (e, t) {
                return t ? "\0" === e ? "�" : e.slice(0, -1) + "\\" + e.charCodeAt(e.length - 1).toString(16) + " " : "\\" + e
            },
            ie = function () {
                E()
            },
            re = ye(function (e) {
                return !0 === e.disabled && ("form" in e || "label" in e)
            }, {
                dir: "parentNode",
                next: "legend"
            });
        try {
            O.apply(t = j.call(y.childNodes), y.childNodes), t[y.childNodes.length].nodeType
        } catch (n) {
            O = {
                apply: t.length ? function (e, t) {
                    I.apply(e, j.call(t))
                } : function (e, t) {
                    for (var n = e.length, i = 0; e[n++] = t[i++];);
                    e.length = n - 1
                }
            }
        }

        function oe(e, t, n, i) {
            var r, o, s, a, l, u, c, f = t && t.ownerDocument,
                d = t ? t.nodeType : 9;
            if (n = n || [], "string" != typeof e || !e || 1 !== d && 9 !== d && 11 !== d) return n;
            if (!i && ((t ? t.ownerDocument || t : y) !== T && E(t), t = t || T, x)) {
                if (11 !== d && (l = G.exec(e)))
                    if (r = l[1]) {
                        if (9 === d) {
                            if (!(s = t.getElementById(r))) return n;
                            if (s.id === r) return n.push(s), n
                        } else if (f && (s = f.getElementById(r)) && v(t, s) && s.id === r) return n.push(s), n
                    } else {
                        if (l[2]) return O.apply(n, t.getElementsByTagName(e)), n;
                        if ((r = l[3]) && h.getElementsByClassName && t.getElementsByClassName) return O.apply(n, t.getElementsByClassName(r)), n
                    } if (h.qsa && !S[e + " "] && (!m || !m.test(e))) {
                    if (1 !== d) f = t, c = e;
                    else if ("object" !== t.nodeName.toLowerCase()) {
                        for ((a = t.getAttribute("id")) ? a = a.replace(te, ne) : t.setAttribute("id", a = C), o = (u = p(e)).length; o--;) u[o] = "#" + a + " " + ve(u[o]);
                        c = u.join(","), f = J.test(e) && ge(t.parentNode) || t
                    }
                    if (c) try {
                        return O.apply(n, f.querySelectorAll(c)), n
                    } catch (e) {} finally {
                        a === C && t.removeAttribute("id")
                    }
                }
            }
            return g(e.replace(F, "$1"), t, n, i)
        }

        function se() {
            var i = [];
            return function e(t, n) {
                return i.push(t + " ") > b.cacheLength && delete e[i.shift()], e[t + " "] = n
            }
        }

        function ae(e) {
            return e[C] = !0, e
        }

        function le(e) {
            var t = T.createElement("fieldset");
            try {
                return !!e(t)
            } catch (e) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function ue(e, t) {
            for (var n = e.split("|"), i = n.length; i--;) b.attrHandle[n[i]] = t
        }

        function ce(e, t) {
            var n = t && e,
                i = n && 1 === e.nodeType && 1 === t.nodeType && e.sourceIndex - t.sourceIndex;
            if (i) return i;
            if (n)
                for (; n = n.nextSibling;)
                    if (n === t) return -1;
            return e ? 1 : -1
        }

        function fe(t) {
            return function (e) {
                return "input" === e.nodeName.toLowerCase() && e.type === t
            }
        }

        function de(n) {
            return function (e) {
                var t = e.nodeName.toLowerCase();
                return ("input" === t || "button" === t) && e.type === n
            }
        }

        function he(t) {
            return function (e) {
                return "form" in e ? e.parentNode && !1 === e.disabled ? "label" in e ? "label" in e.parentNode ? e.parentNode.disabled === t : e.disabled === t : e.isDisabled === t || e.isDisabled !== !t && re(e) === t : e.disabled === t : "label" in e && e.disabled === t
            }
        }

        function pe(s) {
            return ae(function (o) {
                return o = +o, ae(function (e, t) {
                    for (var n, i = s([], e.length, o), r = i.length; r--;) e[n = i[r]] && (e[n] = !(t[n] = e[n]))
                })
            })
        }

        function ge(e) {
            return e && void 0 !== e.getElementsByTagName && e
        }
        for (e in h = oe.support = {}, r = oe.isXML = function (e) {
                var t = e && (e.ownerDocument || e).documentElement;
                return !!t && "HTML" !== t.nodeName
            }, E = oe.setDocument = function (e) {
                var t, n, i = e ? e.ownerDocument || e : y;
                return i !== T && 9 === i.nodeType && i.documentElement && (s = (T = i).documentElement, x = !r(T), y !== T && (n = T.defaultView) && n.top !== n && (n.addEventListener ? n.addEventListener("unload", ie, !1) : n.attachEvent && n.attachEvent("onunload", ie)), h.attributes = le(function (e) {
                    return e.className = "i", !e.getAttribute("className")
                }), h.getElementsByTagName = le(function (e) {
                    return e.appendChild(T.createComment("")), !e.getElementsByTagName("*").length
                }), h.getElementsByClassName = X.test(T.getElementsByClassName), h.getById = le(function (e) {
                    return s.appendChild(e).id = C, !T.getElementsByName || !T.getElementsByName(C).length
                }), h.getById ? (b.filter.ID = function (e) {
                    var t = e.replace(Z, ee);
                    return function (e) {
                        return e.getAttribute("id") === t
                    }
                }, b.find.ID = function (e, t) {
                    if (void 0 !== t.getElementById && x) {
                        var n = t.getElementById(e);
                        return n ? [n] : []
                    }
                }) : (b.filter.ID = function (e) {
                    var n = e.replace(Z, ee);
                    return function (e) {
                        var t = void 0 !== e.getAttributeNode && e.getAttributeNode("id");
                        return t && t.value === n
                    }
                }, b.find.ID = function (e, t) {
                    if (void 0 !== t.getElementById && x) {
                        var n, i, r, o = t.getElementById(e);
                        if (o) {
                            if ((n = o.getAttributeNode("id")) && n.value === e) return [o];
                            for (r = t.getElementsByName(e), i = 0; o = r[i++];)
                                if ((n = o.getAttributeNode("id")) && n.value === e) return [o]
                        }
                        return []
                    }
                }), b.find.TAG = h.getElementsByTagName ? function (e, t) {
                    return void 0 !== t.getElementsByTagName ? t.getElementsByTagName(e) : h.qsa ? t.querySelectorAll(e) : void 0
                } : function (e, t) {
                    var n, i = [],
                        r = 0,
                        o = t.getElementsByTagName(e);
                    if ("*" !== e) return o;
                    for (; n = o[r++];) 1 === n.nodeType && i.push(n);
                    return i
                }, b.find.CLASS = h.getElementsByClassName && function (e, t) {
                    if (void 0 !== t.getElementsByClassName && x) return t.getElementsByClassName(e)
                }, a = [], m = [], (h.qsa = X.test(T.querySelectorAll)) && (le(function (e) {
                    s.appendChild(e).innerHTML = "<a id='" + C + "'></a><select id='" + C + "-\r\\' msallowcapture=''><option selected=''></option></select>", e.querySelectorAll("[msallowcapture^='']").length && m.push("[*^$]=" + H + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || m.push("\\[" + H + "*(?:value|" + P + ")"), e.querySelectorAll("[id~=" + C + "-]").length || m.push("~="), e.querySelectorAll(":checked").length || m.push(":checked"), e.querySelectorAll("a#" + C + "+*").length || m.push(".#.+[+~]")
                }), le(function (e) {
                    e.innerHTML = "<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";
                    var t = T.createElement("input");
                    t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && m.push("name" + H + "*[*^$|!~]?="), 2 !== e.querySelectorAll(":enabled").length && m.push(":enabled", ":disabled"), s.appendChild(e).disabled = !0, 2 !== e.querySelectorAll(":disabled").length && m.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), m.push(",.*:")
                })), (h.matchesSelector = X.test(c = s.matches || s.webkitMatchesSelector || s.mozMatchesSelector || s.oMatchesSelector || s.msMatchesSelector)) && le(function (e) {
                    h.disconnectedMatch = c.call(e, "*"), c.call(e, "[s!='']:x"), a.push("!=", W)
                }), m = m.length && new RegExp(m.join("|")), a = a.length && new RegExp(a.join("|")), t = X.test(s.compareDocumentPosition), v = t || X.test(s.contains) ? function (e, t) {
                    var n = 9 === e.nodeType ? e.documentElement : e,
                        i = t && t.parentNode;
                    return e === i || !(!i || 1 !== i.nodeType || !(n.contains ? n.contains(i) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(i)))
                } : function (e, t) {
                    if (t)
                        for (; t = t.parentNode;)
                            if (t === e) return !0;
                    return !1
                }, A = t ? function (e, t) {
                    if (e === t) return u = !0, 0;
                    var n = !e.compareDocumentPosition - !t.compareDocumentPosition;
                    return n || (1 & (n = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1) || !h.sortDetached && t.compareDocumentPosition(e) === n ? e === T || e.ownerDocument === y && v(y, e) ? -1 : t === T || t.ownerDocument === y && v(y, t) ? 1 : l ? L(l, e) - L(l, t) : 0 : 4 & n ? -1 : 1)
                } : function (e, t) {
                    if (e === t) return u = !0, 0;
                    var n, i = 0,
                        r = e.parentNode,
                        o = t.parentNode,
                        s = [e],
                        a = [t];
                    if (!r || !o) return e === T ? -1 : t === T ? 1 : r ? -1 : o ? 1 : l ? L(l, e) - L(l, t) : 0;
                    if (r === o) return ce(e, t);
                    for (n = e; n = n.parentNode;) s.unshift(n);
                    for (n = t; n = n.parentNode;) a.unshift(n);
                    for (; s[i] === a[i];) i++;
                    return i ? ce(s[i], a[i]) : s[i] === y ? -1 : a[i] === y ? 1 : 0
                }), T
            }, oe.matches = function (e, t) {
                return oe(e, null, null, t)
            }, oe.matchesSelector = function (e, t) {
                if ((e.ownerDocument || e) !== T && E(e), t = t.replace(K, "='$1']"), h.matchesSelector && x && !S[t + " "] && (!a || !a.test(t)) && (!m || !m.test(t))) try {
                    var n = c.call(e, t);
                    if (n || h.disconnectedMatch || e.document && 11 !== e.document.nodeType) return n
                } catch (e) {}
                return 0 < oe(t, T, null, [e]).length
            }, oe.contains = function (e, t) {
                return (e.ownerDocument || e) !== T && E(e), v(e, t)
            }, oe.attr = function (e, t) {
                (e.ownerDocument || e) !== T && E(e);
                var n = b.attrHandle[t.toLowerCase()],
                    i = n && N.call(b.attrHandle, t.toLowerCase()) ? n(e, t, !x) : void 0;
                return void 0 !== i ? i : h.attributes || !x ? e.getAttribute(t) : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
            }, oe.escape = function (e) {
                return (e + "").replace(te, ne)
            }, oe.error = function (e) {
                throw new Error("Syntax error, unrecognized expression: " + e)
            }, oe.uniqueSort = function (e) {
                var t, n = [],
                    i = 0,
                    r = 0;
                if (u = !h.detectDuplicates, l = !h.sortStable && e.slice(0), e.sort(A), u) {
                    for (; t = e[r++];) t === e[r] && (i = n.push(r));
                    for (; i--;) e.splice(n[i], 1)
                }
                return l = null, e
            }, o = oe.getText = function (e) {
                var t, n = "",
                    i = 0,
                    r = e.nodeType;
                if (r) {
                    if (1 === r || 9 === r || 11 === r) {
                        if ("string" == typeof e.textContent) return e.textContent;
                        for (e = e.firstChild; e; e = e.nextSibling) n += o(e)
                    } else if (3 === r || 4 === r) return e.nodeValue
                } else
                    for (; t = e[i++];) n += o(t);
                return n
            }, (b = oe.selectors = {
                cacheLength: 50,
                createPseudo: ae,
                match: V,
                attrHandle: {},
                find: {},
                relative: {
                    ">": {
                        dir: "parentNode",
                        first: !0
                    },
                    " ": {
                        dir: "parentNode"
                    },
                    "+": {
                        dir: "previousSibling",
                        first: !0
                    },
                    "~": {
                        dir: "previousSibling"
                    }
                },
                preFilter: {
                    ATTR: function (e) {
                        return e[1] = e[1].replace(Z, ee), e[3] = (e[3] || e[4] || e[5] || "").replace(Z, ee), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                    },
                    CHILD: function (e) {
                        return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || oe.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && oe.error(e[0]), e
                    },
                    PSEUDO: function (e) {
                        var t, n = !e[6] && e[2];
                        return V.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && $.test(n) && (t = p(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                    }
                },
                filter: {
                    TAG: function (e) {
                        var t = e.replace(Z, ee).toLowerCase();
                        return "*" === e ? function () {
                            return !0
                        } : function (e) {
                            return e.nodeName && e.nodeName.toLowerCase() === t
                        }
                    },
                    CLASS: function (e) {
                        var t = d[e + " "];
                        return t || (t = new RegExp("(^|" + H + ")" + e + "(" + H + "|$)")) && d(e, function (e) {
                            return t.test("string" == typeof e.className && e.className || void 0 !== e.getAttribute && e.getAttribute("class") || "")
                        })
                    },
                    ATTR: function (n, i, r) {
                        return function (e) {
                            var t = oe.attr(e, n);
                            return null == t ? "!=" === i : !i || (t += "", "=" === i ? t === r : "!=" === i ? t !== r : "^=" === i ? r && 0 === t.indexOf(r) : "*=" === i ? r && -1 < t.indexOf(r) : "$=" === i ? r && t.slice(-r.length) === r : "~=" === i ? -1 < (" " + t.replace(M, " ") + " ").indexOf(r) : "|=" === i && (t === r || t.slice(0, r.length + 1) === r + "-"))
                        }
                    },
                    CHILD: function (p, e, t, g, m) {
                        var v = "nth" !== p.slice(0, 3),
                            y = "last" !== p.slice(-4),
                            _ = "of-type" === e;
                        return 1 === g && 0 === m ? function (e) {
                            return !!e.parentNode
                        } : function (e, t, n) {
                            var i, r, o, s, a, l, u = v !== y ? "nextSibling" : "previousSibling",
                                c = e.parentNode,
                                f = _ && e.nodeName.toLowerCase(),
                                d = !n && !_,
                                h = !1;
                            if (c) {
                                if (v) {
                                    for (; u;) {
                                        for (s = e; s = s[u];)
                                            if (_ ? s.nodeName.toLowerCase() === f : 1 === s.nodeType) return !1;
                                        l = u = "only" === p && !l && "nextSibling"
                                    }
                                    return !0
                                }
                                if (l = [y ? c.firstChild : c.lastChild], y && d) {
                                    for (h = (a = (i = (r = (o = (s = c)[C] || (s[C] = {}))[s.uniqueID] || (o[s.uniqueID] = {}))[p] || [])[0] === D && i[1]) && i[2], s = a && c.childNodes[a]; s = ++a && s && s[u] || (h = a = 0) || l.pop();)
                                        if (1 === s.nodeType && ++h && s === e) {
                                            r[p] = [D, a, h];
                                            break
                                        }
                                } else if (d && (h = a = (i = (r = (o = (s = e)[C] || (s[C] = {}))[s.uniqueID] || (o[s.uniqueID] = {}))[p] || [])[0] === D && i[1]), !1 === h)
                                    for (;
                                        (s = ++a && s && s[u] || (h = a = 0) || l.pop()) && ((_ ? s.nodeName.toLowerCase() !== f : 1 !== s.nodeType) || !++h || (d && ((r = (o = s[C] || (s[C] = {}))[s.uniqueID] || (o[s.uniqueID] = {}))[p] = [D, h]), s !== e)););
                                return (h -= m) === g || h % g == 0 && 0 <= h / g
                            }
                        }
                    },
                    PSEUDO: function (e, o) {
                        var t, s = b.pseudos[e] || b.setFilters[e.toLowerCase()] || oe.error("unsupported pseudo: " + e);
                        return s[C] ? s(o) : 1 < s.length ? (t = [e, e, "", o], b.setFilters.hasOwnProperty(e.toLowerCase()) ? ae(function (e, t) {
                            for (var n, i = s(e, o), r = i.length; r--;) e[n = L(e, i[r])] = !(t[n] = i[r])
                        }) : function (e) {
                            return s(e, 0, t)
                        }) : s
                    }
                },
                pseudos: {
                    not: ae(function (e) {
                        var i = [],
                            r = [],
                            a = f(e.replace(F, "$1"));
                        return a[C] ? ae(function (e, t, n, i) {
                            for (var r, o = a(e, null, i, []), s = e.length; s--;)(r = o[s]) && (e[s] = !(t[s] = r))
                        }) : function (e, t, n) {
                            return i[0] = e, a(i, null, n, r), i[0] = null, !r.pop()
                        }
                    }),
                    has: ae(function (t) {
                        return function (e) {
                            return 0 < oe(t, e).length
                        }
                    }),
                    contains: ae(function (t) {
                        return t = t.replace(Z, ee),
                            function (e) {
                                return -1 < (e.textContent || e.innerText || o(e)).indexOf(t)
                            }
                    }),
                    lang: ae(function (n) {
                        return Q.test(n || "") || oe.error("unsupported lang: " + n), n = n.replace(Z, ee).toLowerCase(),
                            function (e) {
                                var t;
                                do {
                                    if (t = x ? e.lang : e.getAttribute("xml:lang") || e.getAttribute("lang")) return (t = t.toLowerCase()) === n || 0 === t.indexOf(n + "-")
                                } while ((e = e.parentNode) && 1 === e.nodeType);
                                return !1
                            }
                    }),
                    target: function (e) {
                        var t = n.location && n.location.hash;
                        return t && t.slice(1) === e.id
                    },
                    root: function (e) {
                        return e === s
                    },
                    focus: function (e) {
                        return e === T.activeElement && (!T.hasFocus || T.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                    },
                    enabled: he(!1),
                    disabled: he(!0),
                    checked: function (e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && !!e.checked || "option" === t && !!e.selected
                    },
                    selected: function (e) {
                        return e.parentNode && e.parentNode.selectedIndex, !0 === e.selected
                    },
                    empty: function (e) {
                        for (e = e.firstChild; e; e = e.nextSibling)
                            if (e.nodeType < 6) return !1;
                        return !0
                    },
                    parent: function (e) {
                        return !b.pseudos.empty(e)
                    },
                    header: function (e) {
                        return z.test(e.nodeName)
                    },
                    input: function (e) {
                        return Y.test(e.nodeName)
                    },
                    button: function (e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && "button" === e.type || "button" === t
                    },
                    text: function (e) {
                        var t;
                        return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                    },
                    first: pe(function () {
                        return [0]
                    }),
                    last: pe(function (e, t) {
                        return [t - 1]
                    }),
                    eq: pe(function (e, t, n) {
                        return [n < 0 ? n + t : n]
                    }),
                    even: pe(function (e, t) {
                        for (var n = 0; n < t; n += 2) e.push(n);
                        return e
                    }),
                    odd: pe(function (e, t) {
                        for (var n = 1; n < t; n += 2) e.push(n);
                        return e
                    }),
                    lt: pe(function (e, t, n) {
                        for (var i = n < 0 ? n + t : n; 0 <= --i;) e.push(i);
                        return e
                    }),
                    gt: pe(function (e, t, n) {
                        for (var i = n < 0 ? n + t : n; ++i < t;) e.push(i);
                        return e
                    })
                }
            }).pseudos.nth = b.pseudos.eq, {
                radio: !0,
                checkbox: !0,
                file: !0,
                password: !0,
                image: !0
            }) b.pseudos[e] = fe(e);
        for (e in {
                submit: !0,
                reset: !0
            }) b.pseudos[e] = de(e);

        function me() {}

        function ve(e) {
            for (var t = 0, n = e.length, i = ""; t < n; t++) i += e[t].value;
            return i
        }

        function ye(a, e, t) {
            var l = e.dir,
                u = e.next,
                c = u || l,
                f = t && "parentNode" === c,
                d = i++;
            return e.first ? function (e, t, n) {
                for (; e = e[l];)
                    if (1 === e.nodeType || f) return a(e, t, n);
                return !1
            } : function (e, t, n) {
                var i, r, o, s = [D, d];
                if (n) {
                    for (; e = e[l];)
                        if ((1 === e.nodeType || f) && a(e, t, n)) return !0
                } else
                    for (; e = e[l];)
                        if (1 === e.nodeType || f)
                            if (r = (o = e[C] || (e[C] = {}))[e.uniqueID] || (o[e.uniqueID] = {}), u && u === e.nodeName.toLowerCase()) e = e[l] || e;
                            else {
                                if ((i = r[c]) && i[0] === D && i[1] === d) return s[2] = i[2];
                                if ((r[c] = s)[2] = a(e, t, n)) return !0
                            } return !1
            }
        }

        function _e(r) {
            return 1 < r.length ? function (e, t, n) {
                for (var i = r.length; i--;)
                    if (!r[i](e, t, n)) return !1;
                return !0
            } : r[0]
        }

        function be(e, t, n, i, r) {
            for (var o, s = [], a = 0, l = e.length, u = null != t; a < l; a++)(o = e[a]) && (n && !n(o, i, r) || (s.push(o), u && t.push(a)));
            return s
        }

        function we(h, p, g, m, v, e) {
            return m && !m[C] && (m = we(m)), v && !v[C] && (v = we(v, e)), ae(function (e, t, n, i) {
                var r, o, s, a = [],
                    l = [],
                    u = t.length,
                    c = e || function (e, t, n) {
                        for (var i = 0, r = t.length; i < r; i++) oe(e, t[i], n);
                        return n
                    }(p || "*", n.nodeType ? [n] : n, []),
                    f = !h || !e && p ? c : be(c, a, h, n, i),
                    d = g ? v || (e ? h : u || m) ? [] : t : f;
                if (g && g(f, d, n, i), m)
                    for (r = be(d, l), m(r, [], n, i), o = r.length; o--;)(s = r[o]) && (d[l[o]] = !(f[l[o]] = s));
                if (e) {
                    if (v || h) {
                        if (v) {
                            for (r = [], o = d.length; o--;)(s = d[o]) && r.push(f[o] = s);
                            v(null, d = [], r, i)
                        }
                        for (o = d.length; o--;)(s = d[o]) && -1 < (r = v ? L(e, s) : a[o]) && (e[r] = !(t[r] = s))
                    }
                } else d = be(d === t ? d.splice(u, d.length) : d), v ? v(null, t, d, i) : O.apply(t, d)
            })
        }

        function Ee(e) {
            for (var r, t, n, i = e.length, o = b.relative[e[0].type], s = o || b.relative[" "], a = o ? 1 : 0, l = ye(function (e) {
                    return e === r
                }, s, !0), u = ye(function (e) {
                    return -1 < L(r, e)
                }, s, !0), c = [function (e, t, n) {
                    var i = !o && (n || t !== w) || ((r = t).nodeType ? l(e, t, n) : u(e, t, n));
                    return r = null, i
                }]; a < i; a++)
                if (t = b.relative[e[a].type]) c = [ye(_e(c), t)];
                else {
                    if ((t = b.filter[e[a].type].apply(null, e[a].matches))[C]) {
                        for (n = ++a; n < i && !b.relative[e[n].type]; n++);
                        return we(1 < a && _e(c), 1 < a && ve(e.slice(0, a - 1).concat({
                            value: " " === e[a - 2].type ? "*" : ""
                        })).replace(F, "$1"), t, a < n && Ee(e.slice(a, n)), n < i && Ee(e = e.slice(n)), n < i && ve(e))
                    }
                    c.push(t)
                } return _e(c)
        }
        return me.prototype = b.filters = b.pseudos, b.setFilters = new me, p = oe.tokenize = function (e, t) {
            var n, i, r, o, s, a, l, u = _[e + " "];
            if (u) return t ? 0 : u.slice(0);
            for (s = e, a = [], l = b.preFilter; s;) {
                for (o in n && !(i = B.exec(s)) || (i && (s = s.slice(i[0].length) || s), a.push(r = [])), n = !1, (i = U.exec(s)) && (n = i.shift(), r.push({
                        value: n,
                        type: i[0].replace(F, " ")
                    }), s = s.slice(n.length)), b.filter) !(i = V[o].exec(s)) || l[o] && !(i = l[o](i)) || (n = i.shift(), r.push({
                    value: n,
                    type: o,
                    matches: i
                }), s = s.slice(n.length));
                if (!n) break
            }
            return t ? s.length : s ? oe.error(e) : _(e, a).slice(0)
        }, f = oe.compile = function (e, t) {
            var n, m, v, y, _, i, r = [],
                o = [],
                s = S[e + " "];
            if (!s) {
                for (t || (t = p(e)), n = t.length; n--;)(s = Ee(t[n]))[C] ? r.push(s) : o.push(s);
                (s = S(e, (m = o, v = r, y = 0 < v.length, _ = 0 < m.length, i = function (e, t, n, i, r) {
                    var o, s, a, l = 0,
                        u = "0",
                        c = e && [],
                        f = [],
                        d = w,
                        h = e || _ && b.find.TAG("*", r),
                        p = D += null == d ? 1 : Math.random() || .1,
                        g = h.length;
                    for (r && (w = t === T || t || r); u !== g && null != (o = h[u]); u++) {
                        if (_ && o) {
                            for (s = 0, t || o.ownerDocument === T || (E(o), n = !x); a = m[s++];)
                                if (a(o, t || T, n)) {
                                    i.push(o);
                                    break
                                } r && (D = p)
                        }
                        y && ((o = !a && o) && l--, e && c.push(o))
                    }
                    if (l += u, y && u !== l) {
                        for (s = 0; a = v[s++];) a(c, f, t, n);
                        if (e) {
                            if (0 < l)
                                for (; u--;) c[u] || f[u] || (f[u] = k.call(i));
                            f = be(f)
                        }
                        O.apply(i, f), r && !e && 0 < f.length && 1 < l + v.length && oe.uniqueSort(i)
                    }
                    return r && (D = p, w = d), c
                }, y ? ae(i) : i))).selector = e
            }
            return s
        }, g = oe.select = function (e, t, n, i) {
            var r, o, s, a, l, u = "function" == typeof e && e,
                c = !i && p(e = u.selector || e);
            if (n = n || [], 1 === c.length) {
                if (2 < (o = c[0] = c[0].slice(0)).length && "ID" === (s = o[0]).type && 9 === t.nodeType && x && b.relative[o[1].type]) {
                    if (!(t = (b.find.ID(s.matches[0].replace(Z, ee), t) || [])[0])) return n;
                    u && (t = t.parentNode), e = e.slice(o.shift().value.length)
                }
                for (r = V.needsContext.test(e) ? 0 : o.length; r-- && (s = o[r], !b.relative[a = s.type]);)
                    if ((l = b.find[a]) && (i = l(s.matches[0].replace(Z, ee), J.test(o[0].type) && ge(t.parentNode) || t))) {
                        if (o.splice(r, 1), !(e = i.length && ve(o))) return O.apply(n, i), n;
                        break
                    }
            }
            return (u || f(e, c))(i, t, !x, n, !t || J.test(e) && ge(t.parentNode) || t), n
        }, h.sortStable = C.split("").sort(A).join("") === C, h.detectDuplicates = !!u, E(), h.sortDetached = le(function (e) {
            return 1 & e.compareDocumentPosition(T.createElement("fieldset"))
        }), le(function (e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || ue("type|href|height|width", function (e, t, n) {
            if (!n) return e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), h.attributes && le(function (e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || ue("value", function (e, t, n) {
            if (!n && "input" === e.nodeName.toLowerCase()) return e.defaultValue
        }), le(function (e) {
            return null == e.getAttribute("disabled")
        }) || ue(P, function (e, t, n) {
            var i;
            if (!n) return !0 === e[t] ? t.toLowerCase() : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
        }), oe
    }(T);
    C.find = h, C.expr = h.selectors, C.expr[":"] = C.expr.pseudos, C.uniqueSort = C.unique = h.uniqueSort, C.text = h.getText, C.isXMLDoc = h.isXML, C.contains = h.contains, C.escapeSelector = h.escape;
    var p = function (e, t, n) {
            for (var i = [], r = void 0 !== n;
                (e = e[t]) && 9 !== e.nodeType;)
                if (1 === e.nodeType) {
                    if (r && C(e).is(n)) break;
                    i.push(e)
                } return i
        },
        E = function (e, t) {
            for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
            return n
        },
        D = C.expr.match.needsContext;

    function S(e, t) {
        return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
    }
    var A = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;

    function N(e, n, i) {
        return y(n) ? C.grep(e, function (e, t) {
            return !!n.call(e, t, e) !== i
        }) : n.nodeType ? C.grep(e, function (e) {
            return e === n !== i
        }) : "string" != typeof n ? C.grep(e, function (e) {
            return -1 < r.call(n, e) !== i
        }) : C.filter(n, e, i)
    }
    C.filter = function (e, t, n) {
        var i = t[0];
        return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === i.nodeType ? C.find.matchesSelector(i, e) ? [i] : [] : C.find.matches(e, C.grep(t, function (e) {
            return 1 === e.nodeType
        }))
    }, C.fn.extend({
        find: function (e) {
            var t, n, i = this.length,
                r = this;
            if ("string" != typeof e) return this.pushStack(C(e).filter(function () {
                for (t = 0; t < i; t++)
                    if (C.contains(r[t], this)) return !0
            }));
            for (n = this.pushStack([]), t = 0; t < i; t++) C.find(e, r[t], n);
            return 1 < i ? C.uniqueSort(n) : n
        },
        filter: function (e) {
            return this.pushStack(N(this, e || [], !1))
        },
        not: function (e) {
            return this.pushStack(N(this, e || [], !0))
        },
        is: function (e) {
            return !!N(this, "string" == typeof e && D.test(e) ? C(e) : e || [], !1).length
        }
    });
    var k, I = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;
    (C.fn.init = function (e, t, n) {
        var i, r;
        if (!e) return this;
        if (n = n || k, "string" != typeof e) return e.nodeType ? (this[0] = e, this.length = 1, this) : y(e) ? void 0 !== n.ready ? n.ready(e) : e(C) : C.makeArray(e, this);
        if (!(i = "<" === e[0] && ">" === e[e.length - 1] && 3 <= e.length ? [null, e, null] : I.exec(e)) || !i[1] && t) return !t || t.jquery ? (t || n).find(e) : this.constructor(t).find(e);
        if (i[1]) {
            if (t = t instanceof C ? t[0] : t, C.merge(this, C.parseHTML(i[1], t && t.nodeType ? t.ownerDocument || t : x, !0)), A.test(i[1]) && C.isPlainObject(t))
                for (i in t) y(this[i]) ? this[i](t[i]) : this.attr(i, t[i]);
            return this
        }
        return (r = x.getElementById(i[2])) && (this[0] = r, this.length = 1), this
    }).prototype = C.fn, k = C(x);
    var O = /^(?:parents|prev(?:Until|All))/,
        j = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };

    function L(e, t) {
        for (;
            (e = e[t]) && 1 !== e.nodeType;);
        return e
    }
    C.fn.extend({
        has: function (e) {
            var t = C(e, this),
                n = t.length;
            return this.filter(function () {
                for (var e = 0; e < n; e++)
                    if (C.contains(this, t[e])) return !0
            })
        },
        closest: function (e, t) {
            var n, i = 0,
                r = this.length,
                o = [],
                s = "string" != typeof e && C(e);
            if (!D.test(e))
                for (; i < r; i++)
                    for (n = this[i]; n && n !== t; n = n.parentNode)
                        if (n.nodeType < 11 && (s ? -1 < s.index(n) : 1 === n.nodeType && C.find.matchesSelector(n, e))) {
                            o.push(n);
                            break
                        } return this.pushStack(1 < o.length ? C.uniqueSort(o) : o)
        },
        index: function (e) {
            return e ? "string" == typeof e ? r.call(C(e), this[0]) : r.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        add: function (e, t) {
            return this.pushStack(C.uniqueSort(C.merge(this.get(), C(e, t))))
        },
        addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), C.each({
        parent: function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        },
        parents: function (e) {
            return p(e, "parentNode")
        },
        parentsUntil: function (e, t, n) {
            return p(e, "parentNode", n)
        },
        next: function (e) {
            return L(e, "nextSibling")
        },
        prev: function (e) {
            return L(e, "previousSibling")
        },
        nextAll: function (e) {
            return p(e, "nextSibling")
        },
        prevAll: function (e) {
            return p(e, "previousSibling")
        },
        nextUntil: function (e, t, n) {
            return p(e, "nextSibling", n)
        },
        prevUntil: function (e, t, n) {
            return p(e, "previousSibling", n)
        },
        siblings: function (e) {
            return E((e.parentNode || {}).firstChild, e)
        },
        children: function (e) {
            return E(e.firstChild)
        },
        contents: function (e) {
            return S(e, "iframe") ? e.contentDocument : (S(e, "template") && (e = e.content || e), C.merge([], e.childNodes))
        }
    }, function (i, r) {
        C.fn[i] = function (e, t) {
            var n = C.map(this, r, e);
            return "Until" !== i.slice(-5) && (t = e), t && "string" == typeof t && (n = C.filter(t, n)), 1 < this.length && (j[i] || C.uniqueSort(n), O.test(i) && n.reverse()), this.pushStack(n)
        }
    });
    var P = /[^\x20\t\r\n\f]+/g;

    function H(e) {
        return e
    }

    function R(e) {
        throw e
    }

    function q(e, t, n, i) {
        var r;
        try {
            e && y(r = e.promise) ? r.call(e).done(t).fail(n) : e && y(r = e.then) ? r.call(e, t, n) : t.apply(void 0, [e].slice(i))
        } catch (e) {
            n.apply(void 0, [e])
        }
    }
    C.Callbacks = function (i) {
        var e, n;
        i = "string" == typeof i ? (e = i, n = {}, C.each(e.match(P) || [], function (e, t) {
            n[t] = !0
        }), n) : C.extend({}, i);
        var r, t, o, s, a = [],
            l = [],
            u = -1,
            c = function () {
                for (s = s || i.once, o = r = !0; l.length; u = -1)
                    for (t = l.shift(); ++u < a.length;) !1 === a[u].apply(t[0], t[1]) && i.stopOnFalse && (u = a.length, t = !1);
                i.memory || (t = !1), r = !1, s && (a = t ? [] : "")
            },
            f = {
                add: function () {
                    return a && (t && !r && (u = a.length - 1, l.push(t)), function n(e) {
                        C.each(e, function (e, t) {
                            y(t) ? i.unique && f.has(t) || a.push(t) : t && t.length && "string" !== w(t) && n(t)
                        })
                    }(arguments), t && !r && c()), this
                },
                remove: function () {
                    return C.each(arguments, function (e, t) {
                        for (var n; - 1 < (n = C.inArray(t, a, n));) a.splice(n, 1), n <= u && u--
                    }), this
                },
                has: function (e) {
                    return e ? -1 < C.inArray(e, a) : 0 < a.length
                },
                empty: function () {
                    return a && (a = []), this
                },
                disable: function () {
                    return s = l = [], a = t = "", this
                },
                disabled: function () {
                    return !a
                },
                lock: function () {
                    return s = l = [], t || r || (a = t = ""), this
                },
                locked: function () {
                    return !!s
                },
                fireWith: function (e, t) {
                    return s || (t = [e, (t = t || []).slice ? t.slice() : t], l.push(t), r || c()), this
                },
                fire: function () {
                    return f.fireWith(this, arguments), this
                },
                fired: function () {
                    return !!o
                }
            };
        return f
    }, C.extend({
        Deferred: function (e) {
            var o = [
                    ["notify", "progress", C.Callbacks("memory"), C.Callbacks("memory"), 2],
                    ["resolve", "done", C.Callbacks("once memory"), C.Callbacks("once memory"), 0, "resolved"],
                    ["reject", "fail", C.Callbacks("once memory"), C.Callbacks("once memory"), 1, "rejected"]
                ],
                r = "pending",
                s = {
                    state: function () {
                        return r
                    },
                    always: function () {
                        return a.done(arguments).fail(arguments), this
                    },
                    catch: function (e) {
                        return s.then(null, e)
                    },
                    pipe: function () {
                        var r = arguments;
                        return C.Deferred(function (i) {
                            C.each(o, function (e, t) {
                                var n = y(r[t[4]]) && r[t[4]];
                                a[t[1]](function () {
                                    var e = n && n.apply(this, arguments);
                                    e && y(e.promise) ? e.promise().progress(i.notify).done(i.resolve).fail(i.reject) : i[t[0] + "With"](this, n ? [e] : arguments)
                                })
                            }), r = null
                        }).promise()
                    },
                    then: function (t, n, i) {
                        var l = 0;

                        function u(r, o, s, a) {
                            return function () {
                                var n = this,
                                    i = arguments,
                                    e = function () {
                                        var e, t;
                                        if (!(r < l)) {
                                            if ((e = s.apply(n, i)) === o.promise()) throw new TypeError("Thenable self-resolution");
                                            t = e && ("object" == typeof e || "function" == typeof e) && e.then, y(t) ? a ? t.call(e, u(l, o, H, a), u(l, o, R, a)) : (l++, t.call(e, u(l, o, H, a), u(l, o, R, a), u(l, o, H, o.notifyWith))) : (s !== H && (n = void 0, i = [e]), (a || o.resolveWith)(n, i))
                                        }
                                    },
                                    t = a ? e : function () {
                                        try {
                                            e()
                                        } catch (e) {
                                            C.Deferred.exceptionHook && C.Deferred.exceptionHook(e, t.stackTrace), l <= r + 1 && (s !== R && (n = void 0, i = [e]), o.rejectWith(n, i))
                                        }
                                    };
                                r ? t() : (C.Deferred.getStackHook && (t.stackTrace = C.Deferred.getStackHook()), T.setTimeout(t))
                            }
                        }
                        return C.Deferred(function (e) {
                            o[0][3].add(u(0, e, y(i) ? i : H, e.notifyWith)), o[1][3].add(u(0, e, y(t) ? t : H)), o[2][3].add(u(0, e, y(n) ? n : R))
                        }).promise()
                    },
                    promise: function (e) {
                        return null != e ? C.extend(e, s) : s
                    }
                },
                a = {};
            return C.each(o, function (e, t) {
                var n = t[2],
                    i = t[5];
                s[t[1]] = n.add, i && n.add(function () {
                    r = i
                }, o[3 - e][2].disable, o[3 - e][3].disable, o[0][2].lock, o[0][3].lock), n.add(t[3].fire), a[t[0]] = function () {
                    return a[t[0] + "With"](this === a ? void 0 : this, arguments), this
                }, a[t[0] + "With"] = n.fireWith
            }), s.promise(a), e && e.call(a, a), a
        },
        when: function (e) {
            var n = arguments.length,
                t = n,
                i = Array(t),
                r = a.call(arguments),
                o = C.Deferred(),
                s = function (t) {
                    return function (e) {
                        i[t] = this, r[t] = 1 < arguments.length ? a.call(arguments) : e, --n || o.resolveWith(i, r)
                    }
                };
            if (n <= 1 && (q(e, o.done(s(t)).resolve, o.reject, !n), "pending" === o.state() || y(r[t] && r[t].then))) return o.then();
            for (; t--;) q(r[t], s(t), o.reject);
            return o.promise()
        }
    });
    var W = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
    C.Deferred.exceptionHook = function (e, t) {
        T.console && T.console.warn && e && W.test(e.name) && T.console.warn("jQuery.Deferred exception: " + e.message, e.stack, t)
    }, C.readyException = function (e) {
        T.setTimeout(function () {
            throw e
        })
    };
    var M = C.Deferred();

    function F() {
        x.removeEventListener("DOMContentLoaded", F), T.removeEventListener("load", F), C.ready()
    }
    C.fn.ready = function (e) {
        return M.then(e).catch(function (e) {
            C.readyException(e)
        }), this
    }, C.extend({
        isReady: !1,
        readyWait: 1,
        ready: function (e) {
            (!0 === e ? --C.readyWait : C.isReady) || ((C.isReady = !0) !== e && 0 < --C.readyWait || M.resolveWith(x, [C]))
        }
    }), C.ready.then = M.then, "complete" === x.readyState || "loading" !== x.readyState && !x.documentElement.doScroll ? T.setTimeout(C.ready) : (x.addEventListener("DOMContentLoaded", F), T.addEventListener("load", F));
    var B = function (e, t, n, i, r, o, s) {
            var a = 0,
                l = e.length,
                u = null == n;
            if ("object" === w(n))
                for (a in r = !0, n) B(e, t, a, n[a], !0, o, s);
            else if (void 0 !== i && (r = !0, y(i) || (s = !0), u && (t = s ? (t.call(e, i), null) : (u = t, function (e, t, n) {
                    return u.call(C(e), n)
                })), t))
                for (; a < l; a++) t(e[a], n, s ? i : i.call(e[a], a, t(e[a], n)));
            return r ? e : u ? t.call(e) : l ? t(e[0], n) : o
        },
        U = /^-ms-/,
        K = /-([a-z])/g;

    function $(e, t) {
        return t.toUpperCase()
    }

    function Q(e) {
        return e.replace(U, "ms-").replace(K, $)
    }
    var V = function (e) {
        return 1 === e.nodeType || 9 === e.nodeType || !+e.nodeType
    };

    function Y() {
        this.expando = C.expando + Y.uid++
    }
    Y.uid = 1, Y.prototype = {
        cache: function (e) {
            var t = e[this.expando];
            return t || (t = {}, V(e) && (e.nodeType ? e[this.expando] = t : Object.defineProperty(e, this.expando, {
                value: t,
                configurable: !0
            }))), t
        },
        set: function (e, t, n) {
            var i, r = this.cache(e);
            if ("string" == typeof t) r[Q(t)] = n;
            else
                for (i in t) r[Q(i)] = t[i];
            return r
        },
        get: function (e, t) {
            return void 0 === t ? this.cache(e) : e[this.expando] && e[this.expando][Q(t)]
        },
        access: function (e, t, n) {
            return void 0 === t || t && "string" == typeof t && void 0 === n ? this.get(e, t) : (this.set(e, t, n), void 0 !== n ? n : t)
        },
        remove: function (e, t) {
            var n, i = e[this.expando];
            if (void 0 !== i) {
                if (void 0 !== t) {
                    n = (t = Array.isArray(t) ? t.map(Q) : (t = Q(t)) in i ? [t] : t.match(P) || []).length;
                    for (; n--;) delete i[t[n]]
                }(void 0 === t || C.isEmptyObject(i)) && (e.nodeType ? e[this.expando] = void 0 : delete e[this.expando])
            }
        },
        hasData: function (e) {
            var t = e[this.expando];
            return void 0 !== t && !C.isEmptyObject(t)
        }
    };
    var z = new Y,
        X = new Y,
        G = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
        J = /[A-Z]/g;

    function Z(e, t, n) {
        var i, r;
        if (void 0 === n && 1 === e.nodeType)
            if (i = "data-" + t.replace(J, "-$&").toLowerCase(), "string" == typeof (n = e.getAttribute(i))) {
                try {
                    n = "true" === (r = n) || "false" !== r && ("null" === r ? null : r === +r + "" ? +r : G.test(r) ? JSON.parse(r) : r)
                } catch (e) {}
                X.set(e, t, n)
            } else n = void 0;
        return n
    }
    C.extend({
        hasData: function (e) {
            return X.hasData(e) || z.hasData(e)
        },
        data: function (e, t, n) {
            return X.access(e, t, n)
        },
        removeData: function (e, t) {
            X.remove(e, t)
        },
        _data: function (e, t, n) {
            return z.access(e, t, n)
        },
        _removeData: function (e, t) {
            z.remove(e, t)
        }
    }), C.fn.extend({
        data: function (n, e) {
            var t, i, r, o = this[0],
                s = o && o.attributes;
            if (void 0 !== n) return "object" == typeof n ? this.each(function () {
                X.set(this, n)
            }) : B(this, function (e) {
                var t;
                if (o && void 0 === e) {
                    if (void 0 !== (t = X.get(o, n))) return t;
                    if (void 0 !== (t = Z(o, n))) return t
                } else this.each(function () {
                    X.set(this, n, e)
                })
            }, null, e, 1 < arguments.length, null, !0);
            if (this.length && (r = X.get(o), 1 === o.nodeType && !z.get(o, "hasDataAttrs"))) {
                for (t = s.length; t--;) s[t] && 0 === (i = s[t].name).indexOf("data-") && (i = Q(i.slice(5)), Z(o, i, r[i]));
                z.set(o, "hasDataAttrs", !0)
            }
            return r
        },
        removeData: function (e) {
            return this.each(function () {
                X.remove(this, e)
            })
        }
    }), C.extend({
        queue: function (e, t, n) {
            var i;
            if (e) return t = (t || "fx") + "queue", i = z.get(e, t), n && (!i || Array.isArray(n) ? i = z.access(e, t, C.makeArray(n)) : i.push(n)), i || []
        },
        dequeue: function (e, t) {
            t = t || "fx";
            var n = C.queue(e, t),
                i = n.length,
                r = n.shift(),
                o = C._queueHooks(e, t);
            "inprogress" === r && (r = n.shift(), i--), r && ("fx" === t && n.unshift("inprogress"), delete o.stop, r.call(e, function () {
                C.dequeue(e, t)
            }, o)), !i && o && o.empty.fire()
        },
        _queueHooks: function (e, t) {
            var n = t + "queueHooks";
            return z.get(e, n) || z.access(e, n, {
                empty: C.Callbacks("once memory").add(function () {
                    z.remove(e, [t + "queue", n])
                })
            })
        }
    }), C.fn.extend({
        queue: function (t, n) {
            var e = 2;
            return "string" != typeof t && (n = t, t = "fx", e--), arguments.length < e ? C.queue(this[0], t) : void 0 === n ? this : this.each(function () {
                var e = C.queue(this, t, n);
                C._queueHooks(this, t), "fx" === t && "inprogress" !== e[0] && C.dequeue(this, t)
            })
        },
        dequeue: function (e) {
            return this.each(function () {
                C.dequeue(this, e)
            })
        },
        clearQueue: function (e) {
            return this.queue(e || "fx", [])
        },
        promise: function (e, t) {
            var n, i = 1,
                r = C.Deferred(),
                o = this,
                s = this.length,
                a = function () {
                    --i || r.resolveWith(o, [o])
                };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; s--;)(n = z.get(o[s], e + "queueHooks")) && n.empty && (i++, n.empty.add(a));
            return a(), r.promise(t)
        }
    });
    var ee = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        te = new RegExp("^(?:([+-])=|)(" + ee + ")([a-z%]*)$", "i"),
        ne = ["Top", "Right", "Bottom", "Left"],
        ie = function (e, t) {
            return "none" === (e = t || e).style.display || "" === e.style.display && C.contains(e.ownerDocument, e) && "none" === C.css(e, "display")
        },
        re = function (e, t, n, i) {
            var r, o, s = {};
            for (o in t) s[o] = e.style[o], e.style[o] = t[o];
            for (o in r = n.apply(e, i || []), t) e.style[o] = s[o];
            return r
        };

    function oe(e, t, n, i) {
        var r, o, s = 20,
            a = i ? function () {
                return i.cur()
            } : function () {
                return C.css(e, t, "")
            },
            l = a(),
            u = n && n[3] || (C.cssNumber[t] ? "" : "px"),
            c = (C.cssNumber[t] || "px" !== u && +l) && te.exec(C.css(e, t));
        if (c && c[3] !== u) {
            for (l /= 2, u = u || c[3], c = +l || 1; s--;) C.style(e, t, c + u), (1 - o) * (1 - (o = a() / l || .5)) <= 0 && (s = 0), c /= o;
            c *= 2, C.style(e, t, c + u), n = n || []
        }
        return n && (c = +c || +l || 0, r = n[1] ? c + (n[1] + 1) * n[2] : +n[2], i && (i.unit = u, i.start = c, i.end = r)), r
    }
    var se = {};

    function ae(e, t) {
        for (var n, i, r = [], o = 0, s = e.length; o < s; o++)(i = e[o]).style && (n = i.style.display, t ? ("none" === n && (r[o] = z.get(i, "display") || null, r[o] || (i.style.display = "")), "" === i.style.display && ie(i) && (r[o] = (f = u = l = void 0, u = (a = i).ownerDocument, c = a.nodeName, (f = se[c]) || (l = u.body.appendChild(u.createElement(c)), f = C.css(l, "display"), l.parentNode.removeChild(l), "none" === f && (f = "block"), se[c] = f)))) : "none" !== n && (r[o] = "none", z.set(i, "display", n)));
        var a, l, u, c, f;
        for (o = 0; o < s; o++) null != r[o] && (e[o].style.display = r[o]);
        return e
    }
    C.fn.extend({
        show: function () {
            return ae(this, !0)
        },
        hide: function () {
            return ae(this)
        },
        toggle: function (e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                ie(this) ? C(this).show() : C(this).hide()
            })
        }
    });
    var le = /^(?:checkbox|radio)$/i,
        ue = /<([a-z][^\/\0>\x20\t\r\n\f]+)/i,
        ce = /^$|^module$|\/(?:java|ecma)script/i,
        fe = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            thead: [1, "<table>", "</table>"],
            col: [2, "<table><colgroup>", "</colgroup></table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: [0, "", ""]
        };

    function de(e, t) {
        var n;
        return n = void 0 !== e.getElementsByTagName ? e.getElementsByTagName(t || "*") : void 0 !== e.querySelectorAll ? e.querySelectorAll(t || "*") : [], void 0 === t || t && S(e, t) ? C.merge([e], n) : n
    }

    function he(e, t) {
        for (var n = 0, i = e.length; n < i; n++) z.set(e[n], "globalEval", !t || z.get(t[n], "globalEval"))
    }
    fe.optgroup = fe.option, fe.tbody = fe.tfoot = fe.colgroup = fe.caption = fe.thead, fe.th = fe.td;
    var pe, ge, me = /<|&#?\w+;/;

    function ve(e, t, n, i, r) {
        for (var o, s, a, l, u, c, f = t.createDocumentFragment(), d = [], h = 0, p = e.length; h < p; h++)
            if ((o = e[h]) || 0 === o)
                if ("object" === w(o)) C.merge(d, o.nodeType ? [o] : o);
                else if (me.test(o)) {
            for (s = s || f.appendChild(t.createElement("div")), a = (ue.exec(o) || ["", ""])[1].toLowerCase(), l = fe[a] || fe._default, s.innerHTML = l[1] + C.htmlPrefilter(o) + l[2], c = l[0]; c--;) s = s.lastChild;
            C.merge(d, s.childNodes), (s = f.firstChild).textContent = ""
        } else d.push(t.createTextNode(o));
        for (f.textContent = "", h = 0; o = d[h++];)
            if (i && -1 < C.inArray(o, i)) r && r.push(o);
            else if (u = C.contains(o.ownerDocument, o), s = de(f.appendChild(o), "script"), u && he(s), n)
            for (c = 0; o = s[c++];) ce.test(o.type || "") && n.push(o);
        return f
    }
    pe = x.createDocumentFragment().appendChild(x.createElement("div")), (ge = x.createElement("input")).setAttribute("type", "radio"), ge.setAttribute("checked", "checked"), ge.setAttribute("name", "t"), pe.appendChild(ge), v.checkClone = pe.cloneNode(!0).cloneNode(!0).lastChild.checked, pe.innerHTML = "<textarea>x</textarea>", v.noCloneChecked = !!pe.cloneNode(!0).lastChild.defaultValue;
    var ye = x.documentElement,
        _e = /^key/,
        be = /^(?:mouse|pointer|contextmenu|drag|drop)|click/,
        we = /^([^.]*)(?:\.(.+)|)/;

    function Ee() {
        return !0
    }

    function Te() {
        return !1
    }

    function xe() {
        try {
            return x.activeElement
        } catch (e) {}
    }

    function Ce(e, t, n, i, r, o) {
        var s, a;
        if ("object" == typeof t) {
            for (a in "string" != typeof n && (i = i || n, n = void 0), t) Ce(e, a, n, i, t[a], o);
            return e
        }
        if (null == i && null == r ? (r = n, i = n = void 0) : null == r && ("string" == typeof n ? (r = i, i = void 0) : (r = i, i = n, n = void 0)), !1 === r) r = Te;
        else if (!r) return e;
        return 1 === o && (s = r, (r = function (e) {
            return C().off(e), s.apply(this, arguments)
        }).guid = s.guid || (s.guid = C.guid++)), e.each(function () {
            C.event.add(this, t, r, i, n)
        })
    }
    C.event = {
        global: {},
        add: function (t, e, n, i, r) {
            var o, s, a, l, u, c, f, d, h, p, g, m = z.get(t);
            if (m)
                for (n.handler && (n = (o = n).handler, r = o.selector), r && C.find.matchesSelector(ye, r), n.guid || (n.guid = C.guid++), (l = m.events) || (l = m.events = {}), (s = m.handle) || (s = m.handle = function (e) {
                        return void 0 !== C && C.event.triggered !== e.type ? C.event.dispatch.apply(t, arguments) : void 0
                    }), u = (e = (e || "").match(P) || [""]).length; u--;) h = g = (a = we.exec(e[u]) || [])[1], p = (a[2] || "").split(".").sort(), h && (f = C.event.special[h] || {}, h = (r ? f.delegateType : f.bindType) || h, f = C.event.special[h] || {}, c = C.extend({
                    type: h,
                    origType: g,
                    data: i,
                    handler: n,
                    guid: n.guid,
                    selector: r,
                    needsContext: r && C.expr.match.needsContext.test(r),
                    namespace: p.join(".")
                }, o), (d = l[h]) || ((d = l[h] = []).delegateCount = 0, f.setup && !1 !== f.setup.call(t, i, p, s) || t.addEventListener && t.addEventListener(h, s)), f.add && (f.add.call(t, c), c.handler.guid || (c.handler.guid = n.guid)), r ? d.splice(d.delegateCount++, 0, c) : d.push(c), C.event.global[h] = !0)
        },
        remove: function (e, t, n, i, r) {
            var o, s, a, l, u, c, f, d, h, p, g, m = z.hasData(e) && z.get(e);
            if (m && (l = m.events)) {
                for (u = (t = (t || "").match(P) || [""]).length; u--;)
                    if (h = g = (a = we.exec(t[u]) || [])[1], p = (a[2] || "").split(".").sort(), h) {
                        for (f = C.event.special[h] || {}, d = l[h = (i ? f.delegateType : f.bindType) || h] || [], a = a[2] && new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)"), s = o = d.length; o--;) c = d[o], !r && g !== c.origType || n && n.guid !== c.guid || a && !a.test(c.namespace) || i && i !== c.selector && ("**" !== i || !c.selector) || (d.splice(o, 1), c.selector && d.delegateCount--, f.remove && f.remove.call(e, c));
                        s && !d.length && (f.teardown && !1 !== f.teardown.call(e, p, m.handle) || C.removeEvent(e, h, m.handle), delete l[h])
                    } else
                        for (h in l) C.event.remove(e, h + t[u], n, i, !0);
                C.isEmptyObject(l) && z.remove(e, "handle events")
            }
        },
        dispatch: function (e) {
            var t, n, i, r, o, s, a = C.event.fix(e),
                l = new Array(arguments.length),
                u = (z.get(this, "events") || {})[a.type] || [],
                c = C.event.special[a.type] || {};
            for (l[0] = a, t = 1; t < arguments.length; t++) l[t] = arguments[t];
            if (a.delegateTarget = this, !c.preDispatch || !1 !== c.preDispatch.call(this, a)) {
                for (s = C.event.handlers.call(this, a, u), t = 0;
                    (r = s[t++]) && !a.isPropagationStopped();)
                    for (a.currentTarget = r.elem, n = 0;
                        (o = r.handlers[n++]) && !a.isImmediatePropagationStopped();) a.rnamespace && !a.rnamespace.test(o.namespace) || (a.handleObj = o, a.data = o.data, void 0 !== (i = ((C.event.special[o.origType] || {}).handle || o.handler).apply(r.elem, l)) && !1 === (a.result = i) && (a.preventDefault(), a.stopPropagation()));
                return c.postDispatch && c.postDispatch.call(this, a), a.result
            }
        },
        handlers: function (e, t) {
            var n, i, r, o, s, a = [],
                l = t.delegateCount,
                u = e.target;
            if (l && u.nodeType && !("click" === e.type && 1 <= e.button))
                for (; u !== this; u = u.parentNode || this)
                    if (1 === u.nodeType && ("click" !== e.type || !0 !== u.disabled)) {
                        for (o = [], s = {}, n = 0; n < l; n++) void 0 === s[r = (i = t[n]).selector + " "] && (s[r] = i.needsContext ? -1 < C(r, this).index(u) : C.find(r, this, null, [u]).length), s[r] && o.push(i);
                        o.length && a.push({
                            elem: u,
                            handlers: o
                        })
                    } return u = this, l < t.length && a.push({
                elem: u,
                handlers: t.slice(l)
            }), a
        },
        addProp: function (t, e) {
            Object.defineProperty(C.Event.prototype, t, {
                enumerable: !0,
                configurable: !0,
                get: y(e) ? function () {
                    if (this.originalEvent) return e(this.originalEvent)
                } : function () {
                    if (this.originalEvent) return this.originalEvent[t]
                },
                set: function (e) {
                    Object.defineProperty(this, t, {
                        enumerable: !0,
                        configurable: !0,
                        writable: !0,
                        value: e
                    })
                }
            })
        },
        fix: function (e) {
            return e[C.expando] ? e : new C.Event(e)
        },
        special: {
            load: {
                noBubble: !0
            },
            focus: {
                trigger: function () {
                    if (this !== xe() && this.focus) return this.focus(), !1
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function () {
                    if (this === xe() && this.blur) return this.blur(), !1
                },
                delegateType: "focusout"
            },
            click: {
                trigger: function () {
                    if ("checkbox" === this.type && this.click && S(this, "input")) return this.click(), !1
                },
                _default: function (e) {
                    return S(e.target, "a")
                }
            },
            beforeunload: {
                postDispatch: function (e) {
                    void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                }
            }
        }
    }, C.removeEvent = function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n)
    }, C.Event = function (e, t) {
        if (!(this instanceof C.Event)) return new C.Event(e, t);
        e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && !1 === e.returnValue ? Ee : Te, this.target = e.target && 3 === e.target.nodeType ? e.target.parentNode : e.target, this.currentTarget = e.currentTarget, this.relatedTarget = e.relatedTarget) : this.type = e, t && C.extend(this, t), this.timeStamp = e && e.timeStamp || Date.now(), this[C.expando] = !0
    }, C.Event.prototype = {
        constructor: C.Event,
        isDefaultPrevented: Te,
        isPropagationStopped: Te,
        isImmediatePropagationStopped: Te,
        isSimulated: !1,
        preventDefault: function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = Ee, e && !this.isSimulated && e.preventDefault()
        },
        stopPropagation: function () {
            var e = this.originalEvent;
            this.isPropagationStopped = Ee, e && !this.isSimulated && e.stopPropagation()
        },
        stopImmediatePropagation: function () {
            var e = this.originalEvent;
            this.isImmediatePropagationStopped = Ee, e && !this.isSimulated && e.stopImmediatePropagation(), this.stopPropagation()
        }
    }, C.each({
        altKey: !0,
        bubbles: !0,
        cancelable: !0,
        changedTouches: !0,
        ctrlKey: !0,
        detail: !0,
        eventPhase: !0,
        metaKey: !0,
        pageX: !0,
        pageY: !0,
        shiftKey: !0,
        view: !0,
        char: !0,
        charCode: !0,
        key: !0,
        keyCode: !0,
        button: !0,
        buttons: !0,
        clientX: !0,
        clientY: !0,
        offsetX: !0,
        offsetY: !0,
        pointerId: !0,
        pointerType: !0,
        screenX: !0,
        screenY: !0,
        targetTouches: !0,
        toElement: !0,
        touches: !0,
        which: function (e) {
            var t = e.button;
            return null == e.which && _e.test(e.type) ? null != e.charCode ? e.charCode : e.keyCode : !e.which && void 0 !== t && be.test(e.type) ? 1 & t ? 1 : 2 & t ? 3 : 4 & t ? 2 : 0 : e.which
        }
    }, C.event.addProp), C.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout",
        pointerenter: "pointerover",
        pointerleave: "pointerout"
    }, function (e, r) {
        C.event.special[e] = {
            delegateType: r,
            bindType: r,
            handle: function (e) {
                var t, n = e.relatedTarget,
                    i = e.handleObj;
                return n && (n === this || C.contains(this, n)) || (e.type = i.origType, t = i.handler.apply(this, arguments), e.type = r), t
            }
        }
    }), C.fn.extend({
        on: function (e, t, n, i) {
            return Ce(this, e, t, n, i)
        },
        one: function (e, t, n, i) {
            return Ce(this, e, t, n, i, 1)
        },
        off: function (e, t, n) {
            var i, r;
            if (e && e.preventDefault && e.handleObj) return i = e.handleObj, C(e.delegateTarget).off(i.namespace ? i.origType + "." + i.namespace : i.origType, i.selector, i.handler), this;
            if ("object" != typeof e) return !1 !== t && "function" != typeof t || (n = t, t = void 0), !1 === n && (n = Te), this.each(function () {
                C.event.remove(this, e, n, t)
            });
            for (r in e) this.off(r, t, e[r]);
            return this
        }
    });
    var De = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([a-z][^\/\0>\x20\t\r\n\f]*)[^>]*)\/>/gi,
        Se = /<script|<style|<link/i,
        Ae = /checked\s*(?:[^=]|=\s*.checked.)/i,
        Ne = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;

    function ke(e, t) {
        return S(e, "table") && S(11 !== t.nodeType ? t : t.firstChild, "tr") && C(e).children("tbody")[0] || e
    }

    function Ie(e) {
        return e.type = (null !== e.getAttribute("type")) + "/" + e.type, e
    }

    function Oe(e) {
        return "true/" === (e.type || "").slice(0, 5) ? e.type = e.type.slice(5) : e.removeAttribute("type"), e
    }

    function je(e, t) {
        var n, i, r, o, s, a, l, u;
        if (1 === t.nodeType) {
            if (z.hasData(e) && (o = z.access(e), s = z.set(t, o), u = o.events))
                for (r in delete s.handle, s.events = {}, u)
                    for (n = 0, i = u[r].length; n < i; n++) C.event.add(t, r, u[r][n]);
            X.hasData(e) && (a = X.access(e), l = C.extend({}, a), X.set(t, l))
        }
    }

    function Le(n, i, r, o) {
        i = g.apply([], i);
        var e, t, s, a, l, u, c = 0,
            f = n.length,
            d = f - 1,
            h = i[0],
            p = y(h);
        if (p || 1 < f && "string" == typeof h && !v.checkClone && Ae.test(h)) return n.each(function (e) {
            var t = n.eq(e);
            p && (i[0] = h.call(this, e, t.html())), Le(t, i, r, o)
        });
        if (f && (t = (e = ve(i, n[0].ownerDocument, !1, n, o)).firstChild, 1 === e.childNodes.length && (e = t), t || o)) {
            for (a = (s = C.map(de(e, "script"), Ie)).length; c < f; c++) l = e, c !== d && (l = C.clone(l, !0, !0), a && C.merge(s, de(l, "script"))), r.call(n[c], l, c);
            if (a)
                for (u = s[s.length - 1].ownerDocument, C.map(s, Oe), c = 0; c < a; c++) l = s[c], ce.test(l.type || "") && !z.access(l, "globalEval") && C.contains(u, l) && (l.src && "module" !== (l.type || "").toLowerCase() ? C._evalUrl && C._evalUrl(l.src) : b(l.textContent.replace(Ne, ""), u, l))
        }
        return n
    }

    function Pe(e, t, n) {
        for (var i, r = t ? C.filter(t, e) : e, o = 0; null != (i = r[o]); o++) n || 1 !== i.nodeType || C.cleanData(de(i)), i.parentNode && (n && C.contains(i.ownerDocument, i) && he(de(i, "script")), i.parentNode.removeChild(i));
        return e
    }
    C.extend({
        htmlPrefilter: function (e) {
            return e.replace(De, "<$1></$2>")
        },
        clone: function (e, t, n) {
            var i, r, o, s, a, l, u, c = e.cloneNode(!0),
                f = C.contains(e.ownerDocument, e);
            if (!(v.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || C.isXMLDoc(e)))
                for (s = de(c), i = 0, r = (o = de(e)).length; i < r; i++) a = o[i], l = s[i], void 0, "input" === (u = l.nodeName.toLowerCase()) && le.test(a.type) ? l.checked = a.checked : "input" !== u && "textarea" !== u || (l.defaultValue = a.defaultValue);
            if (t)
                if (n)
                    for (o = o || de(e), s = s || de(c), i = 0, r = o.length; i < r; i++) je(o[i], s[i]);
                else je(e, c);
            return 0 < (s = de(c, "script")).length && he(s, !f && de(e, "script")), c
        },
        cleanData: function (e) {
            for (var t, n, i, r = C.event.special, o = 0; void 0 !== (n = e[o]); o++)
                if (V(n)) {
                    if (t = n[z.expando]) {
                        if (t.events)
                            for (i in t.events) r[i] ? C.event.remove(n, i) : C.removeEvent(n, i, t.handle);
                        n[z.expando] = void 0
                    }
                    n[X.expando] && (n[X.expando] = void 0)
                }
        }
    }), C.fn.extend({
        detach: function (e) {
            return Pe(this, e, !0)
        },
        remove: function (e) {
            return Pe(this, e)
        },
        text: function (e) {
            return B(this, function (e) {
                return void 0 === e ? C.text(this) : this.empty().each(function () {
                    1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = e)
                })
            }, null, e, arguments.length)
        },
        append: function () {
            return Le(this, arguments, function (e) {
                1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || ke(this, e).appendChild(e)
            })
        },
        prepend: function () {
            return Le(this, arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = ke(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        },
        before: function () {
            return Le(this, arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        },
        after: function () {
            return Le(this, arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        },
        empty: function () {
            for (var e, t = 0; null != (e = this[t]); t++) 1 === e.nodeType && (C.cleanData(de(e, !1)), e.textContent = "");
            return this
        },
        clone: function (e, t) {
            return e = null != e && e, t = null == t ? e : t, this.map(function () {
                return C.clone(this, e, t)
            })
        },
        html: function (e) {
            return B(this, function (e) {
                var t = this[0] || {},
                    n = 0,
                    i = this.length;
                if (void 0 === e && 1 === t.nodeType) return t.innerHTML;
                if ("string" == typeof e && !Se.test(e) && !fe[(ue.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = C.htmlPrefilter(e);
                    try {
                        for (; n < i; n++) 1 === (t = this[n] || {}).nodeType && (C.cleanData(de(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (e) {}
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        },
        replaceWith: function () {
            var n = [];
            return Le(this, arguments, function (e) {
                var t = this.parentNode;
                C.inArray(this, n) < 0 && (C.cleanData(de(this)), t && t.replaceChild(e, this))
            }, n)
        }
    }), C.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (e, s) {
        C.fn[e] = function (e) {
            for (var t, n = [], i = C(e), r = i.length - 1, o = 0; o <= r; o++) t = o === r ? this : this.clone(!0), C(i[o])[s](t), l.apply(n, t.get());
            return this.pushStack(n)
        }
    });
    var He = new RegExp("^(" + ee + ")(?!px)[a-z%]+$", "i"),
        Re = function (e) {
            var t = e.ownerDocument.defaultView;
            return t && t.opener || (t = T), t.getComputedStyle(e)
        },
        qe = new RegExp(ne.join("|"), "i");

    function We(e, t, n) {
        var i, r, o, s, a = e.style;
        return (n = n || Re(e)) && ("" !== (s = n.getPropertyValue(t) || n[t]) || C.contains(e.ownerDocument, e) || (s = C.style(e, t)), !v.pixelBoxStyles() && He.test(s) && qe.test(t) && (i = a.width, r = a.minWidth, o = a.maxWidth, a.minWidth = a.maxWidth = a.width = s, s = n.width, a.width = i, a.minWidth = r, a.maxWidth = o)), void 0 !== s ? s + "" : s
    }

    function Me(e, t) {
        return {
            get: function () {
                if (!e()) return (this.get = t).apply(this, arguments);
                delete this.get
            }
        }
    }! function () {
        function e() {
            if (l) {
                a.style.cssText = "position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0", l.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%", ye.appendChild(a).appendChild(l);
                var e = T.getComputedStyle(l);
                n = "1%" !== e.top, s = 12 === t(e.marginLeft), l.style.right = "60%", o = 36 === t(e.right), i = 36 === t(e.width), l.style.position = "absolute", r = 36 === l.offsetWidth || "absolute", ye.removeChild(a), l = null
            }
        }

        function t(e) {
            return Math.round(parseFloat(e))
        }
        var n, i, r, o, s, a = x.createElement("div"),
            l = x.createElement("div");
        l.style && (l.style.backgroundClip = "content-box", l.cloneNode(!0).style.backgroundClip = "", v.clearCloneStyle = "content-box" === l.style.backgroundClip, C.extend(v, {
            boxSizingReliable: function () {
                return e(), i
            },
            pixelBoxStyles: function () {
                return e(), o
            },
            pixelPosition: function () {
                return e(), n
            },
            reliableMarginLeft: function () {
                return e(), s
            },
            scrollboxSize: function () {
                return e(), r
            }
        }))
    }();
    var Fe = /^(none|table(?!-c[ea]).+)/,
        Be = /^--/,
        Ue = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        Ke = {
            letterSpacing: "0",
            fontWeight: "400"
        },
        $e = ["Webkit", "Moz", "ms"],
        Qe = x.createElement("div").style;

    function Ve(e) {
        var t = C.cssProps[e];
        return t || (t = C.cssProps[e] = function (e) {
            if (e in Qe) return e;
            for (var t = e[0].toUpperCase() + e.slice(1), n = $e.length; n--;)
                if ((e = $e[n] + t) in Qe) return e
        }(e) || e), t
    }

    function Ye(e, t, n) {
        var i = te.exec(t);
        return i ? Math.max(0, i[2] - (n || 0)) + (i[3] || "px") : t
    }

    function ze(e, t, n, i, r, o) {
        var s = "width" === t ? 1 : 0,
            a = 0,
            l = 0;
        if (n === (i ? "border" : "content")) return 0;
        for (; s < 4; s += 2) "margin" === n && (l += C.css(e, n + ne[s], !0, r)), i ? ("content" === n && (l -= C.css(e, "padding" + ne[s], !0, r)), "margin" !== n && (l -= C.css(e, "border" + ne[s] + "Width", !0, r))) : (l += C.css(e, "padding" + ne[s], !0, r), "padding" !== n ? l += C.css(e, "border" + ne[s] + "Width", !0, r) : a += C.css(e, "border" + ne[s] + "Width", !0, r));
        return !i && 0 <= o && (l += Math.max(0, Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - o - l - a - .5))), l
    }

    function Xe(e, t, n) {
        var i = Re(e),
            r = We(e, t, i),
            o = "border-box" === C.css(e, "boxSizing", !1, i),
            s = o;
        if (He.test(r)) {
            if (!n) return r;
            r = "auto"
        }
        return s = s && (v.boxSizingReliable() || r === e.style[t]), ("auto" === r || !parseFloat(r) && "inline" === C.css(e, "display", !1, i)) && (r = e["offset" + t[0].toUpperCase() + t.slice(1)], s = !0), (r = parseFloat(r) || 0) + ze(e, t, n || (o ? "border" : "content"), s, i, r) + "px"
    }

    function Ge(e, t, n, i, r) {
        return new Ge.prototype.init(e, t, n, i, r)
    }
    C.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = We(e, "opacity");
                        return "" === n ? "1" : n
                    }
                }
            }
        },
        cssNumber: {
            animationIterationCount: !0,
            columnCount: !0,
            fillOpacity: !0,
            flexGrow: !0,
            flexShrink: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {},
        style: function (e, t, n, i) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var r, o, s, a = Q(t),
                    l = Be.test(t),
                    u = e.style;
                if (l || (t = Ve(a)), s = C.cssHooks[t] || C.cssHooks[a], void 0 === n) return s && "get" in s && void 0 !== (r = s.get(e, !1, i)) ? r : u[t];
                "string" == (o = typeof n) && (r = te.exec(n)) && r[1] && (n = oe(e, t, r), o = "number"), null != n && n == n && ("number" === o && (n += r && r[3] || (C.cssNumber[a] ? "" : "px")), v.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (u[t] = "inherit"), s && "set" in s && void 0 === (n = s.set(e, n, i)) || (l ? u.setProperty(t, n) : u[t] = n))
            }
        },
        css: function (e, t, n, i) {
            var r, o, s, a = Q(t);
            return Be.test(t) || (t = Ve(a)), (s = C.cssHooks[t] || C.cssHooks[a]) && "get" in s && (r = s.get(e, !0, n)), void 0 === r && (r = We(e, t, i)), "normal" === r && t in Ke && (r = Ke[t]), "" === n || n ? (o = parseFloat(r), !0 === n || isFinite(o) ? o || 0 : r) : r
        }
    }), C.each(["height", "width"], function (e, a) {
        C.cssHooks[a] = {
            get: function (e, t, n) {
                if (t) return !Fe.test(C.css(e, "display")) || e.getClientRects().length && e.getBoundingClientRect().width ? Xe(e, a, n) : re(e, Ue, function () {
                    return Xe(e, a, n)
                })
            },
            set: function (e, t, n) {
                var i, r = Re(e),
                    o = "border-box" === C.css(e, "boxSizing", !1, r),
                    s = n && ze(e, a, n, o, r);
                return o && v.scrollboxSize() === r.position && (s -= Math.ceil(e["offset" + a[0].toUpperCase() + a.slice(1)] - parseFloat(r[a]) - ze(e, a, "border", !1, r) - .5)), s && (i = te.exec(t)) && "px" !== (i[3] || "px") && (e.style[a] = t, t = C.css(e, a)), Ye(0, t, s)
            }
        }
    }), C.cssHooks.marginLeft = Me(v.reliableMarginLeft, function (e, t) {
        if (t) return (parseFloat(We(e, "marginLeft")) || e.getBoundingClientRect().left - re(e, {
            marginLeft: 0
        }, function () {
            return e.getBoundingClientRect().left
        })) + "px"
    }), C.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function (r, o) {
        C.cssHooks[r + o] = {
            expand: function (e) {
                for (var t = 0, n = {}, i = "string" == typeof e ? e.split(" ") : [e]; t < 4; t++) n[r + ne[t] + o] = i[t] || i[t - 2] || i[0];
                return n
            }
        }, "margin" !== r && (C.cssHooks[r + o].set = Ye)
    }), C.fn.extend({
        css: function (e, t) {
            return B(this, function (e, t, n) {
                var i, r, o = {},
                    s = 0;
                if (Array.isArray(t)) {
                    for (i = Re(e), r = t.length; s < r; s++) o[t[s]] = C.css(e, t[s], !1, i);
                    return o
                }
                return void 0 !== n ? C.style(e, t, n) : C.css(e, t)
            }, e, t, 1 < arguments.length)
        }
    }), ((C.Tween = Ge).prototype = {
        constructor: Ge,
        init: function (e, t, n, i, r, o) {
            this.elem = e, this.prop = n, this.easing = r || C.easing._default, this.options = t, this.start = this.now = this.cur(), this.end = i, this.unit = o || (C.cssNumber[n] ? "" : "px")
        },
        cur: function () {
            var e = Ge.propHooks[this.prop];
            return e && e.get ? e.get(this) : Ge.propHooks._default.get(this)
        },
        run: function (e) {
            var t, n = Ge.propHooks[this.prop];
            return this.options.duration ? this.pos = t = C.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : Ge.propHooks._default.set(this), this
        }
    }).init.prototype = Ge.prototype, (Ge.propHooks = {
        _default: {
            get: function (e) {
                var t;
                return 1 !== e.elem.nodeType || null != e.elem[e.prop] && null == e.elem.style[e.prop] ? e.elem[e.prop] : (t = C.css(e.elem, e.prop, "")) && "auto" !== t ? t : 0
            },
            set: function (e) {
                C.fx.step[e.prop] ? C.fx.step[e.prop](e) : 1 !== e.elem.nodeType || null == e.elem.style[C.cssProps[e.prop]] && !C.cssHooks[e.prop] ? e.elem[e.prop] = e.now : C.style(e.elem, e.prop, e.now + e.unit)
            }
        }
    }).scrollTop = Ge.propHooks.scrollLeft = {
        set: function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, C.easing = {
        linear: function (e) {
            return e
        },
        swing: function (e) {
            return .5 - Math.cos(e * Math.PI) / 2
        },
        _default: "swing"
    }, C.fx = Ge.prototype.init, C.fx.step = {};
    var Je, Ze, et, tt, nt = /^(?:toggle|show|hide)$/,
        it = /queueHooks$/;

    function rt() {
        Ze && (!1 === x.hidden && T.requestAnimationFrame ? T.requestAnimationFrame(rt) : T.setTimeout(rt, C.fx.interval), C.fx.tick())
    }

    function ot() {
        return T.setTimeout(function () {
            Je = void 0
        }), Je = Date.now()
    }

    function st(e, t) {
        var n, i = 0,
            r = {
                height: e
            };
        for (t = t ? 1 : 0; i < 4; i += 2 - t) r["margin" + (n = ne[i])] = r["padding" + n] = e;
        return t && (r.opacity = r.width = e), r
    }

    function at(e, t, n) {
        for (var i, r = (lt.tweeners[t] || []).concat(lt.tweeners["*"]), o = 0, s = r.length; o < s; o++)
            if (i = r[o].call(n, t, e)) return i
    }

    function lt(o, e, t) {
        var n, s, i = 0,
            r = lt.prefilters.length,
            a = C.Deferred().always(function () {
                delete l.elem
            }),
            l = function () {
                if (s) return !1;
                for (var e = Je || ot(), t = Math.max(0, u.startTime + u.duration - e), n = 1 - (t / u.duration || 0), i = 0, r = u.tweens.length; i < r; i++) u.tweens[i].run(n);
                return a.notifyWith(o, [u, n, t]), n < 1 && r ? t : (r || a.notifyWith(o, [u, 1, 0]), a.resolveWith(o, [u]), !1)
            },
            u = a.promise({
                elem: o,
                props: C.extend({}, e),
                opts: C.extend(!0, {
                    specialEasing: {},
                    easing: C.easing._default
                }, t),
                originalProperties: e,
                originalOptions: t,
                startTime: Je || ot(),
                duration: t.duration,
                tweens: [],
                createTween: function (e, t) {
                    var n = C.Tween(o, u.opts, e, t, u.opts.specialEasing[e] || u.opts.easing);
                    return u.tweens.push(n), n
                },
                stop: function (e) {
                    var t = 0,
                        n = e ? u.tweens.length : 0;
                    if (s) return this;
                    for (s = !0; t < n; t++) u.tweens[t].run(1);
                    return e ? (a.notifyWith(o, [u, 1, 0]), a.resolveWith(o, [u, e])) : a.rejectWith(o, [u, e]), this
                }
            }),
            c = u.props;
        for (function (e, t) {
                var n, i, r, o, s;
                for (n in e)
                    if (r = t[i = Q(n)], o = e[n], Array.isArray(o) && (r = o[1], o = e[n] = o[0]), n !== i && (e[i] = o, delete e[n]), (s = C.cssHooks[i]) && "expand" in s)
                        for (n in o = s.expand(o), delete e[i], o) n in e || (e[n] = o[n], t[n] = r);
                    else t[i] = r
            }(c, u.opts.specialEasing); i < r; i++)
            if (n = lt.prefilters[i].call(u, o, c, u.opts)) return y(n.stop) && (C._queueHooks(u.elem, u.opts.queue).stop = n.stop.bind(n)), n;
        return C.map(c, at, u), y(u.opts.start) && u.opts.start.call(o, u), u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always), C.fx.timer(C.extend(l, {
            elem: o,
            anim: u,
            queue: u.opts.queue
        })), u
    }
    C.Animation = C.extend(lt, {
        tweeners: {
            "*": [function (e, t) {
                var n = this.createTween(e, t);
                return oe(n.elem, e, te.exec(t), n), n
            }]
        },
        tweener: function (e, t) {
            for (var n, i = 0, r = (e = y(e) ? (t = e, ["*"]) : e.match(P)).length; i < r; i++) n = e[i], lt.tweeners[n] = lt.tweeners[n] || [], lt.tweeners[n].unshift(t)
        },
        prefilters: [function (e, t, n) {
            var i, r, o, s, a, l, u, c, f = "width" in t || "height" in t,
                d = this,
                h = {},
                p = e.style,
                g = e.nodeType && ie(e),
                m = z.get(e, "fxshow");
            for (i in n.queue || (null == (s = C._queueHooks(e, "fx")).unqueued && (s.unqueued = 0, a = s.empty.fire, s.empty.fire = function () {
                    s.unqueued || a()
                }), s.unqueued++, d.always(function () {
                    d.always(function () {
                        s.unqueued--, C.queue(e, "fx").length || s.empty.fire()
                    })
                })), t)
                if (r = t[i], nt.test(r)) {
                    if (delete t[i], o = o || "toggle" === r, r === (g ? "hide" : "show")) {
                        if ("show" !== r || !m || void 0 === m[i]) continue;
                        g = !0
                    }
                    h[i] = m && m[i] || C.style(e, i)
                } if ((l = !C.isEmptyObject(t)) || !C.isEmptyObject(h))
                for (i in f && 1 === e.nodeType && (n.overflow = [p.overflow, p.overflowX, p.overflowY], null == (u = m && m.display) && (u = z.get(e, "display")), "none" === (c = C.css(e, "display")) && (u ? c = u : (ae([e], !0), u = e.style.display || u, c = C.css(e, "display"), ae([e]))), ("inline" === c || "inline-block" === c && null != u) && "none" === C.css(e, "float") && (l || (d.done(function () {
                        p.display = u
                    }), null == u && (c = p.display, u = "none" === c ? "" : c)), p.display = "inline-block")), n.overflow && (p.overflow = "hidden", d.always(function () {
                        p.overflow = n.overflow[0], p.overflowX = n.overflow[1], p.overflowY = n.overflow[2]
                    })), l = !1, h) l || (m ? "hidden" in m && (g = m.hidden) : m = z.access(e, "fxshow", {
                    display: u
                }), o && (m.hidden = !g), g && ae([e], !0), d.done(function () {
                    for (i in g || ae([e]), z.remove(e, "fxshow"), h) C.style(e, i, h[i])
                })), l = at(g ? m[i] : 0, i, d), i in m || (m[i] = l.start, g && (l.end = l.start, l.start = 0))
        }],
        prefilter: function (e, t) {
            t ? lt.prefilters.unshift(e) : lt.prefilters.push(e)
        }
    }), C.speed = function (e, t, n) {
        var i = e && "object" == typeof e ? C.extend({}, e) : {
            complete: n || !n && t || y(e) && e,
            duration: e,
            easing: n && t || t && !y(t) && t
        };
        return C.fx.off ? i.duration = 0 : "number" != typeof i.duration && (i.duration in C.fx.speeds ? i.duration = C.fx.speeds[i.duration] : i.duration = C.fx.speeds._default), null != i.queue && !0 !== i.queue || (i.queue = "fx"), i.old = i.complete, i.complete = function () {
            y(i.old) && i.old.call(this), i.queue && C.dequeue(this, i.queue)
        }, i
    }, C.fn.extend({
        fadeTo: function (e, t, n, i) {
            return this.filter(ie).css("opacity", 0).show().end().animate({
                opacity: t
            }, e, n, i)
        },
        animate: function (t, e, n, i) {
            var r = C.isEmptyObject(t),
                o = C.speed(e, n, i),
                s = function () {
                    var e = lt(this, C.extend({}, t), o);
                    (r || z.get(this, "finish")) && e.stop(!0)
                };
            return s.finish = s, r || !1 === o.queue ? this.each(s) : this.queue(o.queue, s)
        },
        stop: function (r, e, o) {
            var s = function (e) {
                var t = e.stop;
                delete e.stop, t(o)
            };
            return "string" != typeof r && (o = e, e = r, r = void 0), e && !1 !== r && this.queue(r || "fx", []), this.each(function () {
                var e = !0,
                    t = null != r && r + "queueHooks",
                    n = C.timers,
                    i = z.get(this);
                if (t) i[t] && i[t].stop && s(i[t]);
                else
                    for (t in i) i[t] && i[t].stop && it.test(t) && s(i[t]);
                for (t = n.length; t--;) n[t].elem !== this || null != r && n[t].queue !== r || (n[t].anim.stop(o), e = !1, n.splice(t, 1));
                !e && o || C.dequeue(this, r)
            })
        },
        finish: function (s) {
            return !1 !== s && (s = s || "fx"), this.each(function () {
                var e, t = z.get(this),
                    n = t[s + "queue"],
                    i = t[s + "queueHooks"],
                    r = C.timers,
                    o = n ? n.length : 0;
                for (t.finish = !0, C.queue(this, s, []), i && i.stop && i.stop.call(this, !0), e = r.length; e--;) r[e].elem === this && r[e].queue === s && (r[e].anim.stop(!0), r.splice(e, 1));
                for (e = 0; e < o; e++) n[e] && n[e].finish && n[e].finish.call(this);
                delete t.finish
            })
        }
    }), C.each(["toggle", "show", "hide"], function (e, i) {
        var r = C.fn[i];
        C.fn[i] = function (e, t, n) {
            return null == e || "boolean" == typeof e ? r.apply(this, arguments) : this.animate(st(i, !0), e, t, n)
        }
    }), C.each({
        slideDown: st("show"),
        slideUp: st("hide"),
        slideToggle: st("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function (e, i) {
        C.fn[e] = function (e, t, n) {
            return this.animate(i, e, t, n)
        }
    }), C.timers = [], C.fx.tick = function () {
        var e, t = 0,
            n = C.timers;
        for (Je = Date.now(); t < n.length; t++)(e = n[t])() || n[t] !== e || n.splice(t--, 1);
        n.length || C.fx.stop(), Je = void 0
    }, C.fx.timer = function (e) {
        C.timers.push(e), C.fx.start()
    }, C.fx.interval = 13, C.fx.start = function () {
        Ze || (Ze = !0, rt())
    }, C.fx.stop = function () {
        Ze = null
    }, C.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    }, C.fn.delay = function (i, e) {
        return i = C.fx && C.fx.speeds[i] || i, e = e || "fx", this.queue(e, function (e, t) {
            var n = T.setTimeout(e, i);
            t.stop = function () {
                T.clearTimeout(n)
            }
        })
    }, et = x.createElement("input"), tt = x.createElement("select").appendChild(x.createElement("option")), et.type = "checkbox", v.checkOn = "" !== et.value, v.optSelected = tt.selected, (et = x.createElement("input")).value = "t", et.type = "radio", v.radioValue = "t" === et.value;
    var ut, ct = C.expr.attrHandle;
    C.fn.extend({
        attr: function (e, t) {
            return B(this, C.attr, e, t, 1 < arguments.length)
        },
        removeAttr: function (e) {
            return this.each(function () {
                C.removeAttr(this, e)
            })
        }
    }), C.extend({
        attr: function (e, t, n) {
            var i, r, o = e.nodeType;
            if (3 !== o && 8 !== o && 2 !== o) return void 0 === e.getAttribute ? C.prop(e, t, n) : (1 === o && C.isXMLDoc(e) || (r = C.attrHooks[t.toLowerCase()] || (C.expr.match.bool.test(t) ? ut : void 0)), void 0 !== n ? null === n ? void C.removeAttr(e, t) : r && "set" in r && void 0 !== (i = r.set(e, n, t)) ? i : (e.setAttribute(t, n + ""), n) : r && "get" in r && null !== (i = r.get(e, t)) ? i : null == (i = C.find.attr(e, t)) ? void 0 : i)
        },
        attrHooks: {
            type: {
                set: function (e, t) {
                    if (!v.radioValue && "radio" === t && S(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        },
        removeAttr: function (e, t) {
            var n, i = 0,
                r = t && t.match(P);
            if (r && 1 === e.nodeType)
                for (; n = r[i++];) e.removeAttribute(n)
        }
    }), ut = {
        set: function (e, t, n) {
            return !1 === t ? C.removeAttr(e, n) : e.setAttribute(n, n), n
        }
    }, C.each(C.expr.match.bool.source.match(/\w+/g), function (e, t) {
        var s = ct[t] || C.find.attr;
        ct[t] = function (e, t, n) {
            var i, r, o = t.toLowerCase();
            return n || (r = ct[o], ct[o] = i, i = null != s(e, t, n) ? o : null, ct[o] = r), i
        }
    });
    var ft = /^(?:input|select|textarea|button)$/i,
        dt = /^(?:a|area)$/i;

    function ht(e) {
        return (e.match(P) || []).join(" ")
    }

    function pt(e) {
        return e.getAttribute && e.getAttribute("class") || ""
    }

    function gt(e) {
        return Array.isArray(e) ? e : "string" == typeof e && e.match(P) || []
    }
    C.fn.extend({
        prop: function (e, t) {
            return B(this, C.prop, e, t, 1 < arguments.length)
        },
        removeProp: function (e) {
            return this.each(function () {
                delete this[C.propFix[e] || e]
            })
        }
    }), C.extend({
        prop: function (e, t, n) {
            var i, r, o = e.nodeType;
            if (3 !== o && 8 !== o && 2 !== o) return 1 === o && C.isXMLDoc(e) || (t = C.propFix[t] || t, r = C.propHooks[t]), void 0 !== n ? r && "set" in r && void 0 !== (i = r.set(e, n, t)) ? i : e[t] = n : r && "get" in r && null !== (i = r.get(e, t)) ? i : e[t]
        },
        propHooks: {
            tabIndex: {
                get: function (e) {
                    var t = C.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : ft.test(e.nodeName) || dt.test(e.nodeName) && e.href ? 0 : -1
                }
            }
        },
        propFix: {
            for: "htmlFor",
            class: "className"
        }
    }), v.optSelected || (C.propHooks.selected = {
        get: function (e) {
            var t = e.parentNode;
            return t && t.parentNode && t.parentNode.selectedIndex, null
        },
        set: function (e) {
            var t = e.parentNode;
            t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex)
        }
    }), C.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        C.propFix[this.toLowerCase()] = this
    }), C.fn.extend({
        addClass: function (t) {
            var e, n, i, r, o, s, a, l = 0;
            if (y(t)) return this.each(function (e) {
                C(this).addClass(t.call(this, e, pt(this)))
            });
            if ((e = gt(t)).length)
                for (; n = this[l++];)
                    if (r = pt(n), i = 1 === n.nodeType && " " + ht(r) + " ") {
                        for (s = 0; o = e[s++];) i.indexOf(" " + o + " ") < 0 && (i += o + " ");
                        r !== (a = ht(i)) && n.setAttribute("class", a)
                    } return this
        },
        removeClass: function (t) {
            var e, n, i, r, o, s, a, l = 0;
            if (y(t)) return this.each(function (e) {
                C(this).removeClass(t.call(this, e, pt(this)))
            });
            if (!arguments.length) return this.attr("class", "");
            if ((e = gt(t)).length)
                for (; n = this[l++];)
                    if (r = pt(n), i = 1 === n.nodeType && " " + ht(r) + " ") {
                        for (s = 0; o = e[s++];)
                            for (; - 1 < i.indexOf(" " + o + " ");) i = i.replace(" " + o + " ", " ");
                        r !== (a = ht(i)) && n.setAttribute("class", a)
                    } return this
        },
        toggleClass: function (r, t) {
            var o = typeof r,
                s = "string" === o || Array.isArray(r);
            return "boolean" == typeof t && s ? t ? this.addClass(r) : this.removeClass(r) : y(r) ? this.each(function (e) {
                C(this).toggleClass(r.call(this, e, pt(this), t), t)
            }) : this.each(function () {
                var e, t, n, i;
                if (s)
                    for (t = 0, n = C(this), i = gt(r); e = i[t++];) n.hasClass(e) ? n.removeClass(e) : n.addClass(e);
                else void 0 !== r && "boolean" !== o || ((e = pt(this)) && z.set(this, "__className__", e), this.setAttribute && this.setAttribute("class", e || !1 === r ? "" : z.get(this, "__className__") || ""))
            })
        },
        hasClass: function (e) {
            var t, n, i = 0;
            for (t = " " + e + " "; n = this[i++];)
                if (1 === n.nodeType && -1 < (" " + ht(pt(n)) + " ").indexOf(t)) return !0;
            return !1
        }
    });
    var mt = /\r/g;
    C.fn.extend({
        val: function (n) {
            var i, e, r, t = this[0];
            return arguments.length ? (r = y(n), this.each(function (e) {
                var t;
                1 === this.nodeType && (null == (t = r ? n.call(this, e, C(this).val()) : n) ? t = "" : "number" == typeof t ? t += "" : Array.isArray(t) && (t = C.map(t, function (e) {
                    return null == e ? "" : e + ""
                })), (i = C.valHooks[this.type] || C.valHooks[this.nodeName.toLowerCase()]) && "set" in i && void 0 !== i.set(this, t, "value") || (this.value = t))
            })) : t ? (i = C.valHooks[t.type] || C.valHooks[t.nodeName.toLowerCase()]) && "get" in i && void 0 !== (e = i.get(t, "value")) ? e : "string" == typeof (e = t.value) ? e.replace(mt, "") : null == e ? "" : e : void 0
        }
    }), C.extend({
        valHooks: {
            option: {
                get: function (e) {
                    var t = C.find.attr(e, "value");
                    return null != t ? t : ht(C.text(e))
                }
            },
            select: {
                get: function (e) {
                    var t, n, i, r = e.options,
                        o = e.selectedIndex,
                        s = "select-one" === e.type,
                        a = s ? null : [],
                        l = s ? o + 1 : r.length;
                    for (i = o < 0 ? l : s ? o : 0; i < l; i++)
                        if (((n = r[i]).selected || i === o) && !n.disabled && (!n.parentNode.disabled || !S(n.parentNode, "optgroup"))) {
                            if (t = C(n).val(), s) return t;
                            a.push(t)
                        } return a
                },
                set: function (e, t) {
                    for (var n, i, r = e.options, o = C.makeArray(t), s = r.length; s--;)((i = r[s]).selected = -1 < C.inArray(C.valHooks.option.get(i), o)) && (n = !0);
                    return n || (e.selectedIndex = -1), o
                }
            }
        }
    }), C.each(["radio", "checkbox"], function () {
        C.valHooks[this] = {
            set: function (e, t) {
                if (Array.isArray(t)) return e.checked = -1 < C.inArray(C(e).val(), t)
            }
        }, v.checkOn || (C.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    }), v.focusin = "onfocusin" in T;
    var vt = /^(?:focusinfocus|focusoutblur)$/,
        yt = function (e) {
            e.stopPropagation()
        };
    C.extend(C.event, {
        trigger: function (e, t, n, i) {
            var r, o, s, a, l, u, c, f, d = [n || x],
                h = m.call(e, "type") ? e.type : e,
                p = m.call(e, "namespace") ? e.namespace.split(".") : [];
            if (o = f = s = n = n || x, 3 !== n.nodeType && 8 !== n.nodeType && !vt.test(h + C.event.triggered) && (-1 < h.indexOf(".") && (h = (p = h.split(".")).shift(), p.sort()), l = h.indexOf(":") < 0 && "on" + h, (e = e[C.expando] ? e : new C.Event(h, "object" == typeof e && e)).isTrigger = i ? 2 : 3, e.namespace = p.join("."), e.rnamespace = e.namespace ? new RegExp("(^|\\.)" + p.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, e.result = void 0, e.target || (e.target = n), t = null == t ? [e] : C.makeArray(t, [e]), c = C.event.special[h] || {}, i || !c.trigger || !1 !== c.trigger.apply(n, t))) {
                if (!i && !c.noBubble && !_(n)) {
                    for (a = c.delegateType || h, vt.test(a + h) || (o = o.parentNode); o; o = o.parentNode) d.push(o), s = o;
                    s === (n.ownerDocument || x) && d.push(s.defaultView || s.parentWindow || T)
                }
                for (r = 0;
                    (o = d[r++]) && !e.isPropagationStopped();) f = o, e.type = 1 < r ? a : c.bindType || h, (u = (z.get(o, "events") || {})[e.type] && z.get(o, "handle")) && u.apply(o, t), (u = l && o[l]) && u.apply && V(o) && (e.result = u.apply(o, t), !1 === e.result && e.preventDefault());
                return e.type = h, i || e.isDefaultPrevented() || c._default && !1 !== c._default.apply(d.pop(), t) || !V(n) || l && y(n[h]) && !_(n) && ((s = n[l]) && (n[l] = null), C.event.triggered = h, e.isPropagationStopped() && f.addEventListener(h, yt), n[h](), e.isPropagationStopped() && f.removeEventListener(h, yt), C.event.triggered = void 0, s && (n[l] = s)), e.result
            }
        },
        simulate: function (e, t, n) {
            var i = C.extend(new C.Event, n, {
                type: e,
                isSimulated: !0
            });
            C.event.trigger(i, null, t)
        }
    }), C.fn.extend({
        trigger: function (e, t) {
            return this.each(function () {
                C.event.trigger(e, t, this)
            })
        },
        triggerHandler: function (e, t) {
            var n = this[0];
            if (n) return C.event.trigger(e, t, n, !0)
        }
    }), v.focusin || C.each({
        focus: "focusin",
        blur: "focusout"
    }, function (n, i) {
        var r = function (e) {
            C.event.simulate(i, e.target, C.event.fix(e))
        };
        C.event.special[i] = {
            setup: function () {
                var e = this.ownerDocument || this,
                    t = z.access(e, i);
                t || e.addEventListener(n, r, !0), z.access(e, i, (t || 0) + 1)
            },
            teardown: function () {
                var e = this.ownerDocument || this,
                    t = z.access(e, i) - 1;
                t ? z.access(e, i, t) : (e.removeEventListener(n, r, !0), z.remove(e, i))
            }
        }
    });
    var _t = T.location,
        bt = Date.now(),
        wt = /\?/;
    C.parseXML = function (e) {
        var t;
        if (!e || "string" != typeof e) return null;
        try {
            t = (new T.DOMParser).parseFromString(e, "text/xml")
        } catch (e) {
            t = void 0
        }
        return t && !t.getElementsByTagName("parsererror").length || C.error("Invalid XML: " + e), t
    };
    var Et = /\[\]$/,
        Tt = /\r?\n/g,
        xt = /^(?:submit|button|image|reset|file)$/i,
        Ct = /^(?:input|select|textarea|keygen)/i;

    function Dt(n, e, i, r) {
        var t;
        if (Array.isArray(e)) C.each(e, function (e, t) {
            i || Et.test(n) ? r(n, t) : Dt(n + "[" + ("object" == typeof t && null != t ? e : "") + "]", t, i, r)
        });
        else if (i || "object" !== w(e)) r(n, e);
        else
            for (t in e) Dt(n + "[" + t + "]", e[t], i, r)
    }
    C.param = function (e, t) {
        var n, i = [],
            r = function (e, t) {
                var n = y(t) ? t() : t;
                i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(null == n ? "" : n)
            };
        if (Array.isArray(e) || e.jquery && !C.isPlainObject(e)) C.each(e, function () {
            r(this.name, this.value)
        });
        else
            for (n in e) Dt(n, e[n], t, r);
        return i.join("&")
    }, C.fn.extend({
        serialize: function () {
            return C.param(this.serializeArray())
        },
        serializeArray: function () {
            return this.map(function () {
                var e = C.prop(this, "elements");
                return e ? C.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !C(this).is(":disabled") && Ct.test(this.nodeName) && !xt.test(e) && (this.checked || !le.test(e))
            }).map(function (e, t) {
                var n = C(this).val();
                return null == n ? null : Array.isArray(n) ? C.map(n, function (e) {
                    return {
                        name: t.name,
                        value: e.replace(Tt, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: n.replace(Tt, "\r\n")
                }
            }).get()
        }
    });
    var St = /%20/g,
        At = /#.*$/,
        Nt = /([?&])_=[^&]*/,
        kt = /^(.*?):[ \t]*([^\r\n]*)$/gm,
        It = /^(?:GET|HEAD)$/,
        Ot = /^\/\//,
        jt = {},
        Lt = {},
        Pt = "*/".concat("*"),
        Ht = x.createElement("a");

    function Rt(o) {
        return function (e, t) {
            "string" != typeof e && (t = e, e = "*");
            var n, i = 0,
                r = e.toLowerCase().match(P) || [];
            if (y(t))
                for (; n = r[i++];) "+" === n[0] ? (n = n.slice(1) || "*", (o[n] = o[n] || []).unshift(t)) : (o[n] = o[n] || []).push(t)
        }
    }

    function qt(t, r, o, s) {
        var a = {},
            l = t === Lt;

        function u(e) {
            var i;
            return a[e] = !0, C.each(t[e] || [], function (e, t) {
                var n = t(r, o, s);
                return "string" != typeof n || l || a[n] ? l ? !(i = n) : void 0 : (r.dataTypes.unshift(n), u(n), !1)
            }), i
        }
        return u(r.dataTypes[0]) || !a["*"] && u("*")
    }

    function Wt(e, t) {
        var n, i, r = C.ajaxSettings.flatOptions || {};
        for (n in t) void 0 !== t[n] && ((r[n] ? e : i || (i = {}))[n] = t[n]);
        return i && C.extend(!0, e, i), e
    }
    Ht.href = _t.href, C.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: _t.href,
            type: "GET",
            isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(_t.protocol),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Pt,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /\bxml\b/,
                html: /\bhtml/,
                json: /\bjson\b/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText",
                json: "responseJSON"
            },
            converters: {
                "* text": String,
                "text html": !0,
                "text json": JSON.parse,
                "text xml": C.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function (e, t) {
            return t ? Wt(Wt(e, C.ajaxSettings), t) : Wt(C.ajaxSettings, e)
        },
        ajaxPrefilter: Rt(jt),
        ajaxTransport: Rt(Lt),
        ajax: function (e, t) {
            "object" == typeof e && (t = e, e = void 0), t = t || {};
            var c, f, d, n, h, i, p, g, r, o, m = C.ajaxSetup({}, t),
                v = m.context || m,
                y = m.context && (v.nodeType || v.jquery) ? C(v) : C.event,
                _ = C.Deferred(),
                b = C.Callbacks("once memory"),
                w = m.statusCode || {},
                s = {},
                a = {},
                l = "canceled",
                E = {
                    readyState: 0,
                    getResponseHeader: function (e) {
                        var t;
                        if (p) {
                            if (!n)
                                for (n = {}; t = kt.exec(d);) n[t[1].toLowerCase()] = t[2];
                            t = n[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    },
                    getAllResponseHeaders: function () {
                        return p ? d : null
                    },
                    setRequestHeader: function (e, t) {
                        return null == p && (e = a[e.toLowerCase()] = a[e.toLowerCase()] || e, s[e] = t), this
                    },
                    overrideMimeType: function (e) {
                        return null == p && (m.mimeType = e), this
                    },
                    statusCode: function (e) {
                        var t;
                        if (e)
                            if (p) E.always(e[E.status]);
                            else
                                for (t in e) w[t] = [w[t], e[t]];
                        return this
                    },
                    abort: function (e) {
                        var t = e || l;
                        return c && c.abort(t), u(0, t), this
                    }
                };
            if (_.promise(E), m.url = ((e || m.url || _t.href) + "").replace(Ot, _t.protocol + "//"), m.type = t.method || t.type || m.method || m.type, m.dataTypes = (m.dataType || "*").toLowerCase().match(P) || [""], null == m.crossDomain) {
                i = x.createElement("a");
                try {
                    i.href = m.url, i.href = i.href, m.crossDomain = Ht.protocol + "//" + Ht.host != i.protocol + "//" + i.host
                } catch (e) {
                    m.crossDomain = !0
                }
            }
            if (m.data && m.processData && "string" != typeof m.data && (m.data = C.param(m.data, m.traditional)), qt(jt, m, t, E), p) return E;
            for (r in (g = C.event && m.global) && 0 == C.active++ && C.event.trigger("ajaxStart"), m.type = m.type.toUpperCase(), m.hasContent = !It.test(m.type), f = m.url.replace(At, ""), m.hasContent ? m.data && m.processData && 0 === (m.contentType || "").indexOf("application/x-www-form-urlencoded") && (m.data = m.data.replace(St, "+")) : (o = m.url.slice(f.length), m.data && (m.processData || "string" == typeof m.data) && (f += (wt.test(f) ? "&" : "?") + m.data, delete m.data), !1 === m.cache && (f = f.replace(Nt, "$1"), o = (wt.test(f) ? "&" : "?") + "_=" + bt++ + o), m.url = f + o), m.ifModified && (C.lastModified[f] && E.setRequestHeader("If-Modified-Since", C.lastModified[f]), C.etag[f] && E.setRequestHeader("If-None-Match", C.etag[f])), (m.data && m.hasContent && !1 !== m.contentType || t.contentType) && E.setRequestHeader("Content-Type", m.contentType), E.setRequestHeader("Accept", m.dataTypes[0] && m.accepts[m.dataTypes[0]] ? m.accepts[m.dataTypes[0]] + ("*" !== m.dataTypes[0] ? ", " + Pt + "; q=0.01" : "") : m.accepts["*"]), m.headers) E.setRequestHeader(r, m.headers[r]);
            if (m.beforeSend && (!1 === m.beforeSend.call(v, E, m) || p)) return E.abort();
            if (l = "abort", b.add(m.complete), E.done(m.success), E.fail(m.error), c = qt(Lt, m, t, E)) {
                if (E.readyState = 1, g && y.trigger("ajaxSend", [E, m]), p) return E;
                m.async && 0 < m.timeout && (h = T.setTimeout(function () {
                    E.abort("timeout")
                }, m.timeout));
                try {
                    p = !1, c.send(s, u)
                } catch (e) {
                    if (p) throw e;
                    u(-1, e)
                }
            } else u(-1, "No Transport");

            function u(e, t, n, i) {
                var r, o, s, a, l, u = t;
                p || (p = !0, h && T.clearTimeout(h), c = void 0, d = i || "", E.readyState = 0 < e ? 4 : 0, r = 200 <= e && e < 300 || 304 === e, n && (a = function (e, t, n) {
                    for (var i, r, o, s, a = e.contents, l = e.dataTypes;
                        "*" === l[0];) l.shift(), void 0 === i && (i = e.mimeType || t.getResponseHeader("Content-Type"));
                    if (i)
                        for (r in a)
                            if (a[r] && a[r].test(i)) {
                                l.unshift(r);
                                break
                            } if (l[0] in n) o = l[0];
                    else {
                        for (r in n) {
                            if (!l[0] || e.converters[r + " " + l[0]]) {
                                o = r;
                                break
                            }
                            s || (s = r)
                        }
                        o = o || s
                    }
                    if (o) return o !== l[0] && l.unshift(o), n[o]
                }(m, E, n)), a = function (e, t, n, i) {
                    var r, o, s, a, l, u = {},
                        c = e.dataTypes.slice();
                    if (c[1])
                        for (s in e.converters) u[s.toLowerCase()] = e.converters[s];
                    for (o = c.shift(); o;)
                        if (e.responseFields[o] && (n[e.responseFields[o]] = t), !l && i && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = o, o = c.shift())
                            if ("*" === o) o = l;
                            else if ("*" !== l && l !== o) {
                        if (!(s = u[l + " " + o] || u["* " + o]))
                            for (r in u)
                                if ((a = r.split(" "))[1] === o && (s = u[l + " " + a[0]] || u["* " + a[0]])) {
                                    !0 === s ? s = u[r] : !0 !== u[r] && (o = a[0], c.unshift(a[1]));
                                    break
                                } if (!0 !== s)
                            if (s && e.throws) t = s(t);
                            else try {
                                t = s(t)
                            } catch (e) {
                                return {
                                    state: "parsererror",
                                    error: s ? e : "No conversion from " + l + " to " + o
                                }
                            }
                    }
                    return {
                        state: "success",
                        data: t
                    }
                }(m, a, E, r), r ? (m.ifModified && ((l = E.getResponseHeader("Last-Modified")) && (C.lastModified[f] = l), (l = E.getResponseHeader("etag")) && (C.etag[f] = l)), 204 === e || "HEAD" === m.type ? u = "nocontent" : 304 === e ? u = "notmodified" : (u = a.state, o = a.data, r = !(s = a.error))) : (s = u, !e && u || (u = "error", e < 0 && (e = 0))), E.status = e, E.statusText = (t || u) + "", r ? _.resolveWith(v, [o, u, E]) : _.rejectWith(v, [E, u, s]), E.statusCode(w), w = void 0, g && y.trigger(r ? "ajaxSuccess" : "ajaxError", [E, m, r ? o : s]), b.fireWith(v, [E, u]), g && (y.trigger("ajaxComplete", [E, m]), --C.active || C.event.trigger("ajaxStop")))
            }
            return E
        },
        getJSON: function (e, t, n) {
            return C.get(e, t, n, "json")
        },
        getScript: function (e, t) {
            return C.get(e, void 0, t, "script")
        }
    }), C.each(["get", "post"], function (e, r) {
        C[r] = function (e, t, n, i) {
            return y(t) && (i = i || n, n = t, t = void 0), C.ajax(C.extend({
                url: e,
                type: r,
                dataType: i,
                data: t,
                success: n
            }, C.isPlainObject(e) && e))
        }
    }), C._evalUrl = function (e) {
        return C.ajax({
            url: e,
            type: "GET",
            dataType: "script",
            cache: !0,
            async: !1,
            global: !1,
            throws: !0
        })
    }, C.fn.extend({
        wrapAll: function (e) {
            var t;
            return this[0] && (y(e) && (e = e.call(this[0])), t = C(e, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                for (var e = this; e.firstElementChild;) e = e.firstElementChild;
                return e
            }).append(this)), this
        },
        wrapInner: function (n) {
            return y(n) ? this.each(function (e) {
                C(this).wrapInner(n.call(this, e))
            }) : this.each(function () {
                var e = C(this),
                    t = e.contents();
                t.length ? t.wrapAll(n) : e.append(n)
            })
        },
        wrap: function (t) {
            var n = y(t);
            return this.each(function (e) {
                C(this).wrapAll(n ? t.call(this, e) : t)
            })
        },
        unwrap: function (e) {
            return this.parent(e).not("body").each(function () {
                C(this).replaceWith(this.childNodes)
            }), this
        }
    }), C.expr.pseudos.hidden = function (e) {
        return !C.expr.pseudos.visible(e)
    }, C.expr.pseudos.visible = function (e) {
        return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length)
    }, C.ajaxSettings.xhr = function () {
        try {
            return new T.XMLHttpRequest
        } catch (e) {}
    };
    var Mt = {
            0: 200,
            1223: 204
        },
        Ft = C.ajaxSettings.xhr();
    v.cors = !!Ft && "withCredentials" in Ft, v.ajax = Ft = !!Ft, C.ajaxTransport(function (r) {
        var o, s;
        if (v.cors || Ft && !r.crossDomain) return {
            send: function (e, t) {
                var n, i = r.xhr();
                if (i.open(r.type, r.url, r.async, r.username, r.password), r.xhrFields)
                    for (n in r.xhrFields) i[n] = r.xhrFields[n];
                for (n in r.mimeType && i.overrideMimeType && i.overrideMimeType(r.mimeType), r.crossDomain || e["X-Requested-With"] || (e["X-Requested-With"] = "XMLHttpRequest"), e) i.setRequestHeader(n, e[n]);
                o = function (e) {
                    return function () {
                        o && (o = s = i.onload = i.onerror = i.onabort = i.ontimeout = i.onreadystatechange = null, "abort" === e ? i.abort() : "error" === e ? "number" != typeof i.status ? t(0, "error") : t(i.status, i.statusText) : t(Mt[i.status] || i.status, i.statusText, "text" !== (i.responseType || "text") || "string" != typeof i.responseText ? {
                            binary: i.response
                        } : {
                            text: i.responseText
                        }, i.getAllResponseHeaders()))
                    }
                }, i.onload = o(), s = i.onerror = i.ontimeout = o("error"), void 0 !== i.onabort ? i.onabort = s : i.onreadystatechange = function () {
                    4 === i.readyState && T.setTimeout(function () {
                        o && s()
                    })
                }, o = o("abort");
                try {
                    i.send(r.hasContent && r.data || null)
                } catch (e) {
                    if (o) throw e
                }
            },
            abort: function () {
                o && o()
            }
        }
    }), C.ajaxPrefilter(function (e) {
        e.crossDomain && (e.contents.script = !1)
    }), C.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /\b(?:java|ecma)script\b/
        },
        converters: {
            "text script": function (e) {
                return C.globalEval(e), e
            }
        }
    }), C.ajaxPrefilter("script", function (e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET")
    }), C.ajaxTransport("script", function (n) {
        var i, r;
        if (n.crossDomain) return {
            send: function (e, t) {
                i = C("<script>").prop({
                    charset: n.scriptCharset,
                    src: n.url
                }).on("load error", r = function (e) {
                    i.remove(), r = null, e && t("error" === e.type ? 404 : 200, e.type)
                }), x.head.appendChild(i[0])
            },
            abort: function () {
                r && r()
            }
        }
    });
    var Bt, Ut = [],
        Kt = /(=)\?(?=&|$)|\?\?/;
    C.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function () {
            var e = Ut.pop() || C.expando + "_" + bt++;
            return this[e] = !0, e
        }
    }), C.ajaxPrefilter("json jsonp", function (e, t, n) {
        var i, r, o, s = !1 !== e.jsonp && (Kt.test(e.url) ? "url" : "string" == typeof e.data && 0 === (e.contentType || "").indexOf("application/x-www-form-urlencoded") && Kt.test(e.data) && "data");
        if (s || "jsonp" === e.dataTypes[0]) return i = e.jsonpCallback = y(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback, s ? e[s] = e[s].replace(Kt, "$1" + i) : !1 !== e.jsonp && (e.url += (wt.test(e.url) ? "&" : "?") + e.jsonp + "=" + i), e.converters["script json"] = function () {
            return o || C.error(i + " was not called"), o[0]
        }, e.dataTypes[0] = "json", r = T[i], T[i] = function () {
            o = arguments
        }, n.always(function () {
            void 0 === r ? C(T).removeProp(i) : T[i] = r, e[i] && (e.jsonpCallback = t.jsonpCallback, Ut.push(i)), o && y(r) && r(o[0]), o = r = void 0
        }), "script"
    }), v.createHTMLDocument = ((Bt = x.implementation.createHTMLDocument("").body).innerHTML = "<form></form><form></form>", 2 === Bt.childNodes.length), C.parseHTML = function (e, t, n) {
        return "string" != typeof e ? [] : ("boolean" == typeof t && (n = t, t = !1), t || (v.createHTMLDocument ? ((i = (t = x.implementation.createHTMLDocument("")).createElement("base")).href = x.location.href, t.head.appendChild(i)) : t = x), o = !n && [], (r = A.exec(e)) ? [t.createElement(r[1])] : (r = ve([e], t, o), o && o.length && C(o).remove(), C.merge([], r.childNodes)));
        var i, r, o
    }, C.fn.load = function (e, t, n) {
        var i, r, o, s = this,
            a = e.indexOf(" ");
        return -1 < a && (i = ht(e.slice(a)), e = e.slice(0, a)), y(t) ? (n = t, t = void 0) : t && "object" == typeof t && (r = "POST"), 0 < s.length && C.ajax({
            url: e,
            type: r || "GET",
            dataType: "html",
            data: t
        }).done(function (e) {
            o = arguments, s.html(i ? C("<div>").append(C.parseHTML(e)).find(i) : e)
        }).always(n && function (e, t) {
            s.each(function () {
                n.apply(this, o || [e.responseText, t, e])
            })
        }), this
    }, C.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        C.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), C.expr.pseudos.animated = function (t) {
        return C.grep(C.timers, function (e) {
            return t === e.elem
        }).length
    }, C.offset = {
        setOffset: function (e, t, n) {
            var i, r, o, s, a, l, u = C.css(e, "position"),
                c = C(e),
                f = {};
            "static" === u && (e.style.position = "relative"), a = c.offset(), o = C.css(e, "top"), l = C.css(e, "left"), r = ("absolute" === u || "fixed" === u) && -1 < (o + l).indexOf("auto") ? (s = (i = c.position()).top, i.left) : (s = parseFloat(o) || 0, parseFloat(l) || 0), y(t) && (t = t.call(e, n, C.extend({}, a))), null != t.top && (f.top = t.top - a.top + s), null != t.left && (f.left = t.left - a.left + r), "using" in t ? t.using.call(e, f) : c.css(f)
        }
    }, C.fn.extend({
        offset: function (t) {
            if (arguments.length) return void 0 === t ? this : this.each(function (e) {
                C.offset.setOffset(this, t, e)
            });
            var e, n, i = this[0];
            return i ? i.getClientRects().length ? (e = i.getBoundingClientRect(), n = i.ownerDocument.defaultView, {
                top: e.top + n.pageYOffset,
                left: e.left + n.pageXOffset
            }) : {
                top: 0,
                left: 0
            } : void 0
        },
        position: function () {
            if (this[0]) {
                var e, t, n, i = this[0],
                    r = {
                        top: 0,
                        left: 0
                    };
                if ("fixed" === C.css(i, "position")) t = i.getBoundingClientRect();
                else {
                    for (t = this.offset(), n = i.ownerDocument, e = i.offsetParent || n.documentElement; e && (e === n.body || e === n.documentElement) && "static" === C.css(e, "position");) e = e.parentNode;
                    e && e !== i && 1 === e.nodeType && ((r = C(e).offset()).top += C.css(e, "borderTopWidth", !0), r.left += C.css(e, "borderLeftWidth", !0))
                }
                return {
                    top: t.top - r.top - C.css(i, "marginTop", !0),
                    left: t.left - r.left - C.css(i, "marginLeft", !0)
                }
            }
        },
        offsetParent: function () {
            return this.map(function () {
                for (var e = this.offsetParent; e && "static" === C.css(e, "position");) e = e.offsetParent;
                return e || ye
            })
        }
    }), C.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function (t, r) {
        var o = "pageYOffset" === r;
        C.fn[t] = function (e) {
            return B(this, function (e, t, n) {
                var i;
                if (_(e) ? i = e : 9 === e.nodeType && (i = e.defaultView), void 0 === n) return i ? i[r] : e[t];
                i ? i.scrollTo(o ? i.pageXOffset : n, o ? n : i.pageYOffset) : e[t] = n
            }, t, e, arguments.length)
        }
    }), C.each(["top", "left"], function (e, n) {
        C.cssHooks[n] = Me(v.pixelPosition, function (e, t) {
            if (t) return t = We(e, n), He.test(t) ? C(e).position()[n] + "px" : t
        })
    }), C.each({
        Height: "height",
        Width: "width"
    }, function (s, a) {
        C.each({
            padding: "inner" + s,
            content: a,
            "": "outer" + s
        }, function (i, o) {
            C.fn[o] = function (e, t) {
                var n = arguments.length && (i || "boolean" != typeof e),
                    r = i || (!0 === e || !0 === t ? "margin" : "border");
                return B(this, function (e, t, n) {
                    var i;
                    return _(e) ? 0 === o.indexOf("outer") ? e["inner" + s] : e.document.documentElement["client" + s] : 9 === e.nodeType ? (i = e.documentElement, Math.max(e.body["scroll" + s], i["scroll" + s], e.body["offset" + s], i["offset" + s], i["client" + s])) : void 0 === n ? C.css(e, t, r) : C.style(e, t, n, r)
                }, a, n ? e : void 0, n)
            }
        })
    }), C.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), function (e, n) {
        C.fn[n] = function (e, t) {
            return 0 < arguments.length ? this.on(n, null, e, t) : this.trigger(n)
        }
    }), C.fn.extend({
        hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }
    }), C.fn.extend({
        bind: function (e, t, n) {
            return this.on(e, null, t, n)
        },
        unbind: function (e, t) {
            return this.off(e, null, t)
        },
        delegate: function (e, t, n, i) {
            return this.on(t, e, n, i)
        },
        undelegate: function (e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    }), C.proxy = function (e, t) {
        var n, i, r;
        if ("string" == typeof t && (n = e[t], t = e, e = n), y(e)) return i = a.call(arguments, 2), (r = function () {
            return e.apply(t || this, i.concat(a.call(arguments)))
        }).guid = e.guid = e.guid || C.guid++, r
    }, C.holdReady = function (e) {
        e ? C.readyWait++ : C.ready(!0)
    }, C.isArray = Array.isArray, C.parseJSON = JSON.parse, C.nodeName = S, C.isFunction = y, C.isWindow = _, C.camelCase = Q, C.type = w, C.now = Date.now, C.isNumeric = function (e) {
        var t = C.type(e);
        return ("number" === t || "string" === t) && !isNaN(e - parseFloat(e))
    }, "function" == typeof define && define.amd && define("jquery", [], function () {
        return C
    });
    var $t = T.jQuery,
        Qt = T.$;
    return C.noConflict = function (e) {
        return T.$ === C && (T.$ = Qt), e && T.jQuery === C && (T.jQuery = $t), C
    }, e || (T.jQuery = T.$ = C), C
}),
function (e, t) {
    "object" == typeof exports && "undefined" != typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define(t) : e.Popper = t()
}(this, function () {
    "use strict";

    function s(e) {
        return e && "[object Function]" === {}.toString.call(e)
    }

    function b(e, t) {
        if (1 !== e.nodeType) return [];
        var n = getComputedStyle(e, null);
        return t ? n[t] : n
    }

    function y(e) {
        return "HTML" === e.nodeName ? e : e.parentNode || e.host
    }

    function _(e) {
        if (!e) return document.body;
        switch (e.nodeName) {
            case "HTML":
            case "BODY":
                return e.ownerDocument.body;
            case "#document":
                return e.body
        }
        var t = b(e),
            n = t.overflow,
            i = t.overflowX,
            r = t.overflowY;
        return /(auto|scroll)/.test(n + r + i) ? e : _(y(e))
    }

    function w(e) {
        var t = e && e.offsetParent,
            n = t && t.nodeName;
        return n && "BODY" !== n && "HTML" !== n ? -1 !== ["TD", "TABLE"].indexOf(t.nodeName) && "static" === b(t, "position") ? w(t) : t : e ? e.ownerDocument.documentElement : document.documentElement
    }

    function c(e) {
        return null === e.parentNode ? e : c(e.parentNode)
    }

    function E(e, t) {
        if (!(e && e.nodeType && t && t.nodeType)) return document.documentElement;
        var n = e.compareDocumentPosition(t) & Node.DOCUMENT_POSITION_FOLLOWING,
            i = n ? e : t,
            r = n ? t : e,
            o = document.createRange();
        o.setStart(i, 0), o.setEnd(r, 0);
        var s, a, l = o.commonAncestorContainer;
        if (e !== l && t !== l || i.contains(r)) return "BODY" === (a = (s = l).nodeName) || "HTML" !== a && w(s.firstElementChild) !== s ? w(l) : l;
        var u = c(e);
        return u.host ? E(u.host, t) : E(e, c(t).host)
    }

    function T(e) {
        var t = "top" === (1 < arguments.length && void 0 !== arguments[1] ? arguments[1] : "top") ? "scrollTop" : "scrollLeft",
            n = e.nodeName;
        if ("BODY" !== n && "HTML" !== n) return e[t];
        var i = e.ownerDocument.documentElement;
        return (e.ownerDocument.scrollingElement || i)[t]
    }

    function f(e, t) {
        var n = "x" === t ? "Left" : "Top",
            i = "Left" == n ? "Right" : "Bottom";
        return parseFloat(e["border" + n + "Width"], 10) + parseFloat(e["border" + i + "Width"], 10)
    }

    function i(e, t, n, i) {
        return R(t["offset" + e], t["scroll" + e], n["client" + e], n["offset" + e], n["scroll" + e], F() ? n["offset" + e] + i["margin" + ("Height" === e ? "Top" : "Left")] + i["margin" + ("Height" === e ? "Bottom" : "Right")] : 0)
    }

    function x() {
        var e = document.body,
            t = document.documentElement,
            n = F() && getComputedStyle(t);
        return {
            height: i("Height", e, t, n),
            width: i("Width", e, t, n)
        }
    }

    function C(e) {
        return K({}, e, {
            right: e.left + e.width,
            bottom: e.top + e.height
        })
    }

    function D(e) {
        var t = {};
        if (F()) try {
            t = e.getBoundingClientRect();
            var n = T(e, "top"),
                i = T(e, "left");
            t.top += n, t.left += i, t.bottom += n, t.right += i
        } catch (e) {} else t = e.getBoundingClientRect();
        var r = {
                left: t.left,
                top: t.top,
                width: t.right - t.left,
                height: t.bottom - t.top
            },
            o = "HTML" === e.nodeName ? x() : {},
            s = o.width || e.clientWidth || r.right - r.left,
            a = o.height || e.clientHeight || r.bottom - r.top,
            l = e.offsetWidth - s,
            u = e.offsetHeight - a;
        if (l || u) {
            var c = b(e);
            l -= f(c, "x"), u -= f(c, "y"), r.width -= l, r.height -= u
        }
        return C(r)
    }

    function S(e, t) {
        var n = F(),
            i = "HTML" === t.nodeName,
            r = D(e),
            o = D(t),
            s = _(e),
            a = b(t),
            l = parseFloat(a.borderTopWidth, 10),
            u = parseFloat(a.borderLeftWidth, 10),
            c = C({
                top: r.top - o.top - l,
                left: r.left - o.left - u,
                width: r.width,
                height: r.height
            });
        if (c.marginTop = 0, c.marginLeft = 0, !n && i) {
            var f = parseFloat(a.marginTop, 10),
                d = parseFloat(a.marginLeft, 10);
            c.top -= l - f, c.bottom -= l - f, c.left -= u - d, c.right -= u - d, c.marginTop = f, c.marginLeft = d
        }
        return (n ? t.contains(s) : t === s && "BODY" !== s.nodeName) && (c = function (e, t) {
            var n = 2 < arguments.length && void 0 !== arguments[2] && arguments[2],
                i = T(t, "top"),
                r = T(t, "left"),
                o = n ? -1 : 1;
            return e.top += i * o, e.bottom += i * o, e.left += r * o, e.right += r * o, e
        }(c, t)), c
    }

    function d(e, t, n, i) {
        var r, o, s, a, l, u, c, f = {
                top: 0,
                left: 0
            },
            d = E(e, t);
        if ("viewport" === i) o = (r = d).ownerDocument.documentElement, s = S(r, o), a = R(o.clientWidth, window.innerWidth || 0), l = R(o.clientHeight, window.innerHeight || 0), u = T(o), c = T(o, "left"), f = C({
            top: u - s.top + s.marginTop,
            left: c - s.left + s.marginLeft,
            width: a,
            height: l
        });
        else {
            var h;
            "scrollParent" === i ? "BODY" === (h = _(y(t))).nodeName && (h = e.ownerDocument.documentElement) : h = "window" === i ? e.ownerDocument.documentElement : i;
            var p = S(h, d);
            if ("HTML" !== h.nodeName || function e(t) {
                    var n = t.nodeName;
                    return "BODY" !== n && "HTML" !== n && ("fixed" === b(t, "position") || e(y(t)))
                }(d)) f = p;
            else {
                var g = x(),
                    m = g.height,
                    v = g.width;
                f.top += p.top - p.marginTop, f.bottom = m + p.top, f.left += p.left - p.marginLeft, f.right = v + p.left
            }
        }
        return f.left += n, f.top += n, f.right -= n, f.bottom -= n, f
    }

    function a(e, t, i, n, r) {
        var o = 5 < arguments.length && void 0 !== arguments[5] ? arguments[5] : 0;
        if (-1 === e.indexOf("auto")) return e;
        var s = d(i, n, o, r),
            a = {
                top: {
                    width: s.width,
                    height: t.top - s.top
                },
                right: {
                    width: s.right - t.right,
                    height: s.height
                },
                bottom: {
                    width: s.width,
                    height: s.bottom - t.bottom
                },
                left: {
                    width: t.left - s.left,
                    height: s.height
                }
            },
            l = Object.keys(a).map(function (e) {
                return K({
                    key: e
                }, a[e], {
                    area: (t = a[e], t.width * t.height)
                });
                var t
            }).sort(function (e, t) {
                return t.area - e.area
            }),
            u = l.filter(function (e) {
                var t = e.width,
                    n = e.height;
                return t >= i.clientWidth && n >= i.clientHeight
            }),
            c = 0 < u.length ? u[0].key : l[0].key,
            f = e.split("-")[1];
        return c + (f ? "-" + f : "")
    }

    function l(e, t, n) {
        return S(n, E(t, n))
    }

    function A(e) {
        var t = getComputedStyle(e),
            n = parseFloat(t.marginTop) + parseFloat(t.marginBottom),
            i = parseFloat(t.marginLeft) + parseFloat(t.marginRight);
        return {
            width: e.offsetWidth + i,
            height: e.offsetHeight + n
        }
    }

    function N(e) {
        var t = {
            left: "right",
            right: "left",
            bottom: "top",
            top: "bottom"
        };
        return e.replace(/left|right|bottom|top/g, function (e) {
            return t[e]
        })
    }

    function k(e, t, n) {
        n = n.split("-")[0];
        var i = A(e),
            r = {
                width: i.width,
                height: i.height
            },
            o = -1 !== ["right", "left"].indexOf(n),
            s = o ? "top" : "left",
            a = o ? "left" : "top",
            l = o ? "height" : "width",
            u = o ? "width" : "height";
        return r[s] = t[s] + t[l] / 2 - i[l] / 2, r[a] = n === a ? t[a] - i[u] : t[N(a)], r
    }

    function I(e, t) {
        return Array.prototype.find ? e.find(t) : e.filter(t)[0]
    }

    function O(e, n, t) {
        return (void 0 === t ? e : e.slice(0, function (e, t, n) {
            if (Array.prototype.findIndex) return e.findIndex(function (e) {
                return e[t] === n
            });
            var i = I(e, function (e) {
                return e[t] === n
            });
            return e.indexOf(i)
        }(e, "name", t))).forEach(function (e) {
            e.function && console.warn("`modifier.function` is deprecated, use `modifier.fn`!");
            var t = e.function || e.fn;
            e.enabled && s(t) && (n.offsets.popper = C(n.offsets.popper), n.offsets.reference = C(n.offsets.reference), n = t(n, e))
        }), n
    }

    function e(e, n) {
        return e.some(function (e) {
            var t = e.name;
            return e.enabled && t === n
        })
    }

    function j(e) {
        for (var t = [!1, "ms", "Webkit", "Moz", "O"], n = e.charAt(0).toUpperCase() + e.slice(1), i = 0; i < t.length - 1; i++) {
            var r = t[i],
                o = r ? "" + r + n : e;
            if (void 0 !== document.body.style[o]) return o
        }
        return null
    }

    function o(e) {
        var t = e.ownerDocument;
        return t ? t.defaultView : window
    }

    function t(e, t, n, i) {
        n.updateBound = i, o(e).addEventListener("resize", n.updateBound, {
            passive: !0
        });
        var r = _(e);
        return function e(t, n, i, r) {
            var o = "BODY" === t.nodeName,
                s = o ? t.ownerDocument.defaultView : t;
            s.addEventListener(n, i, {
                passive: !0
            }), o || e(_(s.parentNode), n, i, r), r.push(s)
        }(r, "scroll", n.updateBound, n.scrollParents), n.scrollElement = r, n.eventsEnabled = !0, n
    }

    function n() {
        var e, t;
        this.state.eventsEnabled && (cancelAnimationFrame(this.scheduleUpdate), this.state = (e = this.reference, t = this.state, o(e).removeEventListener("resize", t.updateBound), t.scrollParents.forEach(function (e) {
            e.removeEventListener("scroll", t.updateBound)
        }), t.updateBound = null, t.scrollParents = [], t.scrollElement = null, t.eventsEnabled = !1, t))
    }

    function h(e) {
        return "" !== e && !isNaN(parseFloat(e)) && isFinite(e)
    }

    function u(n, i) {
        Object.keys(i).forEach(function (e) {
            var t = ""; - 1 !== ["width", "height", "top", "right", "bottom", "left"].indexOf(e) && h(i[e]) && (t = "px"), n.style[e] = i[e] + t
        })
    }

    function L(e, t, n) {
        var i = I(e, function (e) {
                return e.name === t
            }),
            r = !!i && e.some(function (e) {
                return e.name === n && e.enabled && e.order < i.order
            });
        if (!r) {
            var o = "`" + t + "`";
            console.warn("`" + n + "` modifier is required by " + o + " modifier in order to work, be sure to include it before " + o + "!")
        }
        return r
    }

    function r(e) {
        var t = 1 < arguments.length && void 0 !== arguments[1] && arguments[1],
            n = Q.indexOf(e),
            i = Q.slice(n + 1).concat(Q.slice(0, n));
        return t ? i.reverse() : i
    }

    function p(e, r, o, t) {
        var s = [0, 0],
            a = -1 !== ["right", "left"].indexOf(t),
            n = e.split(/(\+|\-)/).map(function (e) {
                return e.trim()
            }),
            i = n.indexOf(I(n, function (e) {
                return -1 !== e.search(/,|\s/)
            }));
        n[i] && -1 === n[i].indexOf(",") && console.warn("Offsets separated by white space(s) are deprecated, use a comma (,) instead.");
        var l = /\s*,\s*|\s+/,
            u = -1 === i ? [n] : [n.slice(0, i).concat([n[i].split(l)[0]]), [n[i].split(l)[1]].concat(n.slice(i + 1))];
        return (u = u.map(function (e, t) {
            var n = (1 === t ? !a : a) ? "height" : "width",
                i = !1;
            return e.reduce(function (e, t) {
                return "" === e[e.length - 1] && -1 !== ["+", "-"].indexOf(t) ? (e[e.length - 1] = t, i = !0, e) : i ? (e[e.length - 1] += t, i = !1, e) : e.concat(t)
            }, []).map(function (e) {
                return function (e, t, n, i) {
                    var r, o = e.match(/((?:\-|\+)?\d*\.?\d*)(.*)/),
                        s = +o[1],
                        a = o[2];
                    if (!s) return e;
                    if (0 !== a.indexOf("%")) return "vh" !== a && "vw" !== a ? s : ("vh" === a ? R(document.documentElement.clientHeight, window.innerHeight || 0) : R(document.documentElement.clientWidth, window.innerWidth || 0)) / 100 * s;
                    switch (a) {
                        case "%p":
                            r = n;
                            break;
                        case "%":
                        case "%r":
                        default:
                            r = i
                    }
                    return C(r)[t] / 100 * s
                }(e, n, r, o)
            })
        })).forEach(function (n, i) {
            n.forEach(function (e, t) {
                h(e) && (s[i] += e * ("-" === n[t - 1] ? -1 : 1))
            })
        }), s
    }
    for (var P = Math.min, H = Math.floor, R = Math.max, g = "undefined" != typeof window && "undefined" != typeof document, m = ["Edge", "Trident", "Firefox"], v = 0, q = 0; q < m.length; q += 1)
        if (g && 0 <= navigator.userAgent.indexOf(m[q])) {
            v = 1;
            break
        } var W, M = g && window.Promise ? function (e) {
            var t = !1;
            return function () {
                t || (t = !0, window.Promise.resolve().then(function () {
                    t = !1, e()
                }))
            }
        } : function (e) {
            var t = !1;
            return function () {
                t || (t = !0, setTimeout(function () {
                    t = !1, e()
                }, v))
            }
        },
        F = function () {
            return null == W && (W = -1 !== navigator.appVersion.indexOf("MSIE 10")), W
        },
        B = function () {
            function i(e, t) {
                for (var n, i = 0; i < t.length; i++)(n = t[i]).enumerable = n.enumerable || !1, n.configurable = !0, "value" in n && (n.writable = !0), Object.defineProperty(e, n.key, n)
            }
            return function (e, t, n) {
                return t && i(e.prototype, t), n && i(e, n), e
            }
        }(),
        U = function (e, t, n) {
            return t in e ? Object.defineProperty(e, t, {
                value: n,
                enumerable: !0,
                configurable: !0,
                writable: !0
            }) : e[t] = n, e
        },
        K = Object.assign || function (e) {
            for (var t, n = 1; n < arguments.length; n++)
                for (var i in t = arguments[n]) Object.prototype.hasOwnProperty.call(t, i) && (e[i] = t[i]);
            return e
        },
        $ = ["auto-start", "auto", "auto-end", "top-start", "top", "top-end", "right-start", "right", "right-end", "bottom-end", "bottom", "bottom-start", "left-end", "left", "left-start"],
        Q = $.slice(3),
        V = "flip",
        Y = "clockwise",
        z = "counterclockwise",
        X = function () {
            function o(e, t) {
                var n = this,
                    i = 2 < arguments.length && void 0 !== arguments[2] ? arguments[2] : {};
                (function (e, t) {
                    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
                })(this, o), this.scheduleUpdate = function () {
                    return requestAnimationFrame(n.update)
                }, this.update = M(this.update.bind(this)), this.options = K({}, o.Defaults, i), this.state = {
                    isDestroyed: !1,
                    isCreated: !1,
                    scrollParents: []
                }, this.reference = e && e.jquery ? e[0] : e, this.popper = t && t.jquery ? t[0] : t, this.options.modifiers = {}, Object.keys(K({}, o.Defaults.modifiers, i.modifiers)).forEach(function (e) {
                    n.options.modifiers[e] = K({}, o.Defaults.modifiers[e] || {}, i.modifiers ? i.modifiers[e] : {})
                }), this.modifiers = Object.keys(this.options.modifiers).map(function (e) {
                    return K({
                        name: e
                    }, n.options.modifiers[e])
                }).sort(function (e, t) {
                    return e.order - t.order
                }), this.modifiers.forEach(function (e) {
                    e.enabled && s(e.onLoad) && e.onLoad(n.reference, n.popper, n.options, e, n.state)
                }), this.update();
                var r = this.options.eventsEnabled;
                r && this.enableEventListeners(), this.state.eventsEnabled = r
            }
            return B(o, [{
                key: "update",
                value: function () {
                    return function () {
                        if (!this.state.isDestroyed) {
                            var e = {
                                instance: this,
                                styles: {},
                                arrowStyles: {},
                                attributes: {},
                                flipped: !1,
                                offsets: {}
                            };
                            e.offsets.reference = l(this.state, this.popper, this.reference), e.placement = a(this.options.placement, e.offsets.reference, this.popper, this.reference, this.options.modifiers.flip.boundariesElement, this.options.modifiers.flip.padding), e.originalPlacement = e.placement, e.offsets.popper = k(this.popper, e.offsets.reference, e.placement), e.offsets.popper.position = "absolute", e = O(this.modifiers, e), this.state.isCreated ? this.options.onUpdate(e) : (this.state.isCreated = !0, this.options.onCreate(e))
                        }
                    }.call(this)
                }
            }, {
                key: "destroy",
                value: function () {
                    return function () {
                        return this.state.isDestroyed = !0, e(this.modifiers, "applyStyle") && (this.popper.removeAttribute("x-placement"), this.popper.style.left = "", this.popper.style.position = "", this.popper.style.top = "", this.popper.style[j("transform")] = ""), this.disableEventListeners(), this.options.removeOnDestroy && this.popper.parentNode.removeChild(this.popper), this
                    }.call(this)
                }
            }, {
                key: "enableEventListeners",
                value: function () {
                    return function () {
                        this.state.eventsEnabled || (this.state = t(this.reference, this.options, this.state, this.scheduleUpdate))
                    }.call(this)
                }
            }, {
                key: "disableEventListeners",
                value: function () {
                    return n.call(this)
                }
            }]), o
        }();
    return X.Utils = ("undefined" == typeof window ? global : window).PopperUtils, X.placements = $, X.Defaults = {
        placement: "bottom",
        eventsEnabled: !0,
        removeOnDestroy: !1,
        onCreate: function () {},
        onUpdate: function () {},
        modifiers: {
            shift: {
                order: 100,
                enabled: !0,
                fn: function (e) {
                    var t = e.placement,
                        n = t.split("-")[0],
                        i = t.split("-")[1];
                    if (i) {
                        var r = e.offsets,
                            o = r.reference,
                            s = r.popper,
                            a = -1 !== ["bottom", "top"].indexOf(n),
                            l = a ? "left" : "top",
                            u = a ? "width" : "height",
                            c = {
                                start: U({}, l, o[l]),
                                end: U({}, l, o[l] + o[u] - s[u])
                            };
                        e.offsets.popper = K({}, s, c[i])
                    }
                    return e
                }
            },
            offset: {
                order: 200,
                enabled: !0,
                fn: function (e, t) {
                    var n, i = t.offset,
                        r = e.placement,
                        o = e.offsets,
                        s = o.popper,
                        a = o.reference,
                        l = r.split("-")[0];
                    return n = h(+i) ? [+i, 0] : p(i, s, a, l), "left" === l ? (s.top += n[0], s.left -= n[1]) : "right" === l ? (s.top += n[0], s.left += n[1]) : "top" === l ? (s.left += n[0], s.top -= n[1]) : "bottom" === l && (s.left += n[0], s.top += n[1]), e.popper = s, e
                },
                offset: 0
            },
            preventOverflow: {
                order: 300,
                enabled: !0,
                fn: function (e, i) {
                    var t = i.boundariesElement || w(e.instance.popper);
                    e.instance.reference === t && (t = w(t));
                    var r = d(e.instance.popper, e.instance.reference, i.padding, t);
                    i.boundaries = r;
                    var n = i.priority,
                        o = e.offsets.popper,
                        s = {
                            primary: function (e) {
                                var t = o[e];
                                return o[e] < r[e] && !i.escapeWithReference && (t = R(o[e], r[e])), U({}, e, t)
                            },
                            secondary: function (e) {
                                var t = "right" === e ? "left" : "top",
                                    n = o[t];
                                return o[e] > r[e] && !i.escapeWithReference && (n = P(o[t], r[e] - ("right" === e ? o.width : o.height))), U({}, t, n)
                            }
                        };
                    return n.forEach(function (e) {
                        var t = -1 === ["left", "top"].indexOf(e) ? "secondary" : "primary";
                        o = K({}, o, s[t](e))
                    }), e.offsets.popper = o, e
                },
                priority: ["left", "right", "top", "bottom"],
                padding: 5,
                boundariesElement: "scrollParent"
            },
            keepTogether: {
                order: 400,
                enabled: !0,
                fn: function (e) {
                    var t = e.offsets,
                        n = t.popper,
                        i = t.reference,
                        r = e.placement.split("-")[0],
                        o = H,
                        s = -1 !== ["top", "bottom"].indexOf(r),
                        a = s ? "right" : "bottom",
                        l = s ? "left" : "top",
                        u = s ? "width" : "height";
                    return n[a] < o(i[l]) && (e.offsets.popper[l] = o(i[l]) - n[u]), n[l] > o(i[a]) && (e.offsets.popper[l] = o(i[a])), e
                }
            },
            arrow: {
                order: 500,
                enabled: !0,
                fn: function (e, t) {
                    var n;
                    if (!L(e.instance.modifiers, "arrow", "keepTogether")) return e;
                    var i = t.element;
                    if ("string" == typeof i) {
                        if (!(i = e.instance.popper.querySelector(i))) return e
                    } else if (!e.instance.popper.contains(i)) return console.warn("WARNING: `arrow.element` must be child of its popper element!"), e;
                    var r = e.placement.split("-")[0],
                        o = e.offsets,
                        s = o.popper,
                        a = o.reference,
                        l = -1 !== ["left", "right"].indexOf(r),
                        u = l ? "height" : "width",
                        c = l ? "Top" : "Left",
                        f = c.toLowerCase(),
                        d = l ? "left" : "top",
                        h = l ? "bottom" : "right",
                        p = A(i)[u];
                    a[h] - p < s[f] && (e.offsets.popper[f] -= s[f] - (a[h] - p)), a[f] + p > s[h] && (e.offsets.popper[f] += a[f] + p - s[h]), e.offsets.popper = C(e.offsets.popper);
                    var g = a[f] + a[u] / 2 - p / 2,
                        m = b(e.instance.popper),
                        v = parseFloat(m["margin" + c], 10),
                        y = parseFloat(m["border" + c + "Width"], 10),
                        _ = g - e.offsets.popper[f] - v - y;
                    return _ = R(P(s[u] - p, _), 0), e.arrowElement = i, e.offsets.arrow = (U(n = {}, f, Math.round(_)), U(n, d, ""), n), e
                },
                element: "[x-arrow]"
            },
            flip: {
                order: 600,
                enabled: !0,
                fn: function (p, g) {
                    if (e(p.instance.modifiers, "inner")) return p;
                    if (p.flipped && p.placement === p.originalPlacement) return p;
                    var m = d(p.instance.popper, p.instance.reference, g.padding, g.boundariesElement),
                        v = p.placement.split("-")[0],
                        y = N(v),
                        _ = p.placement.split("-")[1] || "",
                        b = [];
                    switch (g.behavior) {
                        case V:
                            b = [v, y];
                            break;
                        case Y:
                            b = r(v);
                            break;
                        case z:
                            b = r(v, !0);
                            break;
                        default:
                            b = g.behavior
                    }
                    return b.forEach(function (e, t) {
                        if (v !== e || b.length === t + 1) return p;
                        v = p.placement.split("-")[0], y = N(v);
                        var n, i = p.offsets.popper,
                            r = p.offsets.reference,
                            o = H,
                            s = "left" === v && o(i.right) > o(r.left) || "right" === v && o(i.left) < o(r.right) || "top" === v && o(i.bottom) > o(r.top) || "bottom" === v && o(i.top) < o(r.bottom),
                            a = o(i.left) < o(m.left),
                            l = o(i.right) > o(m.right),
                            u = o(i.top) < o(m.top),
                            c = o(i.bottom) > o(m.bottom),
                            f = "left" === v && a || "right" === v && l || "top" === v && u || "bottom" === v && c,
                            d = -1 !== ["top", "bottom"].indexOf(v),
                            h = !!g.flipVariations && (d && "start" === _ && a || d && "end" === _ && l || !d && "start" === _ && u || !d && "end" === _ && c);
                        (s || f || h) && (p.flipped = !0, (s || f) && (v = b[t + 1]), h && (_ = "end" === (n = _) ? "start" : "start" === n ? "end" : n), p.placement = v + (_ ? "-" + _ : ""), p.offsets.popper = K({}, p.offsets.popper, k(p.instance.popper, p.offsets.reference, p.placement)), p = O(p.instance.modifiers, p, "flip"))
                    }), p
                },
                behavior: "flip",
                padding: 5,
                boundariesElement: "viewport"
            },
            inner: {
                order: 700,
                enabled: !1,
                fn: function (e) {
                    var t = e.placement,
                        n = t.split("-")[0],
                        i = e.offsets,
                        r = i.popper,
                        o = i.reference,
                        s = -1 !== ["left", "right"].indexOf(n),
                        a = -1 === ["top", "left"].indexOf(n);
                    return r[s ? "left" : "top"] = o[n] - (a ? r[s ? "width" : "height"] : 0), e.placement = N(t), e.offsets.popper = C(r), e
                }
            },
            hide: {
                order: 800,
                enabled: !0,
                fn: function (e) {
                    if (!L(e.instance.modifiers, "hide", "preventOverflow")) return e;
                    var t = e.offsets.reference,
                        n = I(e.instance.modifiers, function (e) {
                            return "preventOverflow" === e.name
                        }).boundaries;
                    if (t.bottom < n.top || t.left > n.right || t.top > n.bottom || t.right < n.left) {
                        if (!0 === e.hide) return e;
                        e.hide = !0, e.attributes["x-out-of-boundaries"] = ""
                    } else {
                        if (!1 === e.hide) return e;
                        e.hide = !1, e.attributes["x-out-of-boundaries"] = !1
                    }
                    return e
                }
            },
            computeStyle: {
                order: 850,
                enabled: !0,
                fn: function (e, t) {
                    var n = t.x,
                        i = t.y,
                        r = e.offsets.popper,
                        o = I(e.instance.modifiers, function (e) {
                            return "applyStyle" === e.name
                        }).gpuAcceleration;
                    void 0 !== o && console.warn("WARNING: `gpuAcceleration` option moved to `computeStyle` modifier and will not be supported in future versions of Popper.js!");
                    var s, a, l = void 0 === o ? t.gpuAcceleration : o,
                        u = D(w(e.instance.popper)),
                        c = {
                            position: r.position
                        },
                        f = {
                            left: H(r.left),
                            top: H(r.top),
                            bottom: H(r.bottom),
                            right: H(r.right)
                        },
                        d = "bottom" === n ? "top" : "bottom",
                        h = "right" === i ? "left" : "right",
                        p = j("transform");
                    if (a = "bottom" == d ? -u.height + f.bottom : f.top, s = "right" == h ? -u.width + f.right : f.left, l && p) c[p] = "translate3d(" + s + "px, " + a + "px, 0)", c[d] = 0, c[h] = 0, c.willChange = "transform";
                    else {
                        var g = "bottom" == d ? -1 : 1,
                            m = "right" == h ? -1 : 1;
                        c[d] = a * g, c[h] = s * m, c.willChange = d + ", " + h
                    }
                    var v = {
                        "x-placement": e.placement
                    };
                    return e.attributes = K({}, v, e.attributes), e.styles = K({}, c, e.styles), e.arrowStyles = K({}, e.offsets.arrow, e.arrowStyles), e
                },
                gpuAcceleration: !0,
                x: "bottom",
                y: "right"
            },
            applyStyle: {
                order: 900,
                enabled: !0,
                fn: function (e) {
                    return u(e.instance.popper, e.styles), t = e.instance.popper, n = e.attributes, Object.keys(n).forEach(function (e) {
                        !1 === n[e] ? t.removeAttribute(e) : t.setAttribute(e, n[e])
                    }), e.arrowElement && Object.keys(e.arrowStyles).length && u(e.arrowElement, e.arrowStyles), e;
                    var t, n
                },
                onLoad: function (e, t, n, i, r) {
                    var o = l(0, t, e),
                        s = a(n.placement, o, t, e, n.modifiers.flip.boundariesElement, n.modifiers.flip.padding);
                    return t.setAttribute("x-placement", s), u(t, {
                        position: "absolute"
                    }), n
                },
                gpuAcceleration: void 0
            }
        }
    }, X
}),
function (e, t) {
    "object" == typeof exports && "undefined" != typeof module ? t(exports, require("jquery"), require("popper.js")) : "function" == typeof define && define.amd ? define(["exports", "jquery", "popper.js"], t) : t(e.bootstrap = {}, e.jQuery, e.Popper)
}(this, function (e, t, c) {
    "use strict";

    function i(e, t) {
        for (var n = 0; n < t.length; n++) {
            var i = t[n];
            i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(e, i.key, i)
        }
    }

    function s(e, t, n) {
        return t && i(e.prototype, t), n && i(e, n), e
    }

    function u(r) {
        for (var e = 1; e < arguments.length; e++) {
            var o = null != arguments[e] ? arguments[e] : {},
                t = Object.keys(o);
            "function" == typeof Object.getOwnPropertySymbols && (t = t.concat(Object.getOwnPropertySymbols(o).filter(function (e) {
                return Object.getOwnPropertyDescriptor(o, e).enumerable
            }))), t.forEach(function (e) {
                var t, n, i;
                t = r, i = o[n = e], n in t ? Object.defineProperty(t, n, {
                    value: i,
                    enumerable: !0,
                    configurable: !0,
                    writable: !0
                }) : t[n] = i
            })
        }
        return r
    }
    t = t && t.hasOwnProperty("default") ? t.default : t, c = c && c.hasOwnProperty("default") ? c.default : c;
    var r, n, o, a, l, f, d, h, p, g, m, v, y, _, b, w, E, T, x, C, D, S, A, N, k, I, O, j, L, P, H, R, q, W, M, F, B, U, K, $, Q, V, Y, z, X, G, J, Z, ee, te, ne, ie, re, oe, se, ae, le, ue, ce, fe, de, he, pe, ge, me, ve, ye, _e, be, we, Ee, Te, xe, Ce, De, Se, Ae, Ne, ke, Ie, Oe, je, Le, Pe, He, Re, qe, We, Me, Fe, Be, Ue, Ke, $e, Qe, Ve, Ye, ze, Xe, Ge, Je, Ze, et, tt, nt, it, rt, ot, st, at, lt, ut, ct, ft, dt, ht, pt, gt, mt, vt, yt, _t, bt, wt, Et, Tt, xt, Ct, Dt, St, At, Nt, kt, It, Ot, jt, Lt, Pt, Ht, Rt, qt, Wt, Mt, Ft, Bt, Ut, Kt, $t, Qt, Vt, Yt, zt, Xt, Gt, Jt, Zt, en, tn, nn, rn, on, sn, an, ln, un, cn, fn, dn, hn, pn, gn, mn, vn, yn, _n, bn, wn, En, Tn, xn, Cn, Dn, Sn, An, Nn, kn, In, On, jn, Ln, Pn, Hn, Rn, qn, Wn, Mn, Fn, Bn = function (i) {
            var t = "transitionend";

            function e(e) {
                var t = this,
                    n = !1;
                return i(this).one(l.TRANSITION_END, function () {
                    n = !0
                }), setTimeout(function () {
                    n || l.triggerTransitionEnd(t)
                }, e), this
            }
            var l = {
                TRANSITION_END: "bsTransitionEnd",
                getUID: function (e) {
                    for (; e += ~~(1e6 * Math.random()), document.getElementById(e););
                    return e
                },
                getSelectorFromElement: function (e) {
                    var t = e.getAttribute("data-target");
                    t && "#" !== t || (t = e.getAttribute("href") || "");
                    try {
                        return 0 < i(document).find(t).length ? t : null
                    } catch (e) {
                        return null
                    }
                },
                getTransitionDurationFromElement: function (e) {
                    if (!e) return 0;
                    var t = i(e).css("transition-duration");
                    return parseFloat(t) ? (t = t.split(",")[0], 1e3 * parseFloat(t)) : 0
                },
                reflow: function (e) {
                    return e.offsetHeight
                },
                triggerTransitionEnd: function (e) {
                    i(e).trigger(t)
                },
                supportsTransitionEnd: function () {
                    return Boolean(t)
                },
                isElement: function (e) {
                    return (e[0] || e).nodeType
                },
                typeCheckConfig: function (e, t, n) {
                    for (var i in n)
                        if (Object.prototype.hasOwnProperty.call(n, i)) {
                            var r = n[i],
                                o = t[i],
                                s = o && l.isElement(o) ? "element" : (a = o, {}.toString.call(a).match(/\s([a-z]+)/i)[1].toLowerCase());
                            if (!new RegExp(r).test(s)) throw new Error(e.toUpperCase() + ': Option "' + i + '" provided type "' + s + '" but expected type "' + r + '".')
                        } var a
                }
            };
            return i.fn.emulateTransitionEnd = e, i.event.special[l.TRANSITION_END] = {
                bindType: t,
                delegateType: t,
                handle: function (e) {
                    if (i(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
                }
            }, l
        }(t),
        Un = (n = "alert", a = "." + (o = "bs.alert"), l = (r = t).fn[n], f = {
            CLOSE: "close" + a,
            CLOSED: "closed" + a,
            CLICK_DATA_API: "click" + a + ".data-api"
        }, d = "alert", h = "fade", p = "show", g = function () {
            function i(e) {
                this._element = e
            }
            var e = i.prototype;
            return e.close = function (e) {
                var t = this._element;
                e && (t = this._getRootElement(e)), this._triggerCloseEvent(t).isDefaultPrevented() || this._removeElement(t)
            }, e.dispose = function () {
                r.removeData(this._element, o), this._element = null
            }, e._getRootElement = function (e) {
                var t = Bn.getSelectorFromElement(e),
                    n = !1;
                return t && (n = r(t)[0]), n || (n = r(e).closest("." + d)[0]), n
            }, e._triggerCloseEvent = function (e) {
                var t = r.Event(f.CLOSE);
                return r(e).trigger(t), t
            }, e._removeElement = function (t) {
                var n = this;
                if (r(t).removeClass(p), r(t).hasClass(h)) {
                    var e = Bn.getTransitionDurationFromElement(t);
                    r(t).one(Bn.TRANSITION_END, function (e) {
                        return n._destroyElement(t, e)
                    }).emulateTransitionEnd(e)
                } else this._destroyElement(t)
            }, e._destroyElement = function (e) {
                r(e).detach().trigger(f.CLOSED).remove()
            }, i._jQueryInterface = function (n) {
                return this.each(function () {
                    var e = r(this),
                        t = e.data(o);
                    t || (t = new i(this), e.data(o, t)), "close" === n && t[n](this)
                })
            }, i._handleDismiss = function (t) {
                return function (e) {
                    e && e.preventDefault(), t.close(this)
                }
            }, s(i, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }]), i
        }(), r(document).on(f.CLICK_DATA_API, '[data-dismiss="alert"]', g._handleDismiss(new g)), r.fn[n] = g._jQueryInterface, r.fn[n].Constructor = g, r.fn[n].noConflict = function () {
            return r.fn[n] = l, g._jQueryInterface
        }, g),
        Kn = (v = "button", _ = "." + (y = "bs.button"), b = ".data-api", w = (m = t).fn[v], E = "active", T = "btn", C = '[data-toggle^="button"]', D = '[data-toggle="buttons"]', S = "input", A = ".active", N = ".btn", k = {
            CLICK_DATA_API: "click" + _ + b,
            FOCUS_BLUR_DATA_API: (x = "focus") + _ + b + " blur" + _ + b
        }, I = function () {
            function n(e) {
                this._element = e
            }
            var e = n.prototype;
            return e.toggle = function () {
                var e = !0,
                    t = !0,
                    n = m(this._element).closest(D)[0];
                if (n) {
                    var i = m(this._element).find(S)[0];
                    if (i) {
                        if ("radio" === i.type)
                            if (i.checked && m(this._element).hasClass(E)) e = !1;
                            else {
                                var r = m(n).find(A)[0];
                                r && m(r).removeClass(E)
                            } if (e) {
                            if (i.hasAttribute("disabled") || n.hasAttribute("disabled") || i.classList.contains("disabled") || n.classList.contains("disabled")) return;
                            i.checked = !m(this._element).hasClass(E), m(i).trigger("change")
                        }
                        i.focus(), t = !1
                    }
                }
                t && this._element.setAttribute("aria-pressed", !m(this._element).hasClass(E)), e && m(this._element).toggleClass(E)
            }, e.dispose = function () {
                m.removeData(this._element, y), this._element = null
            }, n._jQueryInterface = function (t) {
                return this.each(function () {
                    var e = m(this).data(y);
                    e || (e = new n(this), m(this).data(y, e)), "toggle" === t && e[t]()
                })
            }, s(n, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }]), n
        }(), m(document).on(k.CLICK_DATA_API, C, function (e) {
            e.preventDefault();
            var t = e.target;
            m(t).hasClass(T) || (t = m(t).closest(N)), I._jQueryInterface.call(m(t), "toggle")
        }).on(k.FOCUS_BLUR_DATA_API, C, function (e) {
            var t = m(e.target).closest(N)[0];
            m(t).toggleClass(x, /^focus(in)?$/.test(e.type))
        }), m.fn[v] = I._jQueryInterface, m.fn[v].Constructor = I, m.fn[v].noConflict = function () {
            return m.fn[v] = w, I._jQueryInterface
        }, I),
        $n = (j = "carousel", P = "." + (L = "bs.carousel"), H = ".data-api", R = (O = t).fn[j], q = {
            interval: 5e3,
            keyboard: !0,
            slide: !1,
            pause: "hover",
            wrap: !0
        }, W = {
            interval: "(number|boolean)",
            keyboard: "boolean",
            slide: "(boolean|string)",
            pause: "(string|boolean)",
            wrap: "boolean"
        }, M = "next", F = "prev", B = "left", U = "right", K = {
            SLIDE: "slide" + P,
            SLID: "slid" + P,
            KEYDOWN: "keydown" + P,
            MOUSEENTER: "mouseenter" + P,
            MOUSELEAVE: "mouseleave" + P,
            TOUCHEND: "touchend" + P,
            LOAD_DATA_API: "load" + P + H,
            CLICK_DATA_API: "click" + P + H
        }, $ = "carousel", Q = "active", V = "slide", Y = "carousel-item-right", z = "carousel-item-left", X = "carousel-item-next", G = "carousel-item-prev", J = ".active", Z = ".active.carousel-item", ee = ".carousel-item", te = ".carousel-item-next, .carousel-item-prev", ne = ".carousel-indicators", ie = "[data-slide], [data-slide-to]", re = '[data-ride="carousel"]', oe = function () {
            function o(e, t) {
                this._items = null, this._interval = null, this._activeElement = null, this._isPaused = !1, this._isSliding = !1, this.touchTimeout = null, this._config = this._getConfig(t), this._element = O(e)[0], this._indicatorsElement = O(this._element).find(ne)[0], this._addEventListeners()
            }
            var e = o.prototype;
            return e.next = function () {
                this._isSliding || this._slide(M)
            }, e.nextWhenVisible = function () {
                !document.hidden && O(this._element).is(":visible") && "hidden" !== O(this._element).css("visibility") && this.next()
            }, e.prev = function () {
                this._isSliding || this._slide(F)
            }, e.pause = function (e) {
                e || (this._isPaused = !0), O(this._element).find(te)[0] && (Bn.triggerTransitionEnd(this._element), this.cycle(!0)), clearInterval(this._interval), this._interval = null
            }, e.cycle = function (e) {
                e || (this._isPaused = !1), this._interval && (clearInterval(this._interval), this._interval = null), this._config.interval && !this._isPaused && (this._interval = setInterval((document.visibilityState ? this.nextWhenVisible : this.next).bind(this), this._config.interval))
            }, e.to = function (e) {
                var t = this;
                this._activeElement = O(this._element).find(Z)[0];
                var n = this._getItemIndex(this._activeElement);
                if (!(e > this._items.length - 1 || e < 0))
                    if (this._isSliding) O(this._element).one(K.SLID, function () {
                        return t.to(e)
                    });
                    else {
                        if (n === e) return this.pause(), void this.cycle();
                        var i = n < e ? M : F;
                        this._slide(i, this._items[e])
                    }
            }, e.dispose = function () {
                O(this._element).off(P), O.removeData(this._element, L), this._items = null, this._config = null, this._element = null, this._interval = null, this._isPaused = null, this._isSliding = null, this._activeElement = null, this._indicatorsElement = null
            }, e._getConfig = function (e) {
                return e = u({}, q, e), Bn.typeCheckConfig(j, e, W), e
            }, e._addEventListeners = function () {
                var t = this;
                this._config.keyboard && O(this._element).on(K.KEYDOWN, function (e) {
                    return t._keydown(e)
                }), "hover" === this._config.pause && (O(this._element).on(K.MOUSEENTER, function (e) {
                    return t.pause(e)
                }).on(K.MOUSELEAVE, function (e) {
                    return t.cycle(e)
                }), "ontouchstart" in document.documentElement && O(this._element).on(K.TOUCHEND, function () {
                    t.pause(), t.touchTimeout && clearTimeout(t.touchTimeout), t.touchTimeout = setTimeout(function (e) {
                        return t.cycle(e)
                    }, 500 + t._config.interval)
                }))
            }, e._keydown = function (e) {
                if (!/input|textarea/i.test(e.target.tagName)) switch (e.which) {
                    case 37:
                        e.preventDefault(), this.prev();
                        break;
                    case 39:
                        e.preventDefault(), this.next()
                }
            }, e._getItemIndex = function (e) {
                return this._items = O.makeArray(O(e).parent().find(ee)), this._items.indexOf(e)
            }, e._getItemByDirection = function (e, t) {
                var n = e === M,
                    i = e === F,
                    r = this._getItemIndex(t),
                    o = this._items.length - 1;
                if ((i && 0 === r || n && r === o) && !this._config.wrap) return t;
                var s = (r + (e === F ? -1 : 1)) % this._items.length;
                return -1 === s ? this._items[this._items.length - 1] : this._items[s]
            }, e._triggerSlideEvent = function (e, t) {
                var n = this._getItemIndex(e),
                    i = this._getItemIndex(O(this._element).find(Z)[0]),
                    r = O.Event(K.SLIDE, {
                        relatedTarget: e,
                        direction: t,
                        from: i,
                        to: n
                    });
                return O(this._element).trigger(r), r
            }, e._setActiveIndicatorElement = function (e) {
                if (this._indicatorsElement) {
                    O(this._indicatorsElement).find(J).removeClass(Q);
                    var t = this._indicatorsElement.children[this._getItemIndex(e)];
                    t && O(t).addClass(Q)
                }
            }, e._slide = function (e, t) {
                var n, i, r, o = this,
                    s = O(this._element).find(Z)[0],
                    a = this._getItemIndex(s),
                    l = t || s && this._getItemByDirection(e, s),
                    u = this._getItemIndex(l),
                    c = Boolean(this._interval);
                if (r = e === M ? (n = z, i = X, B) : (n = Y, i = G, U), l && O(l).hasClass(Q)) this._isSliding = !1;
                else if (!this._triggerSlideEvent(l, r).isDefaultPrevented() && s && l) {
                    this._isSliding = !0, c && this.pause(), this._setActiveIndicatorElement(l);
                    var f = O.Event(K.SLID, {
                        relatedTarget: l,
                        direction: r,
                        from: a,
                        to: u
                    });
                    if (O(this._element).hasClass(V)) {
                        O(l).addClass(i), Bn.reflow(l), O(s).addClass(n), O(l).addClass(n);
                        var d = Bn.getTransitionDurationFromElement(s);
                        O(s).one(Bn.TRANSITION_END, function () {
                            O(l).removeClass(n + " " + i).addClass(Q), O(s).removeClass(Q + " " + i + " " + n), o._isSliding = !1, setTimeout(function () {
                                return O(o._element).trigger(f)
                            }, 0)
                        }).emulateTransitionEnd(d)
                    } else O(s).removeClass(Q), O(l).addClass(Q), this._isSliding = !1, O(this._element).trigger(f);
                    c && this.cycle()
                }
            }, o._jQueryInterface = function (i) {
                return this.each(function () {
                    var e = O(this).data(L),
                        t = u({}, q, O(this).data());
                    "object" == typeof i && (t = u({}, t, i));
                    var n = "string" == typeof i ? i : t.slide;
                    if (e || (e = new o(this, t), O(this).data(L, e)), "number" == typeof i) e.to(i);
                    else if ("string" == typeof n) {
                        if (void 0 === e[n]) throw new TypeError('No method named "' + n + '"');
                        e[n]()
                    } else t.interval && (e.pause(), e.cycle())
                })
            }, o._dataApiClickHandler = function (e) {
                var t = Bn.getSelectorFromElement(this);
                if (t) {
                    var n = O(t)[0];
                    if (n && O(n).hasClass($)) {
                        var i = u({}, O(n).data(), O(this).data()),
                            r = this.getAttribute("data-slide-to");
                        r && (i.interval = !1), o._jQueryInterface.call(O(n), i), r && O(n).data(L).to(r), e.preventDefault()
                    }
                }
            }, s(o, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return q
                }
            }]), o
        }(), O(document).on(K.CLICK_DATA_API, ie, oe._dataApiClickHandler), O(window).on(K.LOAD_DATA_API, function () {
            O(re).each(function () {
                var e = O(this);
                oe._jQueryInterface.call(e, e.data())
            })
        }), O.fn[j] = oe._jQueryInterface, O.fn[j].Constructor = oe, O.fn[j].noConflict = function () {
            return O.fn[j] = R, oe._jQueryInterface
        }, oe),
        Qn = (ae = "collapse", ue = "." + (le = "bs.collapse"), ce = (se = t).fn[ae], fe = {
            toggle: !0,
            parent: ""
        }, de = {
            toggle: "boolean",
            parent: "(string|element)"
        }, he = {
            SHOW: "show" + ue,
            SHOWN: "shown" + ue,
            HIDE: "hide" + ue,
            HIDDEN: "hidden" + ue,
            CLICK_DATA_API: "click" + ue + ".data-api"
        }, pe = "show", ge = "collapse", me = "collapsing", ve = "collapsed", ye = "width", _e = "height", be = ".show, .collapsing", we = '[data-toggle="collapse"]', Ee = function () {
            function a(e, t) {
                this._isTransitioning = !1, this._element = e, this._config = this._getConfig(t), this._triggerArray = se.makeArray(se('[data-toggle="collapse"][href="#' + e.id + '"],[data-toggle="collapse"][data-target="#' + e.id + '"]'));
                for (var n = se(we), i = 0; i < n.length; i++) {
                    var r = n[i],
                        o = Bn.getSelectorFromElement(r);
                    null !== o && 0 < se(o).filter(e).length && (this._selector = o, this._triggerArray.push(r))
                }
                this._parent = this._config.parent ? this._getParent() : null, this._config.parent || this._addAriaAndCollapsedClass(this._element, this._triggerArray), this._config.toggle && this.toggle()
            }
            var e = a.prototype;
            return e.toggle = function () {
                se(this._element).hasClass(pe) ? this.hide() : this.show()
            }, e.show = function () {
                var e, t, n = this;
                if (!this._isTransitioning && !se(this._element).hasClass(pe) && (this._parent && 0 === (e = se.makeArray(se(this._parent).find(be).filter('[data-parent="' + this._config.parent + '"]'))).length && (e = null), !(e && (t = se(e).not(this._selector).data(le)) && t._isTransitioning))) {
                    var i = se.Event(he.SHOW);
                    if (se(this._element).trigger(i), !i.isDefaultPrevented()) {
                        e && (a._jQueryInterface.call(se(e).not(this._selector), "hide"), t || se(e).data(le, null));
                        var r = this._getDimension();
                        se(this._element).removeClass(ge).addClass(me), (this._element.style[r] = 0) < this._triggerArray.length && se(this._triggerArray).removeClass(ve).attr("aria-expanded", !0), this.setTransitioning(!0);
                        var o = "scroll" + (r[0].toUpperCase() + r.slice(1)),
                            s = Bn.getTransitionDurationFromElement(this._element);
                        se(this._element).one(Bn.TRANSITION_END, function () {
                            se(n._element).removeClass(me).addClass(ge).addClass(pe), n._element.style[r] = "", n.setTransitioning(!1), se(n._element).trigger(he.SHOWN)
                        }).emulateTransitionEnd(s), this._element.style[r] = this._element[o] + "px"
                    }
                }
            }, e.hide = function () {
                var e = this;
                if (!this._isTransitioning && se(this._element).hasClass(pe)) {
                    var t = se.Event(he.HIDE);
                    if (se(this._element).trigger(t), !t.isDefaultPrevented()) {
                        var n = this._getDimension();
                        if (this._element.style[n] = this._element.getBoundingClientRect()[n] + "px", Bn.reflow(this._element), se(this._element).addClass(me).removeClass(ge).removeClass(pe), 0 < this._triggerArray.length)
                            for (var i = 0; i < this._triggerArray.length; i++) {
                                var r = this._triggerArray[i],
                                    o = Bn.getSelectorFromElement(r);
                                if (null !== o) se(o).hasClass(pe) || se(r).addClass(ve).attr("aria-expanded", !1)
                            }
                        this.setTransitioning(!0);
                        this._element.style[n] = "";
                        var s = Bn.getTransitionDurationFromElement(this._element);
                        se(this._element).one(Bn.TRANSITION_END, function () {
                            e.setTransitioning(!1), se(e._element).removeClass(me).addClass(ge).trigger(he.HIDDEN)
                        }).emulateTransitionEnd(s)
                    }
                }
            }, e.setTransitioning = function (e) {
                this._isTransitioning = e
            }, e.dispose = function () {
                se.removeData(this._element, le), this._config = null, this._parent = null, this._element = null, this._triggerArray = null, this._isTransitioning = null
            }, e._getConfig = function (e) {
                return (e = u({}, fe, e)).toggle = Boolean(e.toggle), Bn.typeCheckConfig(ae, e, de), e
            }, e._getDimension = function () {
                return se(this._element).hasClass(ye) ? ye : _e
            }, e._getParent = function () {
                var n = this,
                    e = null;
                Bn.isElement(this._config.parent) ? (e = this._config.parent, void 0 !== this._config.parent.jquery && (e = this._config.parent[0])) : e = se(this._config.parent)[0];
                var t = '[data-toggle="collapse"][data-parent="' + this._config.parent + '"]';
                return se(e).find(t).each(function (e, t) {
                    n._addAriaAndCollapsedClass(a._getTargetFromElement(t), [t])
                }), e
            }, e._addAriaAndCollapsedClass = function (e, t) {
                if (e) {
                    var n = se(e).hasClass(pe);
                    0 < t.length && se(t).toggleClass(ve, !n).attr("aria-expanded", n)
                }
            }, a._getTargetFromElement = function (e) {
                var t = Bn.getSelectorFromElement(e);
                return t ? se(t)[0] : null
            }, a._jQueryInterface = function (i) {
                return this.each(function () {
                    var e = se(this),
                        t = e.data(le),
                        n = u({}, fe, e.data(), "object" == typeof i && i ? i : {});
                    if (!t && n.toggle && /show|hide/.test(i) && (n.toggle = !1), t || (t = new a(this, n), e.data(le, t)), "string" == typeof i) {
                        if (void 0 === t[i]) throw new TypeError('No method named "' + i + '"');
                        t[i]()
                    }
                })
            }, s(a, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return fe
                }
            }]), a
        }(), se(document).on(he.CLICK_DATA_API, we, function (e) {
            "A" === e.currentTarget.tagName && e.preventDefault();
            var n = se(this),
                t = Bn.getSelectorFromElement(this);
            se(t).each(function () {
                var e = se(this),
                    t = e.data(le) ? "toggle" : n.data();
                Ee._jQueryInterface.call(e, t)
            })
        }), se.fn[ae] = Ee._jQueryInterface, se.fn[ae].Constructor = Ee, se.fn[ae].noConflict = function () {
            return se.fn[ae] = ce, Ee._jQueryInterface
        }, Ee),
        Vn = (xe = "dropdown", De = "." + (Ce = "bs.dropdown"), Se = ".data-api", Ae = (Te = t).fn[xe], Ne = new RegExp("38|40|27"), ke = {
            HIDE: "hide" + De,
            HIDDEN: "hidden" + De,
            SHOW: "show" + De,
            SHOWN: "shown" + De,
            CLICK: "click" + De,
            CLICK_DATA_API: "click" + De + Se,
            KEYDOWN_DATA_API: "keydown" + De + Se,
            KEYUP_DATA_API: "keyup" + De + Se
        }, Ie = "disabled", Oe = "show", je = "dropup", Le = "dropright", Pe = "dropleft", He = "dropdown-menu-right", Re = "position-static", qe = '[data-toggle="dropdown"]', We = ".dropdown form", Me = ".dropdown-menu", Fe = ".navbar-nav", Be = ".dropdown-menu .dropdown-item:not(.disabled):not(:disabled)", Ue = "top-start", Ke = "top-end", $e = "bottom-start", Qe = "bottom-end", Ve = "right-start", Ye = "left-start", ze = {
            offset: 0,
            flip: !0,
            boundary: "scrollParent",
            reference: "toggle",
            display: "dynamic"
        }, Xe = {
            offset: "(number|string|function)",
            flip: "boolean",
            boundary: "(string|element)",
            reference: "(string|element)",
            display: "string"
        }, Ge = function () {
            function l(e, t) {
                this._element = e, this._popper = null, this._config = this._getConfig(t), this._menu = this._getMenuElement(), this._inNavbar = this._detectNavbar(), this._addEventListeners()
            }
            var e = l.prototype;
            return e.toggle = function () {
                if (!this._element.disabled && !Te(this._element).hasClass(Ie)) {
                    var e = l._getParentFromElement(this._element),
                        t = Te(this._menu).hasClass(Oe);
                    if (l._clearMenus(), !t) {
                        var n = {
                                relatedTarget: this._element
                            },
                            i = Te.Event(ke.SHOW, n);
                        if (Te(e).trigger(i), !i.isDefaultPrevented()) {
                            if (!this._inNavbar) {
                                if (void 0 === c) throw new TypeError("Bootstrap dropdown require Popper.js (https://popper.js.org)");
                                var r = this._element;
                                "parent" === this._config.reference ? r = e : Bn.isElement(this._config.reference) && (r = this._config.reference, void 0 !== this._config.reference.jquery && (r = this._config.reference[0])), "scrollParent" !== this._config.boundary && Te(e).addClass(Re), this._popper = new c(r, this._menu, this._getPopperConfig())
                            }
                            "ontouchstart" in document.documentElement && 0 === Te(e).closest(Fe).length && Te(document.body).children().on("mouseover", null, Te.noop), this._element.focus(), this._element.setAttribute("aria-expanded", !0), Te(this._menu).toggleClass(Oe), Te(e).toggleClass(Oe).trigger(Te.Event(ke.SHOWN, n))
                        }
                    }
                }
            }, e.dispose = function () {
                Te.removeData(this._element, Ce), Te(this._element).off(De), this._element = null, (this._menu = null) !== this._popper && (this._popper.destroy(), this._popper = null)
            }, e.update = function () {
                this._inNavbar = this._detectNavbar(), null !== this._popper && this._popper.scheduleUpdate()
            }, e._addEventListeners = function () {
                var t = this;
                Te(this._element).on(ke.CLICK, function (e) {
                    e.preventDefault(), e.stopPropagation(), t.toggle()
                })
            }, e._getConfig = function (e) {
                return e = u({}, this.constructor.Default, Te(this._element).data(), e), Bn.typeCheckConfig(xe, e, this.constructor.DefaultType), e
            }, e._getMenuElement = function () {
                if (!this._menu) {
                    var e = l._getParentFromElement(this._element);
                    this._menu = Te(e).find(Me)[0]
                }
                return this._menu
            }, e._getPlacement = function () {
                var e = Te(this._element).parent(),
                    t = $e;
                return e.hasClass(je) ? (t = Ue, Te(this._menu).hasClass(He) && (t = Ke)) : e.hasClass(Le) ? t = Ve : e.hasClass(Pe) ? t = Ye : Te(this._menu).hasClass(He) && (t = Qe), t
            }, e._detectNavbar = function () {
                return 0 < Te(this._element).closest(".navbar").length
            }, e._getPopperConfig = function () {
                var t = this,
                    e = {};
                "function" == typeof this._config.offset ? e.fn = function (e) {
                    return e.offsets = u({}, e.offsets, t._config.offset(e.offsets) || {}), e
                } : e.offset = this._config.offset;
                var n = {
                    placement: this._getPlacement(),
                    modifiers: {
                        offset: e,
                        flip: {
                            enabled: this._config.flip
                        },
                        preventOverflow: {
                            boundariesElement: this._config.boundary
                        }
                    }
                };
                return "static" === this._config.display && (n.modifiers.applyStyle = {
                    enabled: !1
                }), n
            }, l._jQueryInterface = function (t) {
                return this.each(function () {
                    var e = Te(this).data(Ce);
                    if (e || (e = new l(this, "object" == typeof t ? t : null), Te(this).data(Ce, e)), "string" == typeof t) {
                        if (void 0 === e[t]) throw new TypeError('No method named "' + t + '"');
                        e[t]()
                    }
                })
            }, l._clearMenus = function (e) {
                if (!e || 3 !== e.which && ("keyup" !== e.type || 9 === e.which))
                    for (var t = Te.makeArray(Te(qe)), n = 0; n < t.length; n++) {
                        var i = l._getParentFromElement(t[n]),
                            r = Te(t[n]).data(Ce),
                            o = {
                                relatedTarget: t[n]
                            };
                        if (r) {
                            var s = r._menu;
                            if (Te(i).hasClass(Oe) && !(e && ("click" === e.type && /input|textarea/i.test(e.target.tagName) || "keyup" === e.type && 9 === e.which) && Te.contains(i, e.target))) {
                                var a = Te.Event(ke.HIDE, o);
                                Te(i).trigger(a), a.isDefaultPrevented() || ("ontouchstart" in document.documentElement && Te(document.body).children().off("mouseover", null, Te.noop), t[n].setAttribute("aria-expanded", "false"), Te(s).removeClass(Oe), Te(i).removeClass(Oe).trigger(Te.Event(ke.HIDDEN, o)))
                            }
                        }
                    }
            }, l._getParentFromElement = function (e) {
                var t, n = Bn.getSelectorFromElement(e);
                return n && (t = Te(n)[0]), t || e.parentNode
            }, l._dataApiKeydownHandler = function (e) {
                if ((/input|textarea/i.test(e.target.tagName) ? !(32 === e.which || 27 !== e.which && (40 !== e.which && 38 !== e.which || Te(e.target).closest(Me).length)) : Ne.test(e.which)) && (e.preventDefault(), e.stopPropagation(), !this.disabled && !Te(this).hasClass(Ie))) {
                    var t = l._getParentFromElement(this),
                        n = Te(t).hasClass(Oe);
                    if ((n || 27 === e.which && 32 === e.which) && (!n || 27 !== e.which && 32 !== e.which)) {
                        var i = Te(t).find(Be).get();
                        if (0 !== i.length) {
                            var r = i.indexOf(e.target);
                            38 === e.which && 0 < r && r--, 40 === e.which && r < i.length - 1 && r++, r < 0 && (r = 0), i[r].focus()
                        }
                    } else {
                        if (27 === e.which) {
                            var o = Te(t).find(qe)[0];
                            Te(o).trigger("focus")
                        }
                        Te(this).trigger("click")
                    }
                }
            }, s(l, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return ze
                }
            }, {
                key: "DefaultType",
                get: function () {
                    return Xe
                }
            }]), l
        }(), Te(document).on(ke.KEYDOWN_DATA_API, qe, Ge._dataApiKeydownHandler).on(ke.KEYDOWN_DATA_API, Me, Ge._dataApiKeydownHandler).on(ke.CLICK_DATA_API + " " + ke.KEYUP_DATA_API, Ge._clearMenus).on(ke.CLICK_DATA_API, qe, function (e) {
            e.preventDefault(), e.stopPropagation(), Ge._jQueryInterface.call(Te(this), "toggle")
        }).on(ke.CLICK_DATA_API, We, function (e) {
            e.stopPropagation()
        }), Te.fn[xe] = Ge._jQueryInterface, Te.fn[xe].Constructor = Ge, Te.fn[xe].noConflict = function () {
            return Te.fn[xe] = Ae, Ge._jQueryInterface
        }, Ge),
        Yn = (Ze = "modal", tt = "." + (et = "bs.modal"), nt = (Je = t).fn[Ze], it = {
            backdrop: !0,
            keyboard: !0,
            focus: !0,
            show: !0
        }, rt = {
            backdrop: "(boolean|string)",
            keyboard: "boolean",
            focus: "boolean",
            show: "boolean"
        }, ot = {
            HIDE: "hide" + tt,
            HIDDEN: "hidden" + tt,
            SHOW: "show" + tt,
            SHOWN: "shown" + tt,
            FOCUSIN: "focusin" + tt,
            RESIZE: "resize" + tt,
            CLICK_DISMISS: "click.dismiss" + tt,
            KEYDOWN_DISMISS: "keydown.dismiss" + tt,
            MOUSEUP_DISMISS: "mouseup.dismiss" + tt,
            MOUSEDOWN_DISMISS: "mousedown.dismiss" + tt,
            CLICK_DATA_API: "click" + tt + ".data-api"
        }, st = "modal-scrollbar-measure", at = "modal-backdrop", lt = "modal-open", ut = "fade", ct = "show", ft = ".modal-dialog", dt = '[data-toggle="modal"]', ht = '[data-dismiss="modal"]', pt = ".fixed-top, .fixed-bottom, .is-fixed, .sticky-top", gt = ".sticky-top", mt = ".navbar-toggler", vt = function () {
            function r(e, t) {
                this._config = this._getConfig(t), this._element = e, this._dialog = Je(e).find(ft)[0], this._backdrop = null, this._isShown = !1, this._isBodyOverflowing = !1, this._ignoreBackdropClick = !1, this._scrollbarWidth = 0
            }
            var e = r.prototype;
            return e.toggle = function (e) {
                return this._isShown ? this.hide() : this.show(e)
            }, e.show = function (e) {
                var t = this;
                if (!this._isTransitioning && !this._isShown) {
                    Je(this._element).hasClass(ut) && (this._isTransitioning = !0);
                    var n = Je.Event(ot.SHOW, {
                        relatedTarget: e
                    });
                    Je(this._element).trigger(n), this._isShown || n.isDefaultPrevented() || (this._isShown = !0, this._checkScrollbar(), this._setScrollbar(), this._adjustDialog(), Je(document.body).addClass(lt), this._setEscapeEvent(), this._setResizeEvent(), Je(this._element).on(ot.CLICK_DISMISS, ht, function (e) {
                        return t.hide(e)
                    }), Je(this._dialog).on(ot.MOUSEDOWN_DISMISS, function () {
                        Je(t._element).one(ot.MOUSEUP_DISMISS, function (e) {
                            Je(e.target).is(t._element) && (t._ignoreBackdropClick = !0)
                        })
                    }), this._showBackdrop(function () {
                        return t._showElement(e)
                    }))
                }
            }, e.hide = function (e) {
                var t = this;
                if (e && e.preventDefault(), !this._isTransitioning && this._isShown) {
                    var n = Je.Event(ot.HIDE);
                    if (Je(this._element).trigger(n), this._isShown && !n.isDefaultPrevented()) {
                        this._isShown = !1;
                        var i = Je(this._element).hasClass(ut);
                        if (i && (this._isTransitioning = !0), this._setEscapeEvent(), this._setResizeEvent(), Je(document).off(ot.FOCUSIN), Je(this._element).removeClass(ct), Je(this._element).off(ot.CLICK_DISMISS), Je(this._dialog).off(ot.MOUSEDOWN_DISMISS), i) {
                            var r = Bn.getTransitionDurationFromElement(this._element);
                            Je(this._element).one(Bn.TRANSITION_END, function (e) {
                                return t._hideModal(e)
                            }).emulateTransitionEnd(r)
                        } else this._hideModal()
                    }
                }
            }, e.dispose = function () {
                Je.removeData(this._element, et), Je(window, document, this._element, this._backdrop).off(tt), this._config = null, this._element = null, this._dialog = null, this._backdrop = null, this._isShown = null, this._isBodyOverflowing = null, this._ignoreBackdropClick = null, this._scrollbarWidth = null
            }, e.handleUpdate = function () {
                this._adjustDialog()
            }, e._getConfig = function (e) {
                return e = u({}, it, e), Bn.typeCheckConfig(Ze, e, rt), e
            }, e._showElement = function (e) {
                var t = this,
                    n = Je(this._element).hasClass(ut);
                this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE || document.body.appendChild(this._element), this._element.style.display = "block", this._element.removeAttribute("aria-hidden"), this._element.scrollTop = 0, n && Bn.reflow(this._element), Je(this._element).addClass(ct), this._config.focus && this._enforceFocus();
                var i = Je.Event(ot.SHOWN, {
                        relatedTarget: e
                    }),
                    r = function () {
                        t._config.focus && t._element.focus(), t._isTransitioning = !1, Je(t._element).trigger(i)
                    };
                if (n) {
                    var o = Bn.getTransitionDurationFromElement(this._element);
                    Je(this._dialog).one(Bn.TRANSITION_END, r).emulateTransitionEnd(o)
                } else r()
            }, e._enforceFocus = function () {
                var t = this;
                Je(document).off(ot.FOCUSIN).on(ot.FOCUSIN, function (e) {
                    document !== e.target && t._element !== e.target && 0 === Je(t._element).has(e.target).length && t._element.focus()
                })
            }, e._setEscapeEvent = function () {
                var t = this;
                this._isShown && this._config.keyboard ? Je(this._element).on(ot.KEYDOWN_DISMISS, function (e) {
                    27 === e.which && (e.preventDefault(), t.hide())
                }) : this._isShown || Je(this._element).off(ot.KEYDOWN_DISMISS)
            }, e._setResizeEvent = function () {
                var t = this;
                this._isShown ? Je(window).on(ot.RESIZE, function (e) {
                    return t.handleUpdate(e)
                }) : Je(window).off(ot.RESIZE)
            }, e._hideModal = function () {
                var e = this;
                this._element.style.display = "none", this._element.setAttribute("aria-hidden", !0), this._isTransitioning = !1, this._showBackdrop(function () {
                    Je(document.body).removeClass(lt), e._resetAdjustments(), e._resetScrollbar(), Je(e._element).trigger(ot.HIDDEN)
                })
            }, e._removeBackdrop = function () {
                this._backdrop && (Je(this._backdrop).remove(), this._backdrop = null)
            }, e._showBackdrop = function (e) {
                var t = this,
                    n = Je(this._element).hasClass(ut) ? ut : "";
                if (this._isShown && this._config.backdrop) {
                    if (this._backdrop = document.createElement("div"), this._backdrop.className = at, n && Je(this._backdrop).addClass(n), Je(this._backdrop).appendTo(document.body), Je(this._element).on(ot.CLICK_DISMISS, function (e) {
                            t._ignoreBackdropClick ? t._ignoreBackdropClick = !1 : e.target === e.currentTarget && ("static" === t._config.backdrop ? t._element.focus() : t.hide())
                        }), n && Bn.reflow(this._backdrop), Je(this._backdrop).addClass(ct), !e) return;
                    if (!n) return void e();
                    var i = Bn.getTransitionDurationFromElement(this._backdrop);
                    Je(this._backdrop).one(Bn.TRANSITION_END, e).emulateTransitionEnd(i)
                } else if (!this._isShown && this._backdrop) {
                    Je(this._backdrop).removeClass(ct);
                    var r = function () {
                        t._removeBackdrop(), e && e()
                    };
                    if (Je(this._element).hasClass(ut)) {
                        var o = Bn.getTransitionDurationFromElement(this._backdrop);
                        Je(this._backdrop).one(Bn.TRANSITION_END, r).emulateTransitionEnd(o)
                    } else r()
                } else e && e()
            }, e._adjustDialog = function () {
                var e = this._element.scrollHeight > document.documentElement.clientHeight;
                !this._isBodyOverflowing && e && (this._element.style.paddingLeft = this._scrollbarWidth + "px"), this._isBodyOverflowing && !e && (this._element.style.paddingRight = this._scrollbarWidth + "px")
            }, e._resetAdjustments = function () {
                this._element.style.paddingLeft = "", this._element.style.paddingRight = ""
            }, e._checkScrollbar = function () {
                var e = document.body.getBoundingClientRect();
                this._isBodyOverflowing = e.left + e.right < window.innerWidth, this._scrollbarWidth = this._getScrollbarWidth()
            }, e._setScrollbar = function () {
                var r = this;
                if (this._isBodyOverflowing) {
                    Je(pt).each(function (e, t) {
                        var n = Je(t)[0].style.paddingRight,
                            i = Je(t).css("padding-right");
                        Je(t).data("padding-right", n).css("padding-right", parseFloat(i) + r._scrollbarWidth + "px")
                    }), Je(gt).each(function (e, t) {
                        var n = Je(t)[0].style.marginRight,
                            i = Je(t).css("margin-right");
                        Je(t).data("margin-right", n).css("margin-right", parseFloat(i) - r._scrollbarWidth + "px")
                    }), Je(mt).each(function (e, t) {
                        var n = Je(t)[0].style.marginRight,
                            i = Je(t).css("margin-right");
                        Je(t).data("margin-right", n).css("margin-right", parseFloat(i) + r._scrollbarWidth + "px")
                    });
                    var e = document.body.style.paddingRight,
                        t = Je(document.body).css("padding-right");
                    Je(document.body).data("padding-right", e).css("padding-right", parseFloat(t) + this._scrollbarWidth + "px")
                }
            }, e._resetScrollbar = function () {
                Je(pt).each(function (e, t) {
                    var n = Je(t).data("padding-right");
                    void 0 !== n && Je(t).css("padding-right", n).removeData("padding-right")
                }), Je(gt + ", " + mt).each(function (e, t) {
                    var n = Je(t).data("margin-right");
                    void 0 !== n && Je(t).css("margin-right", n).removeData("margin-right")
                });
                var e = Je(document.body).data("padding-right");
                void 0 !== e && Je(document.body).css("padding-right", e).removeData("padding-right")
            }, e._getScrollbarWidth = function () {
                var e = document.createElement("div");
                e.className = st, document.body.appendChild(e);
                var t = e.getBoundingClientRect().width - e.clientWidth;
                return document.body.removeChild(e), t
            }, r._jQueryInterface = function (n, i) {
                return this.each(function () {
                    var e = Je(this).data(et),
                        t = u({}, it, Je(this).data(), "object" == typeof n && n ? n : {});
                    if (e || (e = new r(this, t), Je(this).data(et, e)), "string" == typeof n) {
                        if (void 0 === e[n]) throw new TypeError('No method named "' + n + '"');
                        e[n](i)
                    } else t.show && e.show(i)
                })
            }, s(r, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return it
                }
            }]), r
        }(), Je(document).on(ot.CLICK_DATA_API, dt, function (e) {
            var t, n = this,
                i = Bn.getSelectorFromElement(this);
            i && (t = Je(i)[0]);
            var r = Je(t).data(et) ? "toggle" : u({}, Je(t).data(), Je(this).data());
            "A" !== this.tagName && "AREA" !== this.tagName || e.preventDefault();
            var o = Je(t).one(ot.SHOW, function (e) {
                e.isDefaultPrevented() || o.one(ot.HIDDEN, function () {
                    Je(n).is(":visible") && n.focus()
                })
            });
            vt._jQueryInterface.call(Je(t), r, this)
        }), Je.fn[Ze] = vt._jQueryInterface, Je.fn[Ze].Constructor = vt, Je.fn[Ze].noConflict = function () {
            return Je.fn[Ze] = nt, vt._jQueryInterface
        }, vt),
        zn = (_t = "tooltip", wt = "." + (bt = "bs.tooltip"), Et = (yt = t).fn[_t], Tt = "bs-tooltip", xt = new RegExp("(^|\\s)" + Tt + "\\S+", "g"), St = {
            animation: !0,
            template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>',
            trigger: "hover focus",
            title: "",
            delay: 0,
            html: !(Dt = {
                AUTO: "auto",
                TOP: "top",
                RIGHT: "right",
                BOTTOM: "bottom",
                LEFT: "left"
            }),
            selector: !(Ct = {
                animation: "boolean",
                template: "string",
                title: "(string|element|function)",
                trigger: "string",
                delay: "(number|object)",
                html: "boolean",
                selector: "(string|boolean)",
                placement: "(string|function)",
                offset: "(number|string)",
                container: "(string|element|boolean)",
                fallbackPlacement: "(string|array)",
                boundary: "(string|element)"
            }),
            placement: "top",
            offset: 0,
            container: !1,
            fallbackPlacement: "flip",
            boundary: "scrollParent"
        }, Nt = "out", kt = {
            HIDE: "hide" + wt,
            HIDDEN: "hidden" + wt,
            SHOW: (At = "show") + wt,
            SHOWN: "shown" + wt,
            INSERTED: "inserted" + wt,
            CLICK: "click" + wt,
            FOCUSIN: "focusin" + wt,
            FOCUSOUT: "focusout" + wt,
            MOUSEENTER: "mouseenter" + wt,
            MOUSELEAVE: "mouseleave" + wt
        }, It = "fade", Ot = "show", jt = ".tooltip-inner", Lt = ".arrow", Pt = "hover", Ht = "focus", Rt = "click", qt = "manual", Wt = function () {
            function i(e, t) {
                if (void 0 === c) throw new TypeError("Bootstrap tooltips require Popper.js (https://popper.js.org)");
                this._isEnabled = !0, this._timeout = 0, this._hoverState = "", this._activeTrigger = {}, this._popper = null, this.element = e, this.config = this._getConfig(t), this.tip = null, this._setListeners()
            }
            var e = i.prototype;
            return e.enable = function () {
                this._isEnabled = !0
            }, e.disable = function () {
                this._isEnabled = !1
            }, e.toggleEnabled = function () {
                this._isEnabled = !this._isEnabled
            }, e.toggle = function (e) {
                if (this._isEnabled)
                    if (e) {
                        var t = this.constructor.DATA_KEY,
                            n = yt(e.currentTarget).data(t);
                        n || (n = new this.constructor(e.currentTarget, this._getDelegateConfig()), yt(e.currentTarget).data(t, n)), n._activeTrigger.click = !n._activeTrigger.click, n._isWithActiveTrigger() ? n._enter(null, n) : n._leave(null, n)
                    } else {
                        if (yt(this.getTipElement()).hasClass(Ot)) return void this._leave(null, this);
                        this._enter(null, this)
                    }
            }, e.dispose = function () {
                clearTimeout(this._timeout), yt.removeData(this.element, this.constructor.DATA_KEY), yt(this.element).off(this.constructor.EVENT_KEY), yt(this.element).closest(".modal").off("hide.bs.modal"), this.tip && yt(this.tip).remove(), this._isEnabled = null, this._timeout = null, this._hoverState = null, (this._activeTrigger = null) !== this._popper && this._popper.destroy(), this._popper = null, this.element = null, this.config = null, this.tip = null
            }, e.show = function () {
                var t = this;
                if ("none" === yt(this.element).css("display")) throw new Error("Please use show on visible elements");
                var e = yt.Event(this.constructor.Event.SHOW);
                if (this.isWithContent() && this._isEnabled) {
                    yt(this.element).trigger(e);
                    var n = yt.contains(this.element.ownerDocument.documentElement, this.element);
                    if (e.isDefaultPrevented() || !n) return;
                    var i = this.getTipElement(),
                        r = Bn.getUID(this.constructor.NAME);
                    i.setAttribute("id", r), this.element.setAttribute("aria-describedby", r), this.setContent(), this.config.animation && yt(i).addClass(It);
                    var o = "function" == typeof this.config.placement ? this.config.placement.call(this, i, this.element) : this.config.placement,
                        s = this._getAttachment(o);
                    this.addAttachmentClass(s);
                    var a = !1 === this.config.container ? document.body : yt(this.config.container);
                    yt(i).data(this.constructor.DATA_KEY, this), yt.contains(this.element.ownerDocument.documentElement, this.tip) || yt(i).appendTo(a), yt(this.element).trigger(this.constructor.Event.INSERTED), this._popper = new c(this.element, i, {
                        placement: s,
                        modifiers: {
                            offset: {
                                offset: this.config.offset
                            },
                            flip: {
                                behavior: this.config.fallbackPlacement
                            },
                            arrow: {
                                element: Lt
                            },
                            preventOverflow: {
                                boundariesElement: this.config.boundary
                            }
                        },
                        onCreate: function (e) {
                            e.originalPlacement !== e.placement && t._handlePopperPlacementChange(e)
                        },
                        onUpdate: function (e) {
                            t._handlePopperPlacementChange(e)
                        }
                    }), yt(i).addClass(Ot), "ontouchstart" in document.documentElement && yt(document.body).children().on("mouseover", null, yt.noop);
                    var l = function () {
                        t.config.animation && t._fixTransition();
                        var e = t._hoverState;
                        t._hoverState = null, yt(t.element).trigger(t.constructor.Event.SHOWN), e === Nt && t._leave(null, t)
                    };
                    if (yt(this.tip).hasClass(It)) {
                        var u = Bn.getTransitionDurationFromElement(this.tip);
                        yt(this.tip).one(Bn.TRANSITION_END, l).emulateTransitionEnd(u)
                    } else l()
                }
            }, e.hide = function (e) {
                var t = this,
                    n = this.getTipElement(),
                    i = yt.Event(this.constructor.Event.HIDE),
                    r = function () {
                        t._hoverState !== At && n.parentNode && n.parentNode.removeChild(n), t._cleanTipClass(), t.element.removeAttribute("aria-describedby"), yt(t.element).trigger(t.constructor.Event.HIDDEN), null !== t._popper && t._popper.destroy(), e && e()
                    };
                if (yt(this.element).trigger(i), !i.isDefaultPrevented()) {
                    if (yt(n).removeClass(Ot), "ontouchstart" in document.documentElement && yt(document.body).children().off("mouseover", null, yt.noop), this._activeTrigger[Rt] = !1, this._activeTrigger[Ht] = !1, this._activeTrigger[Pt] = !1, yt(this.tip).hasClass(It)) {
                        var o = Bn.getTransitionDurationFromElement(n);
                        yt(n).one(Bn.TRANSITION_END, r).emulateTransitionEnd(o)
                    } else r();
                    this._hoverState = ""
                }
            }, e.update = function () {
                null !== this._popper && this._popper.scheduleUpdate()
            }, e.isWithContent = function () {
                return Boolean(this.getTitle())
            }, e.addAttachmentClass = function (e) {
                yt(this.getTipElement()).addClass(Tt + "-" + e)
            }, e.getTipElement = function () {
                return this.tip = this.tip || yt(this.config.template)[0], this.tip
            }, e.setContent = function () {
                var e = yt(this.getTipElement());
                this.setElementContent(e.find(jt), this.getTitle()), e.removeClass(It + " " + Ot)
            }, e.setElementContent = function (e, t) {
                var n = this.config.html;
                "object" == typeof t && (t.nodeType || t.jquery) ? n ? yt(t).parent().is(e) || e.empty().append(t) : e.text(yt(t).text()) : e[n ? "html" : "text"](t)
            }, e.getTitle = function () {
                var e = this.element.getAttribute("data-original-title");
                return e || (e = "function" == typeof this.config.title ? this.config.title.call(this.element) : this.config.title), e
            }, e._getAttachment = function (e) {
                return Dt[e.toUpperCase()]
            }, e._setListeners = function () {
                var i = this;
                this.config.trigger.split(" ").forEach(function (e) {
                    if ("click" === e) yt(i.element).on(i.constructor.Event.CLICK, i.config.selector, function (e) {
                        return i.toggle(e)
                    });
                    else if (e !== qt) {
                        var t = e === Pt ? i.constructor.Event.MOUSEENTER : i.constructor.Event.FOCUSIN,
                            n = e === Pt ? i.constructor.Event.MOUSELEAVE : i.constructor.Event.FOCUSOUT;
                        yt(i.element).on(t, i.config.selector, function (e) {
                            return i._enter(e)
                        }).on(n, i.config.selector, function (e) {
                            return i._leave(e)
                        })
                    }
                    yt(i.element).closest(".modal").on("hide.bs.modal", function () {
                        return i.hide()
                    })
                }), this.config.selector ? this.config = u({}, this.config, {
                    trigger: "manual",
                    selector: ""
                }) : this._fixTitle()
            }, e._fixTitle = function () {
                var e = typeof this.element.getAttribute("data-original-title");
                (this.element.getAttribute("title") || "string" !== e) && (this.element.setAttribute("data-original-title", this.element.getAttribute("title") || ""), this.element.setAttribute("title", ""))
            }, e._enter = function (e, t) {
                var n = this.constructor.DATA_KEY;
                (t = t || yt(e.currentTarget).data(n)) || (t = new this.constructor(e.currentTarget, this._getDelegateConfig()), yt(e.currentTarget).data(n, t)), e && (t._activeTrigger["focusin" === e.type ? Ht : Pt] = !0), yt(t.getTipElement()).hasClass(Ot) || t._hoverState === At ? t._hoverState = At : (clearTimeout(t._timeout), t._hoverState = At, t.config.delay && t.config.delay.show ? t._timeout = setTimeout(function () {
                    t._hoverState === At && t.show()
                }, t.config.delay.show) : t.show())
            }, e._leave = function (e, t) {
                var n = this.constructor.DATA_KEY;
                (t = t || yt(e.currentTarget).data(n)) || (t = new this.constructor(e.currentTarget, this._getDelegateConfig()), yt(e.currentTarget).data(n, t)), e && (t._activeTrigger["focusout" === e.type ? Ht : Pt] = !1), t._isWithActiveTrigger() || (clearTimeout(t._timeout), t._hoverState = Nt, t.config.delay && t.config.delay.hide ? t._timeout = setTimeout(function () {
                    t._hoverState === Nt && t.hide()
                }, t.config.delay.hide) : t.hide())
            }, e._isWithActiveTrigger = function () {
                for (var e in this._activeTrigger)
                    if (this._activeTrigger[e]) return !0;
                return !1
            }, e._getConfig = function (e) {
                return "number" == typeof (e = u({}, this.constructor.Default, yt(this.element).data(), "object" == typeof e && e ? e : {})).delay && (e.delay = {
                    show: e.delay,
                    hide: e.delay
                }), "number" == typeof e.title && (e.title = e.title.toString()), "number" == typeof e.content && (e.content = e.content.toString()), Bn.typeCheckConfig(_t, e, this.constructor.DefaultType), e
            }, e._getDelegateConfig = function () {
                var e = {};
                if (this.config)
                    for (var t in this.config) this.constructor.Default[t] !== this.config[t] && (e[t] = this.config[t]);
                return e
            }, e._cleanTipClass = function () {
                var e = yt(this.getTipElement()),
                    t = e.attr("class").match(xt);
                null !== t && 0 < t.length && e.removeClass(t.join(""))
            }, e._handlePopperPlacementChange = function (e) {
                this._cleanTipClass(), this.addAttachmentClass(this._getAttachment(e.placement))
            }, e._fixTransition = function () {
                var e = this.getTipElement(),
                    t = this.config.animation;
                null === e.getAttribute("x-placement") && (yt(e).removeClass(It), this.config.animation = !1, this.hide(), this.show(), this.config.animation = t)
            }, i._jQueryInterface = function (n) {
                return this.each(function () {
                    var e = yt(this).data(bt),
                        t = "object" == typeof n && n;
                    if ((e || !/dispose|hide/.test(n)) && (e || (e = new i(this, t), yt(this).data(bt, e)), "string" == typeof n)) {
                        if (void 0 === e[n]) throw new TypeError('No method named "' + n + '"');
                        e[n]()
                    }
                })
            }, s(i, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return St
                }
            }, {
                key: "NAME",
                get: function () {
                    return _t
                }
            }, {
                key: "DATA_KEY",
                get: function () {
                    return bt
                }
            }, {
                key: "Event",
                get: function () {
                    return kt
                }
            }, {
                key: "EVENT_KEY",
                get: function () {
                    return wt
                }
            }, {
                key: "DefaultType",
                get: function () {
                    return Ct
                }
            }]), i
        }(), yt.fn[_t] = Wt._jQueryInterface, yt.fn[_t].Constructor = Wt, yt.fn[_t].noConflict = function () {
            return yt.fn[_t] = Et, Wt._jQueryInterface
        }, Wt),
        Xn = (Ft = "popover", Ut = "." + (Bt = "bs.popover"), Kt = (Mt = t).fn[Ft], $t = "bs-popover", Qt = new RegExp("(^|\\s)" + $t + "\\S+", "g"), Vt = u({}, zn.Default, {
            placement: "right",
            trigger: "click",
            content: "",
            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
        }), Yt = u({}, zn.DefaultType, {
            content: "(string|element|function)"
        }), zt = "fade", Gt = ".popover-header", Jt = ".popover-body", Zt = {
            HIDE: "hide" + Ut,
            HIDDEN: "hidden" + Ut,
            SHOW: (Xt = "show") + Ut,
            SHOWN: "shown" + Ut,
            INSERTED: "inserted" + Ut,
            CLICK: "click" + Ut,
            FOCUSIN: "focusin" + Ut,
            FOCUSOUT: "focusout" + Ut,
            MOUSEENTER: "mouseenter" + Ut,
            MOUSELEAVE: "mouseleave" + Ut
        }, en = function (e) {
            var t, n;

            function i() {
                return e.apply(this, arguments) || this
            }
            n = e, (t = i).prototype = Object.create(n.prototype), (t.prototype.constructor = t).__proto__ = n;
            var r = i.prototype;
            return r.isWithContent = function () {
                return this.getTitle() || this._getContent()
            }, r.addAttachmentClass = function (e) {
                Mt(this.getTipElement()).addClass($t + "-" + e)
            }, r.getTipElement = function () {
                return this.tip = this.tip || Mt(this.config.template)[0], this.tip
            }, r.setContent = function () {
                var e = Mt(this.getTipElement());
                this.setElementContent(e.find(Gt), this.getTitle());
                var t = this._getContent();
                "function" == typeof t && (t = t.call(this.element)), this.setElementContent(e.find(Jt), t), e.removeClass(zt + " " + Xt)
            }, r._getContent = function () {
                return this.element.getAttribute("data-content") || this.config.content
            }, r._cleanTipClass = function () {
                var e = Mt(this.getTipElement()),
                    t = e.attr("class").match(Qt);
                null !== t && 0 < t.length && e.removeClass(t.join(""))
            }, i._jQueryInterface = function (n) {
                return this.each(function () {
                    var e = Mt(this).data(Bt),
                        t = "object" == typeof n ? n : null;
                    if ((e || !/destroy|hide/.test(n)) && (e || (e = new i(this, t), Mt(this).data(Bt, e)), "string" == typeof n)) {
                        if (void 0 === e[n]) throw new TypeError('No method named "' + n + '"');
                        e[n]()
                    }
                })
            }, s(i, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return Vt
                }
            }, {
                key: "NAME",
                get: function () {
                    return Ft
                }
            }, {
                key: "DATA_KEY",
                get: function () {
                    return Bt
                }
            }, {
                key: "Event",
                get: function () {
                    return Zt
                }
            }, {
                key: "EVENT_KEY",
                get: function () {
                    return Ut
                }
            }, {
                key: "DefaultType",
                get: function () {
                    return Yt
                }
            }]), i
        }(zn), Mt.fn[Ft] = en._jQueryInterface, Mt.fn[Ft].Constructor = en, Mt.fn[Ft].noConflict = function () {
            return Mt.fn[Ft] = Kt, en._jQueryInterface
        }, en),
        Gn = (nn = "scrollspy", on = "." + (rn = "bs.scrollspy"), sn = (tn = t).fn[nn], an = {
            offset: 10,
            method: "auto",
            target: ""
        }, ln = {
            offset: "number",
            method: "string",
            target: "(string|element)"
        }, un = {
            ACTIVATE: "activate" + on,
            SCROLL: "scroll" + on,
            LOAD_DATA_API: "load" + on + ".data-api"
        }, cn = "dropdown-item", fn = "active", dn = '[data-spy="scroll"]', hn = ".active", pn = ".nav, .list-group", gn = ".nav-link", mn = ".nav-item", vn = ".list-group-item", yn = ".dropdown", _n = ".dropdown-item", bn = ".dropdown-toggle", wn = "offset", En = "position", Tn = function () {
            function n(e, t) {
                var n = this;
                this._element = e, this._scrollElement = "BODY" === e.tagName ? window : e, this._config = this._getConfig(t), this._selector = this._config.target + " " + gn + "," + this._config.target + " " + vn + "," + this._config.target + " " + _n, this._offsets = [], this._targets = [], this._activeTarget = null, this._scrollHeight = 0, tn(this._scrollElement).on(un.SCROLL, function (e) {
                    return n._process(e)
                }), this.refresh(), this._process()
            }
            var e = n.prototype;
            return e.refresh = function () {
                var t = this,
                    e = this._scrollElement === this._scrollElement.window ? wn : En,
                    r = "auto" === this._config.method ? e : this._config.method,
                    o = r === En ? this._getScrollTop() : 0;
                this._offsets = [], this._targets = [], this._scrollHeight = this._getScrollHeight(), tn.makeArray(tn(this._selector)).map(function (e) {
                    var t, n = Bn.getSelectorFromElement(e);
                    if (n && (t = tn(n)[0]), t) {
                        var i = t.getBoundingClientRect();
                        if (i.width || i.height) return [tn(t)[r]().top + o, n]
                    }
                    return null
                }).filter(function (e) {
                    return e
                }).sort(function (e, t) {
                    return e[0] - t[0]
                }).forEach(function (e) {
                    t._offsets.push(e[0]), t._targets.push(e[1])
                })
            }, e.dispose = function () {
                tn.removeData(this._element, rn), tn(this._scrollElement).off(on), this._element = null, this._scrollElement = null, this._config = null, this._selector = null, this._offsets = null, this._targets = null, this._activeTarget = null, this._scrollHeight = null
            }, e._getConfig = function (e) {
                if ("string" != typeof (e = u({}, an, "object" == typeof e && e ? e : {})).target) {
                    var t = tn(e.target).attr("id");
                    t || (t = Bn.getUID(nn), tn(e.target).attr("id", t)), e.target = "#" + t
                }
                return Bn.typeCheckConfig(nn, e, ln), e
            }, e._getScrollTop = function () {
                return this._scrollElement === window ? this._scrollElement.pageYOffset : this._scrollElement.scrollTop
            }, e._getScrollHeight = function () {
                return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
            }, e._getOffsetHeight = function () {
                return this._scrollElement === window ? window.innerHeight : this._scrollElement.getBoundingClientRect().height
            }, e._process = function () {
                var e = this._getScrollTop() + this._config.offset,
                    t = this._getScrollHeight(),
                    n = this._config.offset + t - this._getOffsetHeight();
                if (this._scrollHeight !== t && this.refresh(), n <= e) {
                    var i = this._targets[this._targets.length - 1];
                    this._activeTarget !== i && this._activate(i)
                } else {
                    if (this._activeTarget && e < this._offsets[0] && 0 < this._offsets[0]) return this._activeTarget = null, void this._clear();
                    for (var r = this._offsets.length; r--;) {
                        this._activeTarget !== this._targets[r] && e >= this._offsets[r] && (void 0 === this._offsets[r + 1] || e < this._offsets[r + 1]) && this._activate(this._targets[r])
                    }
                }
            }, e._activate = function (t) {
                this._activeTarget = t, this._clear();
                var e = this._selector.split(",");
                e = e.map(function (e) {
                    return e + '[data-target="' + t + '"],' + e + '[href="' + t + '"]'
                });
                var n = tn(e.join(","));
                n.hasClass(cn) ? (n.closest(yn).find(bn).addClass(fn), n.addClass(fn)) : (n.addClass(fn), n.parents(pn).prev(gn + ", " + vn).addClass(fn), n.parents(pn).prev(mn).children(gn).addClass(fn)), tn(this._scrollElement).trigger(un.ACTIVATE, {
                    relatedTarget: t
                })
            }, e._clear = function () {
                tn(this._selector).filter(hn).removeClass(fn)
            }, n._jQueryInterface = function (t) {
                return this.each(function () {
                    var e = tn(this).data(rn);
                    if (e || (e = new n(this, "object" == typeof t && t), tn(this).data(rn, e)), "string" == typeof t) {
                        if (void 0 === e[t]) throw new TypeError('No method named "' + t + '"');
                        e[t]()
                    }
                })
            }, s(n, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }, {
                key: "Default",
                get: function () {
                    return an
                }
            }]), n
        }(), tn(window).on(un.LOAD_DATA_API, function () {
            for (var e = tn.makeArray(tn(dn)), t = e.length; t--;) {
                var n = tn(e[t]);
                Tn._jQueryInterface.call(n, n.data())
            }
        }), tn.fn[nn] = Tn._jQueryInterface, tn.fn[nn].Constructor = Tn, tn.fn[nn].noConflict = function () {
            return tn.fn[nn] = sn, Tn._jQueryInterface
        }, Tn),
        Jn = (Dn = "." + (Cn = "bs.tab"), Sn = (xn = t).fn.tab, An = {
            HIDE: "hide" + Dn,
            HIDDEN: "hidden" + Dn,
            SHOW: "show" + Dn,
            SHOWN: "shown" + Dn,
            CLICK_DATA_API: "click" + Dn + ".data-api"
        }, Nn = "dropdown-menu", kn = "active", In = "disabled", On = "fade", jn = "show", Ln = ".dropdown", Pn = ".nav, .list-group", Hn = ".active", Rn = "> li > .active", qn = '[data-toggle="tab"], [data-toggle="pill"], [data-toggle="list"]', Wn = ".dropdown-toggle", Mn = "> .dropdown-menu .active", Fn = function () {
            function i(e) {
                this._element = e
            }
            var e = i.prototype;
            return e.show = function () {
                var n = this;
                if (!(this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE && xn(this._element).hasClass(kn) || xn(this._element).hasClass(In))) {
                    var e, i, t = xn(this._element).closest(Pn)[0],
                        r = Bn.getSelectorFromElement(this._element);
                    if (t) {
                        var o = "UL" === t.nodeName ? Rn : Hn;
                        i = (i = xn.makeArray(xn(t).find(o)))[i.length - 1]
                    }
                    var s = xn.Event(An.HIDE, {
                            relatedTarget: this._element
                        }),
                        a = xn.Event(An.SHOW, {
                            relatedTarget: i
                        });
                    if (i && xn(i).trigger(s), xn(this._element).trigger(a), !a.isDefaultPrevented() && !s.isDefaultPrevented()) {
                        r && (e = xn(r)[0]), this._activate(this._element, t);
                        var l = function () {
                            var e = xn.Event(An.HIDDEN, {
                                    relatedTarget: n._element
                                }),
                                t = xn.Event(An.SHOWN, {
                                    relatedTarget: i
                                });
                            xn(i).trigger(e), xn(n._element).trigger(t)
                        };
                        e ? this._activate(e, e.parentNode, l) : l()
                    }
                }
            }, e.dispose = function () {
                xn.removeData(this._element, Cn), this._element = null
            }, e._activate = function (e, t, n) {
                var i = this,
                    r = ("UL" === t.nodeName ? xn(t).find(Rn) : xn(t).children(Hn))[0],
                    o = n && r && xn(r).hasClass(On),
                    s = function () {
                        return i._transitionComplete(e, r, n)
                    };
                if (r && o) {
                    var a = Bn.getTransitionDurationFromElement(r);
                    xn(r).one(Bn.TRANSITION_END, s).emulateTransitionEnd(a)
                } else s()
            }, e._transitionComplete = function (e, t, n) {
                if (t) {
                    xn(t).removeClass(jn + " " + kn);
                    var i = xn(t.parentNode).find(Mn)[0];
                    i && xn(i).removeClass(kn), "tab" === t.getAttribute("role") && t.setAttribute("aria-selected", !1)
                }
                if (xn(e).addClass(kn), "tab" === e.getAttribute("role") && e.setAttribute("aria-selected", !0), Bn.reflow(e), xn(e).addClass(jn), e.parentNode && xn(e.parentNode).hasClass(Nn)) {
                    var r = xn(e).closest(Ln)[0];
                    r && xn(r).find(Wn).addClass(kn), e.setAttribute("aria-expanded", !0)
                }
                n && n()
            }, i._jQueryInterface = function (n) {
                return this.each(function () {
                    var e = xn(this),
                        t = e.data(Cn);
                    if (t || (t = new i(this), e.data(Cn, t)), "string" == typeof n) {
                        if (void 0 === t[n]) throw new TypeError('No method named "' + n + '"');
                        t[n]()
                    }
                })
            }, s(i, null, [{
                key: "VERSION",
                get: function () {
                    return "4.1.1"
                }
            }]), i
        }(), xn(document).on(An.CLICK_DATA_API, qn, function (e) {
            e.preventDefault(), Fn._jQueryInterface.call(xn(this), "show")
        }), xn.fn.tab = Fn._jQueryInterface, xn.fn.tab.Constructor = Fn, xn.fn.tab.noConflict = function () {
            return xn.fn.tab = Sn, Fn._jQueryInterface
        }, Fn);
    ! function (e) {
        if (void 0 === e) throw new TypeError("Bootstrap's JavaScript requires jQuery. jQuery must be included before Bootstrap's JavaScript.");
        var t = e.fn.jquery.split(" ")[0].split(".");
        if (t[0] < 2 && t[1] < 9 || 1 === t[0] && 9 === t[1] && t[2] < 1 || 4 <= t[0]) throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")
    }(t), e.Util = Bn, e.Alert = Un, e.Button = Kn, e.Carousel = $n, e.Collapse = Qn, e.Dropdown = Vn, e.Modal = Yn, e.Popover = Xn, e.Scrollspy = Gn, e.Tab = Jn, e.Tooltip = zn, Object.defineProperty(e, "__esModule", {
        value: !0
    })
});
