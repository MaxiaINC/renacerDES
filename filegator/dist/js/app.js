var raiz=""; 

(function(e) { 
	
	
 	
    function a(a) {
        for (var n, r, s = a[0], l = a[1], d = a[2], u = 0, p = []; u < s.length; u++) r = s[u], Object.prototype.hasOwnProperty.call(i, r) && i[r] && p.push(i[r][0]), i[r] = 0;
        for (n in l) Object.prototype.hasOwnProperty.call(l, n) && (e[n] = l[n]);
        c && c(a);
        while (p.length) p.shift()();
        return t.push.apply(t, d || []), o()
    }

    function o() {
        for (var e, a = 0; a < t.length; a++) {
            for (var o = t[a], n = !0, s = 1; s < o.length; s++) {
                var l = o[s];
                0 !== i[l] && (n = !1)
            }
            n && (t.splice(a--, 1), e = r(r.s = o[0]))
        }
        return e
    }
    var n = {},
        i = {
            app: 0
        },
        t = [];

    function r(a) {
        if (n[a]) return n[a].exports;
        var o = n[a] = {
            i: a,
            l: !1,
            exports: {}
        };
        return e[a].call(o.exports, o, o.exports, r), o.l = !0, o.exports
    }
    r.m = e, r.c = n, r.d = function(e, a, o) {
        r.o(e, a) || Object.defineProperty(e, a, {
            enumerable: !0,
            get: o
        })
    }, r.r = function(e) {
        "undefined" !== typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }), Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }, r.t = function(e, a) {
        if (1 & a && (e = r(e)), 8 & a) return e;
        if (4 & a && "object" === typeof e && e && e.__esModule) return e;
        var o = Object.create(null);
        if (r.r(o), Object.defineProperty(o, "default", {
                enumerable: !0,
                value: e
            }), 2 & a && "string" != typeof e)
            for (var n in e) r.d(o, n, function(a) {
                return e[a]
            }.bind(null, n));
        return o
    }, r.n = function(e) {
        var a = e && e.__esModule ? function() {
            return e["default"]
        } : function() {
            return e
        };
        return r.d(a, "a", a), a
    }, r.o = function(e, a) {
        return Object.prototype.hasOwnProperty.call(e, a)
    }, r.p = "/";
    var s = window["webpackJsonp"] = window["webpackJsonp"] || [],
        l = s.push.bind(s);
    s.push = a, s = s.slice();
    for (var d = 0; d < s.length; d++) a(s[d]);
    var c = l;
    t.push([0, "chunk-vendors"]), o()
})({
    0: function(e, a, o) {
        e.exports = o("93b7")
    },
    "0b8c": function(e, a, o) {},
    "14a0": function(e, a, o) {
        "use strict";
        var n = o("23ed"),
            i = o.n(n);
        i.a
    },
    "23ed": function(e, a, o) {},
    "2b2b": function(e, a, o) {
        "use strict";
        var n = o("b6df"),
            i = o.n(n);
        i.a
    },
    "37b3": function(e, a, o) {},
    4290: function(e, a, o) {},
    4678: function(e, a, o) {
        var n = {
            "./af": "2bfb",
            "./af.js": "2bfb",
            "./ar": "8e73",
            "./ar-dz": "a356",
            "./ar-dz.js": "a356",
            "./ar-kw": "423e",
            "./ar-kw.js": "423e",
            "./ar-ly": "1cfd",
            "./ar-ly.js": "1cfd",
            "./ar-ma": "0a84",
            "./ar-ma.js": "0a84",
            "./ar-sa": "8230",
            "./ar-sa.js": "8230",
            "./ar-tn": "6d83",
            "./ar-tn.js": "6d83",
            "./ar.js": "8e73",
            "./az": "485c",
            "./az.js": "485c",
            "./be": "1fc1",
            "./be.js": "1fc1",
            "./bg": "84aa",
            "./bg.js": "84aa",
            "./bm": "a7fa",
            "./bm.js": "a7fa",
            "./bn": "9043",
            "./bn.js": "9043",
            "./bo": "d26a",
            "./bo.js": "d26a",
            "./br": "6887",
            "./br.js": "6887",
            "./bs": "2554",
            "./bs.js": "2554",
            "./ca": "d716",
            "./ca.js": "d716",
            "./cs": "3c0d",
            "./cs.js": "3c0d",
            "./cv": "03ec",
            "./cv.js": "03ec",
            "./cy": "9797",
            "./cy.js": "9797",
            "./da": "0f14",
            "./da.js": "0f14",
            "./de": "b469",
            "./de-at": "b3eb",
            "./de-at.js": "b3eb",
            "./de-ch": "bb71",
            "./de-ch.js": "bb71",
            "./de.js": "b469",
            "./dv": "598a",
            "./dv.js": "598a",
            "./el": "8d47",
            "./el.js": "8d47",
            "./en-SG": "cdab",
            "./en-SG.js": "cdab",
            "./en-au": "0e6b",
            "./en-au.js": "0e6b",
            "./en-ca": "3886",
            "./en-ca.js": "3886",
            "./en-gb": "39a6",
            "./en-gb.js": "39a6",
            "./en-ie": "e1d3",
            "./en-ie.js": "e1d3",
            "./en-il": "7333",
            "./en-il.js": "7333",
            "./en-nz": "6f50",
            "./en-nz.js": "6f50",
            "./eo": "65db",
            "./eo.js": "65db",
            "./es": "898b",
            "./es-do": "0a3c",
            "./es-do.js": "0a3c",
            "./es-us": "55c9",
            "./es-us.js": "55c9",
            "./es.js": "898b",
            "./et": "ec18",
            "./et.js": "ec18",
            "./eu": "0ff2",
            "./eu.js": "0ff2",
            "./fa": "8df4",
            "./fa.js": "8df4",
            "./fi": "81e9",
            "./fi.js": "81e9",
            "./fo": "0721",
            "./fo.js": "0721",
            "./fr": "9f26",
            "./fr-ca": "d9f8",
            "./fr-ca.js": "d9f8",
            "./fr-ch": "0e49",
            "./fr-ch.js": "0e49",
            "./fr.js": "9f26",
            "./fy": "7118",
            "./fy.js": "7118",
            "./ga": "5120",
            "./ga.js": "5120",
            "./gd": "f6b4",
            "./gd.js": "f6b4",
            "./gl": "8840",
            "./gl.js": "8840",
            "./gom-latn": "0caa",
            "./gom-latn.js": "0caa",
            "./gu": "e0c5",
            "./gu.js": "e0c5",
            "./he": "c7aa",
            "./he.js": "c7aa",
            "./hi": "dc4d",
            "./hi.js": "dc4d",
            "./hr": "4ba9",
            "./hr.js": "4ba9",
            "./hu": "5b14",
            "./hu.js": "5b14",
            "./hy-am": "d6b6",
            "./hy-am.js": "d6b6",
            "./id": "5038",
            "./id.js": "5038",
            "./is": "0558",
            "./is.js": "0558",
            "./it": "6e98",
            "./it-ch": "6f12",
            "./it-ch.js": "6f12",
            "./it.js": "6e98",
            "./ja": "079e",
            "./ja.js": "079e",
            "./jv": "b540",
            "./jv.js": "b540",
            "./ka": "201b",
            "./ka.js": "201b",
            "./kk": "6d79",
            "./kk.js": "6d79",
            "./km": "e81d",
            "./km.js": "e81d",
            "./kn": "3e92",
            "./kn.js": "3e92",
            "./ko": "22f8",
            "./ko.js": "22f8",
            "./ku": "2421",
            "./ku.js": "2421",
            "./ky": "9609",
            "./ky.js": "9609",
            "./lb": "440c",
            "./lb.js": "440c",
            "./lo": "b29d",
            "./lo.js": "b29d",
            "./lt": "26f9",
            "./lt.js": "26f9",
            "./lv": "b97c",
            "./lv.js": "b97c",
            "./me": "293c",
            "./me.js": "293c",
            "./mi": "688b",
            "./mi.js": "688b",
            "./mk": "6909",
            "./mk.js": "6909",
            "./ml": "02fb",
            "./ml.js": "02fb",
            "./mn": "958b",
            "./mn.js": "958b",
            "./mr": "39bd",
            "./mr.js": "39bd",
            "./ms": "ebe4",
            "./ms-my": "6403",
            "./ms-my.js": "6403",
            "./ms.js": "ebe4",
            "./mt": "1b45",
            "./mt.js": "1b45",
            "./my": "8689",
            "./my.js": "8689",
            "./nb": "6ce3",
            "./nb.js": "6ce3",
            "./ne": "3a39",
            "./ne.js": "3a39",
            "./nl": "facd",
            "./nl-be": "db29",
            "./nl-be.js": "db29",
            "./nl.js": "facd",
            "./nn": "b84c",
            "./nn.js": "b84c",
            "./pa-in": "f3ff",
            "./pa-in.js": "f3ff",
            "./pl": "8d57",
            "./pl.js": "8d57",
            "./pt": "f260",
            "./pt-br": "d2d4",
            "./pt-br.js": "d2d4",
            "./pt.js": "f260",
            "./ro": "972c",
            "./ro.js": "972c",
            "./ru": "957c",
            "./ru.js": "957c",
            "./sd": "6784",
            "./sd.js": "6784",
            "./se": "ffff",
            "./se.js": "ffff",
            "./si": "eda5",
            "./si.js": "eda5",
            "./sk": "7be6",
            "./sk.js": "7be6",
            "./sl": "8155",
            "./sl.js": "8155",
            "./sq": "c8f3",
            "./sq.js": "c8f3",
            "./sr": "cf1e",
            "./sr-cyrl": "13e9",
            "./sr-cyrl.js": "13e9",
            "./sr.js": "cf1e",
            "./ss": "52bd",
            "./ss.js": "52bd",
            "./sv": "5fbd",
            "./sv.js": "5fbd",
            "./sw": "74dc",
            "./sw.js": "74dc",
            "./ta": "3de5",
            "./ta.js": "3de5",
            "./te": "5cbb",
            "./te.js": "5cbb",
            "./tet": "576c",
            "./tet.js": "576c",
            "./tg": "3b1b",
            "./tg.js": "3b1b",
            "./th": "10e8",
            "./th.js": "10e8",
            "./tl-ph": "0f38",
            "./tl-ph.js": "0f38",
            "./tlh": "cf75",
            "./tlh.js": "cf75",
            "./tr": "0e81",
            "./tr.js": "0e81",
            "./tzl": "cf51",
            "./tzl.js": "cf51",
            "./tzm": "c109",
            "./tzm-latn": "b53d",
            "./tzm-latn.js": "b53d",
            "./tzm.js": "c109",
            "./ug-cn": "6117",
            "./ug-cn.js": "6117",
            "./uk": "ada2",
            "./uk.js": "ada2",
            "./ur": "5294",
            "./ur.js": "5294",
            "./uz": "2e8c",
            "./uz-latn": "010e",
            "./uz-latn.js": "010e",
            "./uz.js": "2e8c",
            "./vi": "2921",
            "./vi.js": "2921",
            "./x-pseudo": "fd7e",
            "./x-pseudo.js": "fd7e",
            "./yo": "7f33",
            "./yo.js": "7f33",
            "./zh-cn": "5c3a",
            "./zh-cn.js": "5c3a",
            "./zh-hk": "49ab",
            "./zh-hk.js": "49ab",
            "./zh-tw": "90ea",
            "./zh-tw.js": "90ea"
        };

        function i(e) {
            var a = t(e);
            return o(a)
        }

        function t(e) {
            if (!o.o(n, e)) {
                var a = new Error("Cannot find module '" + e + "'");
                throw a.code = "MODULE_NOT_FOUND", a
            }
            return n[e]
        }
        i.keys = function() {
            return Object.keys(n)
        }, i.resolve = t, e.exports = i, i.id = "4678"
    },
    "4aca": function(e, a, o) {
        "use strict";
        var n = o("37b3"),
            i = o.n(n);
        i.a
    },
    5063: function(e, a, o) {
        "use strict";
        var n = o("db9b"),
            i = o.n(n);
        i.a
    },
    7149: function(e, a, o) {},
    7182: function(e, a, o) {
        "use strict";
        var n = o("7149"),
            i = o.n(n);
        i.a
    },
    "86e1": function(e, a, o) {
        "use strict";
        var n = o("9fdd"),
            i = o.n(n);
        i.a
    },
    "93b7": function(e, a, o) {
        "use strict";
        o.r(a);
        o("e260"), o("e6cf"), o("cca6"), o("a79d");
        var n = o("2b0e"),
            i = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return e.$store.state.initialized ? o("div", {
                    attrs: {
                        id: "wrapper"
                    }
                }, [!e.is("guest") || e.can("write") || e.can("read") || e.can("upload") ? o("div", {
                    attrs: {
                        id: "inner"
                    }
                }, [o("router-view")], 1) : o("Login")], 1) : e._e()
            },
            t = [],
            r = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return e.$store.state.config.guest_redirection ? e._e() : o("div", [e.can("read") ? o("a", {
                    attrs: {
                        id: "back-arrow"
                    },
                    on: {
                        click: function(a) {
                            e.$router.push("/").catch((function() {}))
                        }
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "times"
                    }
                })], 1) : e._e(), o("div", {
                    staticClass: "columns is-centered",
                    attrs: {
                        id: "login"
                    }
                }, [o("div", {
                    staticClass: "column is-narrow"
                }, [o("form", {
                    on: {
                        submit: function(a) {
                            return a.preventDefault(), e.login(a)
                        }
                    }
                }, [o("div", {
                    staticClass: "box"
                }, [o("div", {
                    staticClass: "has-text-centered"
                }, [o("img", {
                    staticClass: "logo",
                    attrs: {
                        src: e.$store.state.config.logo
                    }
                })]), o("br"), o("b-field", {
                    attrs: {
                        label: e.lang("Username")
                    }
                }, [o("b-input", {
                    ref: "username",
                    attrs: {
                        name: "username",
                        required: ""
                    },
                    on: {
                        input: function(a) {
                            e.error = ""
                        }
                    },
                    model: {
                        value: e.username,
                        callback: function(a) {
                            e.username = a
                        },
                        expression: "username"
                    }
                })], 1), o("b-field", {
                    attrs: {
                        label: e.lang("Password")
                    }
                }, [o("b-input", {
                    attrs: {
                        type: "password",
                        name: "password",
                        required: "",
                        "password-reveal": ""
                    },
                    on: {
                        input: function(a) {
                            e.error = ""
                        }
                    },
                    model: {
                        value: e.password,
                        callback: function(a) {
                            e.password = a
                        },
                        expression: "password"
                    }
                })], 1), o("div", {
                    staticClass: "is-flex is-justify-end"
                }, [o("button", {
                    staticClass: "button is-primary"
                }, [e._v(" " + e._s(e.lang("Login")) + " ")])]), e.error ? o("div", [o("code", [e._v(e._s(e.error))])]) : e._e()], 1)])])])])
            },
            s = [],
            l = (o("b0c0"), o("d3b7"), o("bc3a")),
            d = o.n(l),
            c = o("27ae"),
            u = {
                getConfig: function() {
                    return new Promise((function(e, a) {
                        d.a.get("getconfig").then((function(a) {
                            return e(a)
                        }))["catch"]((function(e) {
                            return a(e)
                        }))
                    }))
                },
                getUser: function() {
                    return new Promise((function(e, a) {
                        d.a.get("getuser").then((function(a) {
                            d.a.defaults.headers.common["x-csrf-token"] = a.headers["x-csrf-token"], e(a.data.data)
                        }))["catch"]((function(e) {
                            return a(e)
                        }))
                    }))
                },
                login: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("login", {
                            username: e.username,
                            password: e.password
                        }).then((function(e) {
                            a(e.data.data)
                        }), (function(e) {
                            return o(e)
                        }))
                    }))
                },
                logout: function() {
                    return new Promise((function(e, a) {
                        d.a.post("logout").then((function(a) {
                            return e(a.data.data)
                        }))["catch"]((function(e) {
                            return a(e)
                        }))
                    }))
                },
                changeDir: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("changedir", {
                            to: e.to
                        }).then((function(e) {
                            console.log(e.data.data);
                            
                            if(e.data.data['location'] == "/"){
                              return a(raiz);
                         //   return a(e.data.data)
                            }else{
                                raiz=e.data.data;
								return a(e.data.data);
							}
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                getDir: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("getdir", {
                            dir: e.dir
                        }).then((function(e) {
                            if(e.data.data['location'] != "/"){
                            return a(e.data.data)
                            }else{
								return "";
							}
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                copyItems: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("copyitems", {
                            destination: e.destination,
                            items: e.items
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                moveItems: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("moveitems", {
                            destination: e.destination,
                            items: e.items
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                renameItem: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("renameitem", {
                            from: e.from,
                            to: e.to,
                            destination: e.destination
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                batchDownload: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("batchdownload", {
                            items: e.items
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                zipItems: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("zipitems", {
                            name: e.name,
                            items: e.items,
                            destination: e.destination
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                unzipItem: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("unzipitem", {
                            item: e.item,
                            destination: e.destination
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                removeItems: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("deleteitems", {
                            items: e.items
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                createNew: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("createnew", {
                            type: e.type,
                            name: e.name,
                            destination: e.destination
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                listUsers: function() {
                    return new Promise((function(e, a) {
                        d.a.get("listusers").then((function(a) {
                            return e(a.data.data)
                        }))["catch"]((function(e) {
                            return a(e)
                        }))
                    }))
                },
                deleteUser: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("deleteuser/" + e.username).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                storeUser: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("storeuser", {
                            role: e.role,
                            name: e.name,
                            username: e.username,
                            homedir: e.homedir,
                            password: e.password,
                            permissions: e.permissions
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                updateUser: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("updateuser/" + e.key, {
                            role: e.role,
                            name: e.name,
                            username: e.username,
                            homedir: e.homedir,
                            password: e.password,
                            permissions: e.permissions
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                changePassword: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("changepassword", {
                            oldpassword: e.oldpassword,
                            newpassword: e.newpassword
                        }).then((function(e) {
                            return a(e.data.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                downloadItem: function(e) {
                    return new Promise((function(a, o) {
                        d.a.get("download&path=" + encodeURIComponent(c["Base64"].encode(e.path)), {
                            transformResponse: void 0
                        }).then((function(e) {
                            return a(e.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                },
                saveContent: function(e) {
                    return new Promise((function(a, o) {
                        d.a.post("savecontent", {
                            name: e.name,
                            content: e.content
                        }).then((function(e) {
                            return a(e.data)
                        }))["catch"]((function(e) {
                            return o(e)
                        }))
                    }))
                }
            },
            p = u,
            m = {
                name: "Login",
                data: function() {
                    return {
                        username: "",
                        password: "",
                        error: ""
                    }
                },
                mounted: function() {
                    this.$store.state.config.guest_redirection ? window.location.href = this.$store.state.config.guest_redirection : this.$refs.username.focus()
                },
                methods: {
                    login: function() {
                        var e = this;
                        p.login({
                            username: this.username,
                            password: this.password
                        }).then((function(a) {
                            e.$store.commit("setUser", a), p.changeDir({
                                to: "/"
                            }).then((function() {
                                return e.$router.push("/")["catch"]((function() {}))
                            }))
                        }))["catch"]((function(a) {
                            a.response && a.response.data ? e.error = e.lang(a.response.data.data) : e.handleError(a), e.password = ""
                        }))
                    }
                }
            },
            f = m,
            h = (o("5063"), o("2877")),
            g = Object(h["a"])(f, r, s, !1, null, "17127292", null),
            w = g.exports,
            v = {
                name: "App",
                components: {
                    Login: w
                }
            },
            b = v,
            y = (o("2b2b"), Object(h["a"])(b, i, t, !1, null, null, null)),
            k = y.exports,
            z = o("8c4f"),
            P = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", {
                    staticClass: "container",
                    attrs: {
                        id: "dropzone"
                    },
                    on: {
                        dragover: function(a) {
                            e.dropZone = !(!e.can("upload") || e.isLoading)
                        },
                        dragleave: function(a) {
                            e.dropZone = !1
                        },
                        drop: function(a) {
                            e.dropZone = !1
                        }
                    }
                }, [e.isLoading ? o("div", {
                    attrs: {
                        id: "loading"
                    }
                }) : e._e(), e.can("upload") ? o("Upload", {
                    directives: [{
                        name: "show",
                        rawName: "v-show",
                        value: 0 == e.dropZone,
                        expression: "dropZone == false"
                    }],
                    attrs: {
                        files: e.files,
                        "drop-zone": e.dropZone
                    }
                }) : e._e(), e.dropZone && !e.isLoading ? o("b-upload", {
                    attrs: {
                        multiple: "",
                        "drag-drop": ""
                    }
                }, [o("b", {
                    staticClass: "drop-info"
                }, [e._v(e._s(e.lang("Drop files to upload")))])]) : e._e(), e.dropZone ? e._e() : o("div", {
                    staticClass: "container"
                }, [o("Menu"), o("div", {
                    attrs: {
                        id: "browser"
                    }
                }, [e.can("read") ? o("div", {
                    staticClass: "is-flex is-justify-between"
                }, [o("div", {
                    staticClass: "breadcrumb",
                    attrs: {
                        "aria-label": "breadcrumbs"
                    }
                }, [o("ul", e._l(e.breadcrumbs, (function(a, n) {
                    return o("li", {
                        key: n
                    }, [o("a", {
                        on: {
                            click: function(o) {
                                return e.goTo(a.path)
                            }
                        }
                    }, [e._v(e._s(a.name))])])
                })), 0)]), o("div", [o("a", {
                    staticClass: "search-btn",
                    attrs: {
                        id: "search"
                    },
                    on: {
                        click: e.search
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "search",
                        size: "is-small"
                    }
                })], 1), o("a", {
                    staticClass: "is-paddingless",
                    attrs: {
                        id: "sitemap"
                    },
                    on: {
                        click: e.selectDir
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "sitemap",
                        size: "is-small"
                    }
                })], 1)])]) : e._e(), o("section", {
                    staticClass: "is-flex is-justify-between",
                    attrs: {
                        id: "multi-actions"
                    }
                }, [o("div", [e.can("upload") && !e.checked.length ? o("b-field", {
                    staticClass: "file is-inline-block"
                }, [o("b-upload", {
                    attrs: {
                        multiple: "",
                        native: ""
                    },
                    on: {
                        input: function(a) {
                            e.files = a
                        }
                    }
                }, [e.checked.length ? e._e() : o("a", {
                    staticClass: "is-inline-block"
                }, [o("b-icon", {
                    attrs: {
                        icon: "upload",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("Add files")) + " ")], 1)])], 1) : e._e(), e.can(["read", "write"]) && !e.checked.length ? o("a", {
                    staticClass: "add-new is-inline-block"
                }, [o("b-dropdown", {
                    attrs: {
                        disabled: e.checked.length > 0,
                        "aria-role": "list"
                    }
                }, [o("span", {
                    attrs: {
                        slot: "trigger"
                    },
                    slot: "trigger"
                }, [o("b-icon", {
                    attrs: {
                        icon: "plus",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("New")) + " ")], 1), o("b-dropdown-item", {
                    attrs: {
                        "aria-role": "listitem"
                    },
                    on: {
                        click: function(a) {
                            return e.create("dir")
                        }
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "folder",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("Folder")) + " ")], 1), o("b-dropdown-item", {
                    attrs: {
                        "aria-role": "listitem"
                    },
                    on: {
                        click: function(a) {
                            return e.create("file")
                        }
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "file",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("File")) + " ")], 1)], 1)], 1) : e._e(), e.can("batchdownload") && e.checked.length ? o("a", {
                    staticClass: "is-inline-block",
                    on: {
                        click: e.batchDownload
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "download",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("Download")) + " ")], 1) : e._e(), e.can("write") && e.checked.length ? o("a", {
                    staticClass: "is-inline-block",
                    on: {
                        click: e.copy
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "copy",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("Copy")) + " ")], 1) : e._e(), e.can("write") && e.checked.length ? o("a", {
                    staticClass: "is-inline-block",
                    on: {
                        click: e.move
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "external-link-square-alt",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("Move")) + " ")], 1) : e._e(), e.can(["write", "zip"]) && e.checked.length ? o("a", {
                    staticClass: "is-inline-block",
                    on: {
                        click: e.zip
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "file-archive",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("Zip")) + " ")], 1) : e._e(), e.can("write") && e.checked.length ? o("a", {
                    staticClass: "is-inline-block",
                    on: {
                        click: e.remove
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "trash-alt",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("Delete")) + " ")], 1) : e._e()], 1), e.can("read") ? o("div", {
                    attrs: {
                        id: "pagination"
                    }
                }, [o("Pagination", {
                    attrs: {
                        perpage: e.perPage
                    },
                    on: {
                        selected: function(a) {
                            e.perPage = a
                        }
                    }
                })], 1) : e._e()]), e.can("read") ? o("b-table", {
                    attrs: {
                        data: e.content,
                        "default-sort": e.defaultSort,
                        paginated: e.perPage > 0,
                        "per-page": e.perPage,
                        "current-page": e.currentPage,
                        hoverable: !0,
                        "is-row-checkable": function(e) {
                            return "back" != e.type
                        },
                        "row-class": function(e) {
                            return "file-row type-" + e.type
                        },
                        "checked-rows": e.checked,
                        loading: e.isLoading,
                        checkable: e.can("batchdownload") || e.can("write") || e.can("zip")
                    },
                    on: {
                        "update:currentPage": function(a) {
                            e.currentPage = a
                        },
                        "update:current-page": function(a) {
                            e.currentPage = a
                        },
                        "update:checkedRows": function(a) {
                            e.checked = a
                        },
                        "update:checked-rows": function(a) {
                            e.checked = a
                        },
                        contextmenu: e.rightClick
                    },
                    scopedSlots: e._u([{
                        key: "default",
                        fn: function(a) {
                            return [o("b-table-column", {
                                attrs: {
                                    label: e.lang("Name"),
                                    "custom-sort": e.sortByName,
                                    field: "data.name",
                                    sortable: ""
                                }
                            }, [o("a", {
                                staticClass: "is-block name",
                                on: {
                                    click: function(o) {
                                        return e.itemClick(a.row)
                                    }
                                }
                            }, [e._v(" " + e._s(a.row.name) + " ")])]), o("b-table-column", {
                                attrs: {
                                    label: e.lang("Size"),
                                    "custom-sort": e.sortBySize,
                                    field: "data.size",
                                    sortable: "",
                                    numeric: "",
                                    width: "150"
                                }
                            }, [e._v(" " + e._s("back" == a.row.type || "dir" == a.row.type ? e.lang("Folder") : e.formatBytes(a.row.size)) + " ")]), o("b-table-column", {
                                attrs: {
                                    label: e.lang("Time"),
                                    "custom-sort": e.sortByTime,
                                    field: "data.time",
                                    sortable: "",
                                    numeric: "",
                                    width: "200"
                                }
                            }, [e._v(" " + e._s(a.row.time ? e.formatDate(a.row.time) : "") + " ")]), o("b-table-column", {
                                attrs: {
                                    id: "single-actions",
                                    width: "51"
                                }
                            }, ["back" != a.row.type ? o("b-dropdown", {
                                attrs: {
                                    disabled: e.checked.length > 0,
                                    "aria-role": "list",
                                    position: "is-bottom-left"
                                }
                            }, [o("button", {
                                ref: "ref-single-action-button-" + a.row.path,
                                staticClass: "button is-small",
                                attrs: {
                                    slot: "trigger"
                                },
                                slot: "trigger"
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "ellipsis-h",
                                    size: "is-small"
                                }
                            })], 1), "file" == a.row.type && e.can("download") ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },
                                on: {
                                    click: function(o) {
                                        return e.download(a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "download",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Download")) + " ")], 1) : e._e(), "file" == a.row.type && e.can(["download"]) && e.hasPreview(a.row.path) ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },
                                on: {
                                    click: function(o) {
                                        return e.preview(a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "file-alt",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("View")) + " ")], 1) : e._e(), e.can("write") ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },
                                /*
                                on: {
                                    click: function(o) {
                                        return e.copy(o, a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "copy",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Copy")) + " ")], 1) : e._e(), e.can("write") ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },
                                on: {
                                    click: function(o) {
                                        return e.move(o, a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "external-link-square-alt",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Move")) + " ")], 1) : e._e(), e.can("write") ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },*/
                                on: {
                                    click: function(o) {
                                        return e.rename(o, a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "file-signature",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Rename")) + " ")], 1) : e._e(), e.can(["write", "zip"]) && e.isArchive(a.row) ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },
                                on: {
                                    click: function(o) {
                                        return e.unzip(o, a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "file-archive",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Unzip")) + " ")], 1) : e._e(), e.can(["write", "zip"]) && !e.isArchive(a.row) ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },
                                on: {
                                    click: function(o) {
                                        return e.zip(o, a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "file-archive",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Zip")) + " ")], 1) : e._e(), e.can("write") ? o("b-dropdown-item", {
                                attrs: {
                                    "aria-role": "listitem"
                                },
                                on: {
                                    click: function(o) {
                                        return e.remove(o, a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "trash-alt",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Delete")) + " ")], 1) : e._e(), "file" == a.row.type && e.can("download") ? o("b-dropdown-item", {
                                directives: [{
                                    name: "clipboard",
                                    rawName: "v-clipboard:copy",
                                    value: e.getDownloadLink(a.row.path),
                                    expression: "getDownloadLink(props.row.path)",
                                    arg: "copy"
                                }],
                                attrs: {
                                    "aria-role": "listitem"
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "clipboard",
                                    size: "is-small"
                                }
                            }), e._v(" " + e._s(e.lang("Copy link")) + " ")], 1) : e._e()], 1) : e._e()], 1)]
                        }
                    }], null, !1, 3822470433)
                }) : e._e(), o("section", {
                    staticClass: "is-flex is-justify-between",
                    attrs: {
                        id: "bottom-info"
                    }
                }, [o("div", [o("span", [e._v(e._s(e.lang("Selected", e.checked.length, e.totalCount)))])]), e.showAllEntries || e.hasFilteredEntries ? o("div", [o("input", {
                    attrs: {
                        type: "checkbox",
                        id: "checkbox"
                    },
                    on: {
                        click: e.toggleHidden
                    }
                }), o("label", {
                    attrs: {
                        for: "checkbox"
                    }
                }, [e._v(" " + e._s(e.lang("Show hidden")))])]) : e._e()])], 1)], 1)], 1)
            },
            U = [],
            C = (o("4de4"), o("4160"), o("13d5"), o("a9e3"), o("4d63"), o("ac1f"), o("25f0"), o("8a79"), o("5319"), o("1276"), o("2ca0"), o("159b"), function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("nav", {
                    staticClass: "navbar",
                    attrs: {
                        role: "navigation",
                        "aria-label": "main navigation"
                    }
                }, [o("div", {
                    staticClass: "navbar-brand"
                }, [o("a", {
                    staticClass: "navbar-item logo",
                    on: {
                        click: function(a) {
                            e.$router.push("/").catch((function() {}))
                        }
                    }
                }, [o("img", {
                    attrs: {
                        src: this.$store.state.config.logo
                    }
                })]), o("a", {
                    class: [e.navbarActive ? "is-active" : "", "navbar-burger burger"],
                    attrs: {
                        role: "button",
                        "aria-label": "menu",
                        "aria-expanded": "false"
                    },
                    on: {
                        click: function(a) {
                            e.navbarActive = !e.navbarActive
                        }
                    }
                }, [o("span", {
                    attrs: {
                        "aria-hidden": "true"
                    }
                }), o("span", {
                    attrs: {
                        "aria-hidden": "true"
                    }
                }), o("span", {
                    attrs: {
                        "aria-hidden": "true"
                    }
                })])]), o("div", {
                    class: [e.navbarActive ? "is-active" : "", "navbar-menu"]
                }, [o("div", {
                    staticClass: "navbar-end"
                }, [e.is("admin") ? o("a", {
                    staticClass: "navbar-item files",
                    on: {
                        click: function(a) {
                            e.$router.push("/").catch((function() {}))
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Files")) + " ")]) : e._e(), e.is("admin") ? o("a", {
                    staticClass: "navbar-item users",
                    on: {
                        click: function(a) {
                            e.$router.push("/users").catch((function() {}))
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Users")) + " ")]) : e._e(), e.is("guest") ? o("a", {
                    staticClass: "navbar-item login",
                    on: {
                        click: e.login
                    }
                }, [e._v(" " + e._s(e.lang("Login")) + " ")]) : e._e(), e.is("guest") ? e._e() : o("a", {
                    staticClass: "navbar-item profile",
                    on: {
                        click: e.profile
                    }
                }, [e._v(" " + e._s(this.$store.state.user.name) + " ")]), e.is("guest") ? e._e() : o("a", {
                    staticClass: "navbar-item logout",
                    on: {
                        click: e.logout
                    }
                }, [e._v(" " + e._s(e.lang("Logout")) + " ")])])])])
            }),
            j = [],
            S = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", {
                    staticClass: "modal-card"
                }, [o("header", {
                    staticClass: "modal-card-head"
                }, [o("p", {
                    staticClass: "modal-card-title"
                }, [e._v(" " + e._s(e.lang("Profile")) + " ")])]), o("section", {
                    staticClass: "modal-card-body"
                }, [o("form", {
                    on: {
                        submit: e.save
                    }
                }, [o("b-field", {
                    attrs: {
                        label: e.lang("Old password"),
                        type: e.formErrors.oldpassword ? "is-danger" : "",
                        message: e.formErrors.oldpassword
                    }
                }, [o("b-input", {
                    attrs: {
                        "password-reveal": "",
                        required: ""
                    },
                    nativeOn: {
                        keydown: function(a) {
                            e.formErrors.oldpassword = ""
                        }
                    },
                    model: {
                        value: e.oldpassword,
                        callback: function(a) {
                            e.oldpassword = a
                        },
                        expression: "oldpassword"
                    }
                })], 1), o("b-field", {
                    attrs: {
                        label: e.lang("New password"),
                        type: e.formErrors.newpassword ? "is-danger" : "",
                        message: e.formErrors.newpassword
                    }
                }, [o("b-input", {
                    attrs: {
                        "password-reveal": "",
                        required: ""
                    },
                    nativeOn: {
                        keydown: function(a) {
                            e.formErrors.newpassword = ""
                        }
                    },
                    model: {
                        value: e.newpassword,
                        callback: function(a) {
                            e.newpassword = a
                        },
                        expression: "newpassword"
                    }
                })], 1)], 1)]), o("footer", {
                    staticClass: "modal-card-foot"
                }, [o("button", {
                    staticClass: "button",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: function(a) {
                            return e.$parent.close()
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Close")) + " ")]), o("button", {
                    staticClass: "button is-primary",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: e.save
                    }
                }, [e._v(" " + e._s(e.lang("Save")) + " ")])])])
            },
            N = [],
            A = o("53ca"),
            D = o("2ef0"),
            _ = o.n(D),
            F = {
                name: "Profile",
                data: function() {
                    return {
                        oldpassword: "",
                        newpassword: "",
                        formErrors: {}
                    }
                },
                methods: {
                    save: function() {
                        var e = this;
                        p.changePassword({
                            oldpassword: this.oldpassword,
                            newpassword: this.newpassword
                        }).then((function() {
                            e.$toast.open({
                                message: e.lang("Updated"),
                                type: "is-success"
                            }), e.$parent.close()
                        }))["catch"]((function(a) {
                            "object" != Object(A["a"])(a.response.data.data) && e.handleError(a), _.a.forEach(a.response.data, (function(a) {
                                _.a.forEach(a, (function(a, o) {
                                    e.formErrors[o] = e.lang(a), e.$forceUpdate()
                                }))
                            }))
                        }))
                    }
                }
            },
            L = F,
            E = Object(h["a"])(L, S, N, !1, null, null, null),
            $ = E.exports,
            x = {
                name: "Menu",
                data: function() {
                    return {
                        navbarActive: !1
                    }
                },
                mounted: function() {
                    this.$store.state.user.firstlogin && this.profile()
                },
                methods: {
                    logout: function() {
                        var e = this;
                        p.logout().then((function() {
                            e.$store.commit("initialize"), p.getUser().then((function(a) {
                                e.$store.commit("setUser", a), e.$router.push("/")["catch"]((function() {}))
                            }))["catch"]((function() {
                                e.$store.commit("initialize")
                            }))
                        }))["catch"]((function(a) {
                            e.$store.commit("initialize"), e.handleError(a)
                        }))
                    },
                    login: function() {
                        this.$router.push("/login")["catch"]((function() {}))
                    },
                    profile: function() {
                        this.$modal.open({
                            parent: this,
                            hasModalCard: !0,
                            component: $
                        })
                    }
                }
            },
            T = x,
            R = (o("d7ef"), Object(h["a"])(T, C, j, !1, null, "cd57c856", null)),
            B = R.exports,
            q = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", {
                    staticClass: "modal-card"
                }, [o("header", {
                    staticClass: "modal-card-head"
                }, [o("p", {
                    staticClass: "modal-card-title"
                }, [e._v(" " + e._s(e.lang("Select Folder")) + " ")])]), o("section", {
                    staticClass: "modal-card-body"
                }, [o("div", {
                    staticClass: "tree"
                }, [o("ul", {
                    staticClass: "tree-list"
                }, [o("TreeNode", {
                    attrs: {
                        node: e.$store.state.tree
                    },
                    on: {
                        selected: function(a) {
                            e.$emit("selected", a) && e.$parent.close()
                        }
                    }
                })], 1)])]), o("footer", {
                    staticClass: "modal-card-foot"
                }, [o("button", {
                    staticClass: "button",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: function(a) {
                            return e.$parent.close()
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Close")) + " ")])])])
            },
            O = [],
            V = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("li", {
                    staticClass: "node-tree"
                }, [o("b-button", {
                    attrs: {
                        type: e.button_type,
                        size: "is-small"
                    },
                    on: {
                        click: function(a) {
                            return e.toggleButton(e.node)
                        }
                    }
                }, [o("span", {
                    staticClass: "icon"
                }, [o("i", {
                    class: e.icon
                })])]), e._v(" "), o("a", {
                    on: {
                        click: function(a) {
                            return e.$emit("selected", e.node)
                        }
                    }
                }, [e._v(e._s(e.node.name))]), e.node.children && e.node.children.length ? o("ul", e._l(e.node.children, (function(a, n) {
                    return o("TreeNode", {
                        key: n,
                        attrs: {
                            node: a
                        },
                        on: {
                            selected: function(a) {
                                return e.$emit("selected", a)
                            }
                        }
                    })
                })), 1) : e._e()], 1)
            },
            H = [],
            M = {
                name: "TreeNode",
                props: {
                    node: {
                        type: Object,
                        required: !0
                    }
                },
                data: function() {
                    return {
                        active: !1,
                        button_type: "is-primary"
                    }
                },
                computed: {
                    icon: function() {
                        return {
                            fas: !0,
                            "mdi-24px": !0,
                            "fa-plus": !this.active,
                            "fa-minus": this.active
                        }
                    }
                },
                mounted: function() {
                    "/" == this.node.path && (this.$store.commit("resetTree"), this.toggleButton(this.node))
                },
                methods: {
                    toggleButton: function(e) {
                        var a = this;
                        this.active ? (this.active = !1, this.$store.commit("updateTreeNode", {
                            children: [],
                            path: e.path
                        })) : (this.active = !0, this.button_type = "is-primary is-loading", p.getDir({
                            dir: e.path
                        }).then((function(o) {
                            a.$store.commit("updateTreeNode", {
                                children: _.a.filter(o.files, ["type", "dir"]),
                                path: e.path
                            }), a.$forceUpdate(), a.button_type = "is-primary"
                        }))["catch"]((function(e) {
                            return a.handleError(e)
                        })))
                    }
                }
            },
            I = M,
            Z = (o("4aca"), Object(h["a"])(I, V, H, !1, null, "45d0a157", null)),
            G = Z.exports,
            W = {
                name: "Tree",
                components: {
                    TreeNode: G
                }
            },
            K = W,
            Y = (o("b069"), Object(h["a"])(K, q, O, !1, null, null, null)),
            J = Y.exports,
            Q = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", [o("div", {
                    staticClass: "modal-card"
                }, [o("header", {
                    staticClass: "modal-card-head"
                }, [o("p", {
                    staticClass: "modal-card-title"
                }, [e._v(" " + e._s(e.currentItem.name) + " ")])]), o("section", {
                    staticClass: "modal-card-body preview"
                }, [
                    [o("prism-editor", {
                        attrs: {
                            language: "md",
                            readonly: !e.can("write"),
                            "line-numbers": e.lineNumbers
                        },
                        model: {
                            value: e.content,
                            callback: function(a) {
                                e.content = a
                            },
                            expression: "content"
                        }
                    })]
                ], 2), o("footer", {
                    staticClass: "modal-card-foot"
                }, [e.can("write") ? o("button", {
                    staticClass: "button",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: function(a) {
                            return e.saveFile()
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Save")) + " ")]) : e._e(), o("button", {
                    staticClass: "button",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: function(a) {
                            return e.$parent.close()
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Close")) + " ")])])])])
            },
            X = [],
            ee = (o("c197"), o("a878"), o("fdfb"), o("431a")),
            ae = o.n(ee),
            oe = {
                name: "Editor",
                components: {
                    PrismEditor: ae.a
                },
                props: ["item"],
                data: function() {
                    return {
                        content: "",
                        currentItem: "",
                        lineNumbers: !0
                    }
                },
                mounted: function() {
                    var e = this;
                    this.currentItem = this.item, p.downloadItem({
                        path: this.item.path
                    }).then((function(a) {
                        e.content = a
                    }))["catch"]((function(a) {
                        return e.handleError(a)
                    }))
                },
                methods: {
                    saveFile: function() {
                        var e = this;
                        p.saveContent({
                            name: this.item.name,
                            content: this.content
                        }).then((function() {
                            e.$toast.open({
                                message: e.lang("Updated"),
                                type: "is-success"
                            }), e.$parent.close()
                        }))["catch"]((function(a) {
                            return e.handleError(a)
                        }))
                    }
                }
            },
            ne = oe,
            ie = (o("86e1"), Object(h["a"])(ne, Q, X, !1, null, "47c7e221", null)),
            te = ie.exports,
            re = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", [o("div", {
                    staticClass: "modal-card"
                }, [o("div", {
                    staticClass: "modal-card-body preview"
                }, [o("strong", [e._v(e._s(e.currentItem.name))]), o("div", {
                    staticClass: "columns is-mobile"
                }, [o("div", {
                    staticClass: "column mainbox"
                }, [o("img", {
                    staticClass: "mainimg",
                    attrs: {
                        src: e.imageSrc(e.currentItem.path)
                    }
                })]), e.images.length > 1 ? o("div", {
                    staticClass: "column is-one-fifth sidebox"
                }, [o("ul", e._l(e.images, (function(a, n) {
                    return o("li", {
                        key: n
                    }, [o("img", {
                        directives: [{
                            name: "lazy",
                            rawName: "v-lazy",
                            value: e.imageSrc(a.path),
                            expression: "imageSrc(image.path)"
                        }],
                        on: {
                            click: function(o) {
                                e.currentItem = a
                            }
                        }
                    })])
                })), 0)]) : e._e()])])])])
            },
            se = [],
            le = {
                name: "Gallery",
                props: ["item"],
                data: function() {
                    return {
                        content: "",
                        currentItem: "",
                        lineNumbers: !0
                    }
                },
                computed: {
                    images: function() {
                        var e = this;
                        return _.a.filter(this.$store.state.cwd.content, (function(a) {
                            return e.isImage(a.name)
                        }))
                    }
                },
                mounted: function() {
                    this.currentItem = this.item
                },
                methods: {
                    imageSrc: function(e) {
                        return this.getDownloadLink(e)
                    }
                }
            },
            de = le,
            ce = (o("14a0"), Object(h["a"])(de, re, se, !1, null, "45cb0efd", null)),
            ue = ce.exports,
            pe = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", {
                    staticClass: "modal-card"
                }, [o("header", {
                    staticClass: "modal-card-head"
                }, [o("p", {
                    staticClass: "modal-card-title"
                }, [e._v(" " + e._s(e.lang("Search")) + " ")])]), o("section", {
                    staticClass: "modal-card-body"
                }, [o("b-input", {
                    ref: "input",
                    attrs: {
                        placeholder: e.lang("Name")
                    },
                    on: {
                        input: e.searchFiles
                    },
                    model: {
                        value: e.term,
                        callback: function(a) {
                            e.term = a
                        },
                        expression: "term"
                    }
                }), o("br"), o("b-loading", {
                    attrs: {
                        "is-full-page": !1,
                        active: e.searching
                    },
                    on: {
                        "update:active": function(a) {
                            e.searching = a
                        }
                    }
                }), o("ul", {
                    ref: "results"
                }, e._l(e.results, (function(a, n) {
                    return o("li", {
                        key: n
                    }, [o("a", {
                        on: {
                            click: function(o) {
                                return e.select(a)
                            }
                        }
                    }, [e._v(e._s(a.file.path))])])
                })), 0)], 1), o("footer", {
                    staticClass: "modal-card-foot"
                }, [o("button", {
                    staticClass: "button",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: function(a) {
                            return e.$parent.close()
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Close")) + " ")])])])
            },
            me = [],
            fe = (o("c975"), {
                name: "Search",
                data: function() {
                    return {
                        active: !1,
                        searching: !1,
                        term: "",
                        results: []
                    }
                },
                mounted: function() {
                    this.active = !0, this.searching = !1, this.$refs.input.focus()
                },
                beforeDestroy: function() {
                    this.active = !1, this.searching = !1
                },
                methods: {
                    select: function(e) {
                        this.$emit("selected", e), this.$parent.close()
                    },
                    searchFiles: _.a.debounce((function(e) {
                        this.results = [], e.length > 0 && (this.searching = !0, this.getDir("/"))
                    }), 1e3),
                    getDir: function(e) {
                        var a = this;
                        this.active && (this.searching = !0, p.getDir({
                            dir: e
                        }).then((function(o) {
                            a.searching = !1, _.a.forEach(o.files, (function(o) {
                                o.name.toLowerCase().indexOf(a.term.toLowerCase()) > -1 && a.results.push({
                                    file: o,
                                    dir: e
                                })
                            })), _.a.forEach(_.a.filter(o.files, ["type", "dir"]), (function(e) {
                                a.getDir(e.path)
                            }))
                        }))["catch"]((function(e) {
                            return a.handleError(e)
                        })))
                    }
                }
            }),
            he = fe,
            ge = Object(h["a"])(he, pe, me, !1, null, null, null),
            we = ge.exports,
            ve = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", [o("b-select", {
                    attrs: {
                        value: e.perpage,
                        size: "is-small"
                    },
                    on: {
                        input: function(a) {
                            return e.$emit("selected", a)
                        }
                    }
                }, [o("option", {
                    attrs: {
                        value: ""
                    }
                }, [e._v(" " + e._s(e.lang("No pagination")) + " ")]), o("option", {
                    attrs: {
                        value: "5"
                    }
                }, [e._v(" " + e._s(e.lang("Per page", 5)) + " ")]), o("option", {
                    attrs: {
                        value: "10"
                    }
                }, [e._v(" " + e._s(e.lang("Per page", 10)) + " ")]), o("option", {
                    attrs: {
                        value: "15"
                    }
                }, [e._v(" " + e._s(e.lang("Per page", 15)) + " ")])])], 1)
            },
            be = [],
            ye = {
                name: "Pagination",
                props: ["perpage"]
            },
            ke = ye,
            ze = Object(h["a"])(ke, ve, be, !1, null, null, null),
            Pe = ze.exports,
            Ue = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
				
                return o("div", [e.visible && 0 == e.dropZone ? o("div", {
                    staticClass: "progress-box"
                }, [o("div", {
                    staticClass: "box"
                }, [o("div", [o("div", {
                    staticClass: "is-flex is-justify-between"
                }, [o("div", {
                    staticClass: "is-flex"
                }, [o("a", {
                    on: {
                        click: e.toggleWindow 
						
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: e.progressVisible ? "angle-down" : "angle-up"
                    }
                })], 1), e.activeUploads ? o("span", [e._v(" " + e._s(e.lang("Uploading files", e.resumable.getSize() > 0 ? Math.round(100 * e.resumable.progress()) : 100, e.formatBytes(e.resumable.getSize()))) + " ")]) : e._e(), e.activeUploads && e.paused ? o("span", [e._v(" (" + e._s(e.lang("Paused")) + ") ")]) : e._e(), e.activeUploads ? e._e() : o("span", [e._v(" " + e._s(e.lang("Done")) + " ")])]), o("div", {
                    staticClass: "is-flex"
                }, [e.activeUploads ? o("a", {
                    on: {
                        click: function(a) {
                            return e.togglePause()
                        }
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: e.paused ? "play-circle" : "pause-circle"
                    }
                })], 1) : e._e(), o("a", {
                    staticClass: "progress-icon",
                    on: {
                        click: function(a) {
                            return e.closeWindow()
                        }
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "times"
                    }
                })], 1)])]), o("hr")]), e.progressVisible ? o("div", {
                    staticClass: "progress-items"
                }, e._l(e.resumable.files.slice().reverse(), (function(a, n) {
                    return o("div", {
                        key: n
                    }, [o("div", [o("div", [e._v(e._s("/" != a.relativePath ? a.relativePath : "") + "/" + e._s(a.fileName))]), o("div", {
                        staticClass: "is-flex is-justify-between"
                    }, [o("progress", {
                        class: [a.file.uploadingError ? "is-danger" : "is-primary", "progress is-large"],
                        attrs: {
                            max: "100"
                        },
                        domProps: {
                            value: a.size > 0 ? 100 * a.progress() : 100
                        }
                    }), !a.isUploading() && a.file.uploadingError ? o("a", {
                        staticClass: "progress-icon",
                        on: {
                            click: function(e) {
                                return a.retry()
                            }
                        }
                    }, [o("b-icon", {
                        attrs: {
                            icon: "redo",
                            type: "is-danger"
                        }
                    })], 1) : o("a", {
                        staticClass: "progress-icon",
                        on: {
                            click: function(e) {
                                return a.cancel()
                            }
                        }
                    }, [o("b-icon", {
                        attrs: {
                            icon: a.isComplete() ? "check" : "times"
                        }
                    })], 1)])])])
                })), 0) : e._e()])]) : e._e()])
            },
            Ce = [],
            je = (o("a15b"), o("f056")),
            Se = o.n(je),
            Ne = {
                name: "Upload",
                props: ["files", "dropZone"],
                data: function() {
                    return {
                        resumable: {},
                        visible: !1,
                        paused: !1,
                        progressVisible: !1,
                        progress: 0
                    }
                },
                computed: {
                    activeUploads: function() {
                        return this.resumable.files.length > 0 && this.resumable.progress() < 1
                    }
                },
                watch: {
                    files: function(e) {
                        var a = this;
                        _.a.forEach(e, (function(e) {
                            a.resumable.addFile(e)
                        }))
                    }
                },
                mounted: function() {
                    var e = this;
                    this.resumable = new Se.a({
                        target: n["default"].config.baseURL + "/upload",
                        headers: {
                            "x-csrf-token": d.a.defaults.headers.common["x-csrf-token"]
                        },
                        withCredentials: !0,
                        simultaneousUploads: this.$store.state.config.upload_simultaneous,
                        minFileSize: 0,
                        chunkSize: this.$store.state.config.upload_chunk_size,
                        maxFileSize: this.$store.state.config.upload_max_size,
                        maxFileSizeErrorCallback: function(a) {
                            e.$notification.open({
                                message: e.lang("File size error", a.name, e.formatBytes(e.$store.state.config.upload_max_size)),
                                type: "is-danger",
                                queue: !1,
                                indefinite: !0
                            })
                        }
                    }), this.resumable.support ? (this.resumable.assignDrop(document.getElementById("dropzone")), this.resumable.on("fileAdded", (function(a) {
                        e.visible = !0, e.progressVisible = !0, void 0 === a.relativePath || null === a.relativePath || a.relativePath == a.fileName ? a.relativePath = e.$store.state.cwd.location : a.relativePath = [e.$store.state.cwd.location, a.relativePath].join("/").replace("//", "/").replace(a.fileName, "").replace(/\/$/, ""), e.paused || e.resumable.upload()
                    })), this.resumable.on("fileSuccess", (function(a) {
                        a.file.uploadingError = !1, e.$forceUpdate(), e.can("read") && p.getDir({
                            to: ""
                        }).then((function(a) {
                            e.$store.commit("setCwd", {
                                content: a.files,
                                location: a.location
                            }) 
                        }))["catch"]((function(a) {
                            return e.handleError(a)
                        }))
						
                    })), this.resumable.on("fileError", (function(e) {
                        e.file.uploadingError = !0
                    }))) : this.$dialog.alert({
                        type: "is-danger",
                        message: this.lang("Browser not supported.")
                    })
                },
                methods: {
                    closeWindow: function() {
                        var e = this;
                        this.activeUploads ? this.$dialog.confirm({
                            message: this.lang("Are you sure you want to stop all uploads?"),
                            type: "is-danger",
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Confirm"),
                            onConfirm: function() {
                                e.resumable.cancel(), e.visible = !1
                            }
                        }) : (this.visible = !1, this.resumable.cancel())
                    },
                    toggleWindow: function() {
                        this.progressVisible = !this.progressVisible
						console.log(`PAS?? toggleWindow`)
                    },
                    togglePause: function() {
                        this.paused ? (this.resumable.upload(), this.paused = !1) : (this.resumable.pause(), this.paused = !0)
						console.log(`PAS?? togglePause`)
                    }
                }
            },
            Ae = Ne,
            De = (o("f507"), Object(h["a"])(Ae, Ue, Ce, !1, null, "07f55d0a", null)),
            _e = De.exports,
            Fe = o("4eb5"),
            Le = o.n(Fe);
        n["default"].use(Le.a);
        var Ee = {
                name: "Browser",
                components: {
                    Menu: B,
                    Pagination: Pe,
                    Upload: _e
                },
                data: function() {
                    return {
                        dropZone: !1,
                        perPage: "",
                        currentPage: 1,
                        checked: [],
                        isLoading: !1,
                        defaultSort: ["data.name", "asc"],
                        files: [],
                        hasFilteredEntries: !1,
                        showAllEntries: !1
                    }
                },
                computed: {
                    breadcrumbs: function() {
                        var e = "",
                            a = [{
                                name: this.lang("Home"),
                                path: "/"
                            }];
                        return _.a.forEach(_.a.split(this.$store.state.cwd.location, "/"), (function(o) {
                            e += o + "/", a.push({
                                name: o,
                                path: e
                            })
                        })), _.a.filter(a, (function(e) {
                            return e.name
                        }))
                    },
                    content: function() {
                        return this.$store.state.cwd.content
                    },
                    totalCount: function() {
                        return Number(_.a.sumBy(this.$store.state.cwd.content, (function(e) {
                            return "file" == e.type || "dir" == e.type
                        })))
                    }
                },
                watch: {
                    $route: function(e) {
                        var a = this;
                        this.isLoading = !0, this.checked = [], this.currentPage = 1, p.changeDir({
                            to: e.query.cd
                        }).then((function(e) {
                            a.$store.commit("setCwd", {
                                content: a.filterEntries(e.files),
                                location: e.location
                            }), a.isLoading = !1
                        }))["catch"]((function(e) {
                            a.isLoading = !1, a.handleError(e)
                        }))
                    }
                },
                mounted: function() {
                    this.can("read") && this.loadFiles()
                },
                methods: {
                    toggleHidden: function() {
                        this.showAllEntries = !this.showAllEntries, this.loadFiles(), this.checked = []
                    },
                    filterEntries: function(e) {
                        var a = this,
                            o = this.$store.state.config.filter_entries;
                        if (this.hasFilteredEntries = !1, !this.showAllEntries && "undefined" !== typeof o && o.length > 0) {
                            var n = [];
                            return _.a.forEach(e, (function(e) {
                                var i = !1;
                                _.a.forEach(o, (function(o) {
                                    if ("undefined" !== typeof o && o.length > 0) {
                                        var n = o,
                                            t = n.endsWith("/") ? "dir" : "file";
                                        n = n.replace(/\/$/, "");
                                        var r = n.startsWith("/"),
                                            s = r ? "/" + e.path : e.name;
                                        n = r ? "/" + n : n, n = n.replace(/[.+?^${}()|[\]\\]/g, "\\$&").replace(/\*/g, ".$&");
                                        var l = new RegExp("^" + n + "$", "iu");
                                        if (e.type == t && l.test(s)) return i = !0, a.hasFilteredEntries = !0, !1
                                    }
                                })), i || n.push(e)
                            })), n
                        }
                        return e
                    },
                    loadFiles: function() {
                        var e = this;
                        p.getDir({
                            to: ""
                        }).then((function(a) {
                            e.$store.commit("setCwd", {
                                content: e.filterEntries(a.files),
                                location: a.location
                            })
                        }))["catch"]((function(a) {
                            return e.handleError(a)
                        }))
                    },
                    goTo: function(e) {
                        this.$router.push({
                            name: "browser",
                            query: {
                                cd: e
                            }
                        })["catch"]((function() {}))
                    },
                    getSelected: function() {
                        return _.a.reduce(this.checked, (function(e, a) {
                            return e.push(a), e
                        }), [])
                    },
                    itemClick: function(e) {
                        "dir" == e.type || "back" == e.type ? this.goTo(e.path) : this.can(["download"]) && this.hasPreview(e.path) ? this.preview(e) : this.can(["download"]) && this.download(e)
                    },
                    rightClick: function(e, a) {
                        "back" != e.type && (a.preventDefault(), this.$refs["ref-single-action-button-" + e.path].click())
                    },
                    selectDir: function() {
                        var e = this;
                        this.$modal.open({
                            parent: this,
                            hasModalCard: !0,
                            component: J,
                            events: {
                                selected: function(a) {
                                    e.goTo(a.path)
                                }
                            }
                        })
                    },
                    copy: function(e, a) {
                        var o = this;
                        this.$modal.open({
                            parent: this,
                            hasModalCard: !0,
                            component: J,
                            events: {
                                selected: function(e) {
                                    o.isLoading = !0, p.copyItems({
                                        destination: e.path,
                                        items: a ? [a] : o.getSelected()
                                    }).then((function() {
                                        o.isLoading = !1, o.loadFiles()
                                    }))["catch"]((function(e) {
                                        o.isLoading = !1, o.handleError(e)
                                    })), o.checked = []
                                }
                            }
                        })
                    },
                    move: function(e, a) {
                        var o = this;
                        this.$modal.open({
                            parent: this,
                            hasModalCard: !0,
                            component: J,
                            events: {
                                selected: function(e) {
                                    o.isLoading = !0, p.moveItems({
                                        destination: e.path,
                                        items: a ? [a] : o.getSelected()
                                    }).then((function() {
                                        o.isLoading = !1, o.loadFiles()
                                    }))["catch"]((function(e) {
                                        o.isLoading = !1, o.handleError(e)
                                    })), o.checked = []
                                }
                            }
                        })
                    },
                    batchDownload: function() {
                        var e = this,
                            a = this.getSelected();
                        this.isLoading = !0, p.batchDownload({
                            items: a
                        }).then((function(a) {
                            e.isLoading = !1, e.$dialog.alert({
                                message: e.lang("Your file is ready"),
                                confirmText: e.lang("Download"),
                                onConfirm: function() {
                                    window.open(n["default"].config.baseURL + "/batchdownload&uniqid=" + a.uniqid, "_blank")
                                }
                            })
                        }))["catch"]((function(a) {
                            e.isLoading = !1, e.handleError(a)
                        }))
                    },
                    download: function(e) {
                        window.open(this.getDownloadLink(e.path), "_blank")
                    },
                    search: function() {
                        var e = this;
                        this.$modal.open({
                            parent: this,
                            hasModalCard: !0,
                            component: we,
                            events: {
                                selected: function(a) {
                                    e.goTo(a.dir)
                                }
                            }
                        })
                    },
					preview: function(e) {
						var pathname 			= window.location.pathname;  
						var posicionUltimaBarra = pathname.lastIndexOf("/");
						var rutaRelativa 		= pathname.substring( posicionUltimaBarra + "/".length , pathname.length );
						 // divisi??n de URL y obtenci??n de nombre de archivo con la extensi??n de archivo
						var file 				= rutaRelativa.split ('/'). pop (); // eliminando la extensi??n y manteniendo solo el nombre del archivo
						var nombreArchivo      	= file.split ('.'). shift (); 
						var modulo 				= '';
						console.log('e.path: '+e.path);
						/* if(file == 'incidentes.php'){
							//modulo = 'incidentes';
						}else if(file == 'preventivos.php'){
							modulo = 'incidentes/';
						}else if(file == 'laboratorio.php'){
							modulo = 'laboratorio/';
						} else if(file == 'incidentestemp.php'){
							modulo = 'incidentestemp/';
						} */ 
						//console.log('modulo:'+modulo);
						//window.open("http://renacer.senadis.gob.pa/"+modulo+""+e.path,"Adjuntos","'width=400,height=250,top=120,left=100,toolbar=no,location=no,status=no,menubar=no'")
						window.open("https://toolkit.maxialatam.com/senadisdes/"+modulo+""+e.path,"Adjuntos","'width=400,height=250,top=120,left=100,toolbar=no,location=no,status=no,menubar=no'")
						/* var a = null;
						this.isImage(e.path) && (a = ue), this.isText(e.path) && (a = ie), this.$modal.open({ 
							parent: this,
							props: {
								item: e
							},
							hasModalCard: !0,
							component: a
						})  */
                    },
                    isArchive: function(e) {
                        return "file" == e.type && "zip" == e.name.split(".").pop()
                    },
                    unzip: function(e, a) {
                        var o = this;
                        this.$dialog.confirm({
                            message: this.lang("Are you sure you want to do this?"),
                            type: "is-danger",
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Unzip"),
                            onConfirm: function() {
                                o.isLoading = !0, p.unzipItem({
                                    item: a.path,
                                    destination: o.$store.state.cwd.location
                                }).then((function() {
                                    o.isLoading = !1, o.loadFiles()
                                }))["catch"]((function(e) {
                                    o.isLoading = !1, o.handleError(e)
                                })), o.checked = []
                            }
                        })
                    },
                    zip: function(e, a) {
                        var o = this;
                        this.$dialog.prompt({
                            message: this.lang("Name"),
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Create"),
                            inputAttrs: {
                                value: this.$store.state.config.default_archive_name,
                                placeholder: this.$store.state.config.default_archive_name,
                                maxlength: 100,
                                required: !1
                            },
                            onConfirm: function(e) {
                                e && (o.isLoading = !0, p.zipItems({
                                    name: e,
                                    items: a ? [a] : o.getSelected(),
                                    destination: o.$store.state.cwd.location
                                }).then((function() {
                                    o.isLoading = !1, o.loadFiles()
                                }))["catch"]((function(e) {
                                    o.isLoading = !1, o.handleError(e)
                                })), o.checked = [])
                            }
                        })
                    },
                    rename: function(e, a) {
                        var o = this;
                        this.$dialog.prompt({
                            message: this.lang("New name"),
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Rename"),
                            inputAttrs: {
                                value: a ? a.name : this.getSelected()[0].name,
                                maxlength: 100,
                                required: !1
                            },
                            onConfirm: function(e) {
                                o.isLoading = !0, p.renameItem({
                                    from: a.name,
                                    to: e,
                                    destination: o.$store.state.cwd.location
                                }).then((function() {
                                    o.isLoading = !1, o.loadFiles()
                                }))["catch"]((function(e) {
                                    o.isLoading = !1, o.handleError(e)
                                })), o.checked = []
                            }
                        })
                    },
                    create: function(e) {
                        var a = this;
                        this.$dialog.prompt({
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Create"),
                            inputAttrs: {
                                placeholder: "dir" == e ? "MyFolder" : "file.txt",
                                maxlength: 100,
                                required: !1
                            },
                            onConfirm: function(o) {
                                a.isLoading = !0, p.createNew({
                                    type: e,
                                    name: o,
                                    destination: a.$store.state.cwd.location
                                }).then((function() {
                                    a.isLoading = !1, a.loadFiles()
                                }))["catch"]((function(e) {
                                    a.isLoading = !1, a.handleError(e)
                                })), a.checked = []
                            }
                        })
                    },
                    remove: function(e, a) {
                        var o = this;
                        this.$dialog.confirm({
                            message: this.lang("Are you sure you want to do this?"),
                            type: "is-danger",
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Delete"),
                            onConfirm: function() {
                                o.isLoading = !0, p.removeItems({
                                    items: a ? [a] : o.getSelected()
                                }).then((function() {
                                    o.isLoading = !1, o.loadFiles()
                                }))["catch"]((function(e) {
                                    o.isLoading = !1, o.handleError(e)
                                })), o.checked = []
                            }
                        })
                    },
                    sortByName: function(e, a, o) {
                        return this.customSort(e, a, !o, "name")
                    },
                    sortBySize: function(e, a, o) {
                        return this.customSort(e, a, !o, "size")
                    },
                    sortByTime: function(e, a, o) {
                        return this.customSort(e, a, !o, "time")
                    },
                    customSort: function(e, a, o, n) {
                        return "back" == e.type ? -1 : "back" == a.type ? 1 : "dir" == e.type && "dir" != a.type ? -1 : "dir" == a.type && "dir" != e.type ? 1 : a.type == e.type ? e[n] === a[n] ? this.customSort(e, a, !1, "name") : _.a.isString(e[n]) ? e[n].localeCompare(a[n]) * (o ? -1 : 1) : (e[n] < a[n] ? -1 : 1) * (o ? -1 : 1) : void 0
                    }
                }
            },
            $e = Ee,
            xe = (o("7182"), Object(h["a"])($e, P, U, !1, null, "081c0a81", null)),
            Te = xe.exports,
            Re = function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", {
                    staticClass: "container"
                }, [o("Menu"), o("section", {
                    staticClass: "actions is-flex is-justify-between"
                }, [o("div", [o("a", {
                    on: {
                        click: e.addUser
                    }
                }, [o("b-icon", {
                    attrs: {
                        icon: "plus",
                        size: "is-small"
                    }
                }), e._v(" " + e._s(e.lang("New")) + " ")], 1)]), o("div", [o("Pagination", {
                    attrs: {
                        perpage: e.perPage
                    },
                    on: {
                        selected: function(a) {
                            e.perPage = a
                        }
                    }
                })], 1)]), o("b-table", {
                    attrs: {
                        data: e.users,
                        "default-sort": e.defaultSort,
                        paginated: e.perPage > 0,
                        "per-page": e.perPage,
                        "current-page": e.currentPage,
                        hoverable: !0,
                        loading: e.isLoading
                    },
                    on: {
                        "update:currentPage": function(a) {
                            e.currentPage = a
                        },
                        "update:current-page": function(a) {
                            e.currentPage = a
                        }
                    },
                    scopedSlots: e._u([{
                        key: "default",
                        fn: function(a) {
                            return [o("b-table-column", {
                                attrs: {
                                    label: e.lang("Name"),
                                    field: "name",
                                    sortable: ""
                                }
                            }, [o("a", {
                                on: {
                                    click: function(o) {
                                        return e.editUser(a.row)
                                    }
                                }
                            }, [e._v(" " + e._s(a.row.name) + " ")])]), o("b-table-column", {
                                attrs: {
                                    label: e.lang("Username"),
                                    field: "username",
                                    sortable: ""
                                }
                            }, [o("a", {
                                on: {
                                    click: function(o) {
                                        return e.editUser(a.row)
                                    }
                                }
                            }, [e._v(" " + e._s(a.row.username) + " ")])]), o("b-table-column", {
                                attrs: {
                                    label: e.lang("Permissions"),
                                    field: "role"
                                }
                            }, [e._v(" " + e._s(e.permissions(a.row.permissions)) + " ")]), o("b-table-column", {
                                attrs: {
                                    label: e.lang("Role"),
                                    field: "role",
                                    sortable: ""
                                }
                            }, [e._v(" " + e._s(e.lang(e.capitalize(a.row.role))) + " ")]), o("b-table-column", ["guest" != a.row.role ? o("a", {
                                on: {
                                    click: function(o) {
                                        return e.remove(a.row)
                                    }
                                }
                            }, [o("b-icon", {
                                attrs: {
                                    icon: "trash-alt",
                                    size: "is-small"
                                }
                            })], 1) : e._e()])]
                        }
                    }])
                })], 1)
            },
            Be = [],
            qe = (o("c740"), o("a434"), function() {
                var e = this,
                    a = e.$createElement,
                    o = e._self._c || a;
                return o("div", {
                    staticClass: "modal-card"
                }, [o("header", {
                    staticClass: "modal-card-head"
                }, [o("p", {
                    staticClass: "modal-card-title"
                }, [e._v(" " + e._s(e.user.name) + " ")])]), o("section", {
                    staticClass: "modal-card-body"
                }, [o("form", {
                    on: {
                        submit: function(a) {
                            return a.preventDefault(), e.save(a)
                        }
                    }
                }, ["user" == e.user.role || "admin" == e.user.role ? o("div", {
                    staticClass: "field"
                }, [o("b-field", {
                    attrs: {
                        label: e.lang("Role")
                    }
                }, [o("b-select", {
                    attrs: {
                        placeholder: e.lang("Role"),
                        expanded: "",
                        required: ""
                    },
                    model: {
                        value: e.formFields.role,
                        callback: function(a) {
                            e.$set(e.formFields, "role", a)
                        },
                        expression: "formFields.role"
                    }
                }, [o("option", {
                    key: "user",
                    attrs: {
                        value: "user"
                    }
                }, [e._v(" " + e._s(e.lang("User")) + " ")]), o("option", {
                    key: "admin",
                    attrs: {
                        value: "admin"
                    }
                }, [e._v(" " + e._s(e.lang("Admin")) + " ")])])], 1), o("b-field", {
                    attrs: {
                        label: e.lang("Username"),
                        type: e.formErrors.username ? "is-danger" : "",
                        message: e.formErrors.username
                    }
                }, [o("b-input", {
                    nativeOn: {
                        keydown: function(a) {
                            e.formErrors.username = ""
                        }
                    },
                    model: {
                        value: e.formFields.username,
                        callback: function(a) {
                            e.$set(e.formFields, "username", a)
                        },
                        expression: "formFields.username"
                    }
                })], 1), o("b-field", {
                    attrs: {
                        label: e.lang("Name"),
                        type: e.formErrors.name ? "is-danger" : "",
                        message: e.formErrors.name
                    }
                }, [o("b-input", {
                    nativeOn: {
                        keydown: function(a) {
                            e.formErrors.name = ""
                        }
                    },
                    model: {
                        value: e.formFields.name,
                        callback: function(a) {
                            e.$set(e.formFields, "name", a)
                        },
                        expression: "formFields.name"
                    }
                })], 1), o("b-field", {
                    attrs: {
                        label: e.lang("Password"),
                        type: e.formErrors.password ? "is-danger" : "",
                        message: e.formErrors.password
                    }
                }, [o("b-input", {
                    attrs: {
                        placeholder: "edit" == e.action ? e.lang("Leave blank for no change") : "",
                        "password-reveal": ""
                    },
                    nativeOn: {
                        keydown: function(a) {
                            e.formErrors.password = ""
                        }
                    },
                    model: {
                        value: e.formFields.password,
                        callback: function(a) {
                            e.$set(e.formFields, "password", a)
                        },
                        expression: "formFields.password"
                    }
                })], 1)], 1) : e._e(), o("b-field", {
                    attrs: {
                        label: e.lang("Homedir"),
                        type: e.formErrors.homedir ? "is-danger" : "",
                        message: e.formErrors.homedir
                    }
                }, [o("b-input", {
                    on: {
                        focus: e.selectDir
                    },
                    model: {
                        value: e.formFields.homedir,
                        callback: function(a) {
                            e.$set(e.formFields, "homedir", a)
                        },
                        expression: "formFields.homedir"
                    }
                })], 1), o("b-field", {
                    attrs: {
                        label: e.lang("Permissions")
                    }
                }, [o("div", {
                    staticClass: "block"
                }, [o("b-checkbox", {
                    model: {
                        value: e.permissions.read,
                        callback: function(a) {
                            e.$set(e.permissions, "read", a)
                        },
                        expression: "permissions.read"
                    }
                }, [e._v(" " + e._s(e.lang("Read")) + " ")]), o("b-checkbox", {
                    model: {
                        value: e.permissions.write,
                        callback: function(a) {
                            e.$set(e.permissions, "write", a)
                        },
                        expression: "permissions.write"
                    }
                }, [e._v(" " + e._s(e.lang("Write")) + " ")]), o("b-checkbox", {
                    model: {
                        value: e.permissions.upload,
                        callback: function(a) {
                            e.$set(e.permissions, "upload", a)
                        },
                        expression: "permissions.upload"
                    }
                }, [e._v(" " + e._s(e.lang("Upload")) + " ")]), o("b-checkbox", {
                    model: {
                        value: e.permissions.download,
                        callback: function(a) {
                            e.$set(e.permissions, "download", a)
                        },
                        expression: "permissions.download"
                    }
                }, [e._v(" " + e._s(e.lang("Download permission")) + " ")]), o("b-checkbox", {
                    model: {
                        value: e.permissions.batchdownload,
                        callback: function(a) {
                            e.$set(e.permissions, "batchdownload", a)
                        },
                        expression: "permissions.batchdownload"
                    }
                }, [e._v(" " + e._s(e.lang("Batch Download")) + " ")]), o("b-checkbox", {
                    model: {
                        value: e.permissions.zip,
                        callback: function(a) {
                            e.$set(e.permissions, "zip", a)
                        },
                        expression: "permissions.zip"
                    }
                }, [e._v(" " + e._s(e.lang("Zip")) + " ")])], 1)])], 1)]), o("footer", {
                    staticClass: "modal-card-foot"
                }, [o("button", {
                    staticClass: "button",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: function(a) {
                            return e.$parent.close()
                        }
                    }
                }, [e._v(" " + e._s(e.lang("Close")) + " ")]), o("button", {
                    staticClass: "button is-primary",
                    attrs: {
                        type: "button"
                    },
                    on: {
                        click: e.confirmSave
                    }
                }, [e._v(" " + e._s(e.lang("Save")) + " ")])])])
            }),
            Oe = [],
            Ve = (o("7db0"), {
                name: "UserEdit",
                props: ["user", "action"],
                data: function() {
                    return {
                        formFields: {
                            role: this.user.role,
                            name: this.user.name,
                            username: this.user.username,
                            homedir: this.user.homedir,
                            password: ""
                        },
                        formErrors: {},
                        permissions: {
                            read: !!_.a.find(this.user.permissions, (function(e) {
                                return "read" == e
                            })),
                            write: !!_.a.find(this.user.permissions, (function(e) {
                                return "write" == e
                            })),
                            upload: !!_.a.find(this.user.permissions, (function(e) {
                                return "upload" == e
                            })),
                            download: !!_.a.find(this.user.permissions, (function(e) {
                                return "download" == e
                            })),
                            batchdownload: !!_.a.find(this.user.permissions, (function(e) {
                                return "batchdownload" == e
                            })),
                            zip: !!_.a.find(this.user.permissions, (function(e) {
                                return "zip" == e
                            }))
                        }
                    }
                },
                computed: {},
                watch: {
                    "permissions.read": function(e) {
                        e || (this.permissions.write = !1, this.permissions.batchdownload = !1, this.permissions.zip = !1)
                    },
                    "permissions.write": function(e) {
                        e ? this.permissions.read = !0 : this.permissions.zip = !1
                    },
                    "permissions.download": function(e) {
                        e || (this.permissions.batchdownload = !1)
                    },
                    "permissions.batchdownload": function(e) {
                        e && (this.permissions.read = !0, this.permissions.download = !0)
                    },
                    "permissions.zip": function(e) {
                        e && (this.permissions.read = !0, this.permissions.write = !0)
                    }
                },
                methods: {
                    selectDir: function() {
                        var e = this;
                        this.formErrors.homedir = "", this.$modal.open({
                            parent: this,
                            hasModalCard: !0,
                            component: J,
                            events: {
                                selected: function(a) {
                                    e.formFields.homedir = a.path
                                }
                            }
                        })
                    },
                    getPermissionsArray: function() {
                        return _.a.reduce(this.permissions, (function(e, a, o) {
                            return 1 == a && e.push(o), e
                        }), [])
                    },
                    confirmSave: function() {
                        var e = this;
                        "guest" == this.formFields.role && this.getPermissionsArray().length ? this.$dialog.confirm({
                            message: this.lang("Are you sure you want to allow access to everyone?"),
                            type: "is-danger",
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Confirm"),
                            onConfirm: function() {
                                e.save()
                            }
                        }) : this.save()
                    },
                    save: function() {
                        var e = this,
                            a = "add" == this.action ? p.storeUser : p.updateUser;
                        a({
                            key: this.user.username,
                            role: this.formFields.role,
                            name: this.formFields.name,
                            username: this.formFields.username,
                            homedir: this.formFields.homedir,
                            password: this.formFields.password,
                            permissions: this.getPermissionsArray()
                        }).then((function(a) {
                            e.$toast.open({
                                message: e.lang("Updated"),
                                type: "is-success"
                            }), e.$emit("updated", a), e.$parent.close()
                        }))["catch"]((function(a) {
                            "object" != Object(A["a"])(a.response.data.data) && e.handleError(a), _.a.forEach(a.response.data, (function(a) {
                                _.a.forEach(a, (function(a, o) {
                                    e.formErrors[o] = e.lang(a), e.$forceUpdate()
                                }))
                            }))
                        }))
                    }
                }
            }),
            He = Ve,
            Me = Object(h["a"])(He, qe, Oe, !1, null, null, null),
            Ie = Me.exports,
            Ze = {
                name: "Users",
                components: {
                    Menu: B,
                    Pagination: Pe
                },
                data: function() {
                    return {
                        perPage: "",
                        currentPage: 1,
                        isLoading: !1,
                        defaultSort: ["name", "desc"],
                        users: []
                    }
                },
                mounted: function() {
                    var e = this;
                    p.listUsers().then((function(a) {
                        e.users = a
                    }))["catch"]((function(a) {
                        return e.handleError(a)
                    }))
                },
                methods: {
                    remove: function(e) {
                        var a = this;
                        this.$dialog.confirm({
                            message: this.lang("Are you sure you want to do this?"),
                            type: "is-danger",
                            cancelText: this.lang("Cancel"),
                            confirmText: this.lang("Confirm"),
                            onConfirm: function() {
                                p.deleteUser({
                                    username: e.username
                                }).then((function() {
                                    a.users = _.a.reject(a.users, (function(a) {
                                        return a.username == e.username
                                    })), a.$toast.open({
                                        message: a.lang("Deleted"),
                                        type: "is-success"
                                    })
                                }))["catch"]((function(e) {
                                    return a.handleError(e)
                                })), a.checked = []
                            }
                        })
                    },
                    permissions: function(e) {
                        return _.a.join(e, ", ")
                    },
                    addUser: function() {
                        var e = this;
                        this.$modal.open({
                            parent: this,
                            props: {
                                user: {
                                    role: "user"
                                },
                                action: "add"
                            },
                            hasModalCard: !0,
                            component: Ie,
                            events: {
                                updated: function(a) {
                                    e.users.push(a)
                                }
                            }
                        })
                    },
                    editUser: function(e) {
                        var a = this;
                        e.username ? this.$modal.open({
                            parent: this,
                            props: {
                                user: e,
                                action: "edit"
                            },
                            hasModalCard: !0,
                            component: Ie,
                            events: {
                                updated: function(e) {
                                    a.users.splice(_.a.findIndex(a.users, {
                                        username: e.username
                                    }), 1, e)
                                }
                            }
                        }) : this.handleError("Missing username")
                    }
                }
            },
            Ge = Ze,
            We = (o("abb7"), Object(h["a"])(Ge, Re, Be, !1, null, "de886392", null)),
            Ke = We.exports,
            Ye = o("2f62"),
            Je = (o("fb6a"), o("b680"), o("acd8"), o("c1df")),
            Qe = o.n(Je),
            Xe = {
                Selected: "Selected: {0} of {1}",
                "Uploading files": "Uploading {0}% of {1}",
                "File size error": "{0} is too large, please upload files less than {1}",
                "Upload failed": "{0} failed to upload",
                "Per page": "{0} Per Page",
                Folder: "Folder",
                "Login failed, please try again": "Login failed, please try again",
                "Already logged in": "Already logged in.",
                "Please enter username and password": "Please enter username and password.",
                "Not Found": "Not Found",
                "Not Allowed": "Not Allowed",
                "Please log in": "Please log in",
                "Unknown error": "Unknown error",
                "Add files": "Add files",
                New: "New",
                "New name": "New name",
                Username: "Username",
                Password: "Password",
                Login: "Log in",
                Logout: "Log out",
                Profile: "Profile",
                "No pagination": "No pagination",
                Time: "Time",
                Name: "Name",
                Size: "Size",
                Home: "Home",
                Copy: "Copy",
                Move: "Move",
                Rename: "Rename",
                Required: "Please fill out this field",
                Zip: "Zip",
                "Batch Download": "Batch Download",
                Unzip: "Unzip",
                Delete: "Delete",
                Download: "Download",
                "Copy link": "Copy link",
                Done: "Done",
                File: "File",
                "Drop files to upload": "Drop files to upload",
                Close: "Close",
                "Select Folder": "Select Folder",
                Users: "Users",
                Files: "Files",
                Role: "Role",
                Cancel: "Cancel",
                Paused: "Paused",
                Confirm: "Confirm",
                Create: "Create",
                User: "User",
                Admin: "Admin",
                Save: "Save",
                Read: "Read",
                Write: "Write",
                Upload: "Upload",
                Permissions: "Permissions",
                Homedir: "Home Folder",
                "Leave blank for no change": "Leave blank for no change",
                "Are you sure you want to do this?": "Are you sure you want to do this?",
                "Are you sure you want to allow access to everyone?": "Are you sure you want to allow access to everyone?",
                "Are you sure you want to stop all uploads?": "Are you sure you want to stop all uploads?",
                "Something went wrong": "Something went wrong",
                "Invalid directory": "Invalid directory",
                "This field is required": "This field is required",
                "Username already taken": "Username already taken",
                "User not found": "User not found",
                "Old password": "Old password",
                "New password": "New password",
                "Wrong password": "Wrong password",
                Updated: "Updated",
                Deleted: "Deleted",
                "Your file is ready": "Your file is ready",
                View: "View",
                Search: "Search",
                "Download permission": "Download",
                Guest: "Guest",
                "Show hidden": "Show hidden"
            },
            ea = Xe,
            aa = {
                Selected: "Seleccionados: {0} de {1}",
                "Uploading files": "Subiendo {0}% de {1}",
                "File size error": "{0} es demasiado grande, por favor suba ficheros menores a {1}",
                "Upload failed": "{0} no se pudo subir",
                "Per page": "{0} por p??gina",
                Folder: "Carpeta",
                "Login failed, please try again": "Inicio de sesi??n incorrecto, por favor pruebe de nuevo",
                "Already logged in": "Ya hab??a iniciado sesi??n.",
                "Please enter username and password": "Por favor, escriba nombre de usuario y contrase??a.",
                "Not Found": "No encontrado",
                "Not Allowed": "No permitido",
                "Please log in": "Por favor, inicie sesi??n",
                "Unknown error": "Error desconocido",
                "Add files": "A??adir ficheros",
                New: "Nuevo",
                "New name": "Nuevo nombre",
                Username: "Nombre de usuario",
                Password: "Contrase??a",
                Login: "Iniciar sesi??n",
                Logout: "Salir",
                Profile: "Perfil",
                "No pagination": "Sin paginaci??n",
                Time: "Fecha",
                Name: "Nombre",
                Size: "Tama??o",
                Home: "Carpeta principal",
                Copy: "Copiar",
                Move: "Mover",
                Rename: "Renombrar",
                Required: "Por favor, rellene este campo",
                Zip: "Comprimir",
                "Batch Download": "Descarga por lotes",
                Unzip: "Descomprimir",
                Delete: "Eliminar",
                Download: "Descargar",
                "Copy link": "Copiar enlace",
                Done: "Hecho",
                File: "Archivo",
                "Drop files to upload": "Soltar archivos para subir",
                Close: "Cerrar",
                "Select Folder": "Seleccionar carpeta",
                Users: "Usuarios",
                Files: "Ficheros",
                Role: "Rol",
                Cancel: "Cancelar",
                Paused: "Pausado",
                Confirm: "Confirmar",
                Create: "Crear",
                User: "Usuario",
                Admin: "Administrador",
                Save: "Guardar",
                Read: "Leer",
                Write: "Escribir",
                Upload: "Subir",
                Permissions: "Permisos",
                Homedir: "Carpeta inicial",
                "Leave blank for no change": "Dejar en blanco para no cambiar",
                "Are you sure you want to do this?": "??Seguro que quiere hacer esto?",
                "Are you sure you want to allow access to everyone?": "??Seguro que quiere permitir el acceso a todo el mundo?",
                "Are you sure you want to stop all uploads?": "??Seguro que quiere parar todas las subidas?",
                "Something went wrong": "Algo ha ido mal",
                "Invalid directory": "Carpeta incorrecta",
                "This field is required": "Este campo es obligatorio",
                "Username already taken": "El nombre de usuario ya existe",
                "User not found": "Usuario no encontrad",
                "Old password": "Contrase??a anterior",
                "New password": "Nueva contrase??a",
                "Wrong password": "Contrase??a incorrecta",
                Updated: "Actualizado",
                Deleted: "Eliminado",
                "Your file is ready": "Su fichero est?? listo",
                View: "View",
                Search: "Search",
                "Download permission": "Descargar",
                Guest: "Guest",
                "Show hidden": "Mostrar oculto"
            },
            oa = aa,
            na = {
                Selected: "Ausgew??hlt: {0} von {1}",
                "Uploading files": "Hochladen: {0}% von {1}",
                "File size error": "{0} ist zu gro??, bitte nur Dateien hochladen die kleiner als {1} sind.",
                "Upload failed": "{0} wurde(n) nicht hochgeladen",
                "Per page": "{0} pro Seite",
                Folder: "Ordner",
                "Login failed, please try again": "Anmeldung fehlgeschlagen, bitte nochmal versuchen.",
                "Already logged in": "Bereits angemeldet",
                "Please enter username and password": "Bitte Benutername und Passwort eingeben.",
                "Not Found": "Nicht gefunden",
                "Not Allowed": "Not Allowed",
                "Please log in": "Bitte anmelden",
                "Unknown error": "Unbekannter Fehler",
                "Add files": "Dateien hinzuf??gen",
                New: "Neu",
                "New name": "Neuer Name",
                Username: "Benutzername",
                Password: "Passwort",
                Login: "Anmelden",
                Logout: "Abmelden",
                Profile: "Profil",
                "No pagination": "Kein Seitenumbruch",
                Time: "Zeitpunkt",
                Name: "Name",
                Size: "Gr????e",
                Home: "Home",
                Copy: "Kopieren",
                Move: "Verschieben",
                Rename: "Umbenennen",
                Required: "Bitte dieses Feld ausf??llen",
                Zip: "Zip",
                "Batch Download": "Batch Download",
                Unzip: "Entpacken",
                Delete: "L??schen",
                Download: "Herunterladen",
                "Copy link": "Link kopieren",
                Done: "Fertig",
                File: "Datei",
                "Drop files to upload": "Dateien zum Hochladen hier ablegen",
                Close: "Schlie??en",
                "Select Folder": "Ordner ausw??hlen",
                Users: "Benutzer",
                Files: "Dateien",
                Role: "Rolle",
                Cancel: "Abbrechen",
                Paused: "Pausiert",
                Confirm: "Best??tigen",
                Create: "Erstellen",
                User: "Benutzer",
                Admin: "Admin",
                Save: "Speichern",
                Read: "Lesen",
                Write: "Schreiben",
                Upload: "Hochladen",
                Permissions: "Berechtigungen",
                Homedir: "Home Ordner",
                "Leave blank for no change": "Leer lassen um keine ??nderung vorzunehmen",
                "Are you sure you want to do this?": "Bist du sicher, dass du das tun willst?",
                "Are you sure you want to allow access to everyone?": "Sind Sie sicher, dass Sie jedem den Zugang erm??glichen wollen?",
                "Are you sure you want to stop all uploads?": "Bist du sicher, dass du alle Uploads stoppen willst?",
                "Something went wrong": "Etwas ist schief gelaufen",
                "Invalid directory": "Ung??ltiges Verzeichniss",
                "This field is required": "Dieses Feld ist erforderlich",
                "Username already taken": "Benutzername bereits vergeben",
                "User not found": "Benutzer nicht gefunden",
                "Old password": "Altes Passwort",
                "New password": "Neues Passwort",
                "Wrong password": "Falsches Passwort",
                Updated: "Aktualisiert",
                Deleted: "Gel??scht",
                "Your file is ready": "Deine Datei ist fertig",
                View: "View",
                Search: "Search",
                "Download permission": "Herunterladen",
                Guest: "Guest",
                "Show hidden": "Verborgenes zeigen"
            },
            ia = na,
            ta = {
                Selected: "Terpilih: {0} of {1}",
                "Uploading files": "Mengunggah {0}% of {1}",
                "File size error": "{0} terlalu besar, harap unggah file lebih kecil dari {1}",
                "Upload failed": "{0} gagal diunggah",
                "Per page": "{0} Per Halaman",
                Folder: "Berkas",
                "Login failed, please try again": "Gagal masuk, silakan coba lagi",
                "Already logged in": "Telah masuk.",
                "Please enter username and password": "Silahkan masukan nama pengguna dan kata sandi.",
                "Not Found": "Tidak ditemukan",
                "Not Allowed": "Tidak dibolehkan",
                "Please log in": "Silahkan masuk",
                "Unknown error": "Kesalahan tidak dikenal",
                "Add files": "Tambahkan berkas",
                New: "Baru",
                "New name": "Nama baru",
                Username: "Nama pengguna",
                Password: "Kata sandi",
                Login: "Masuk",
                Logout: "Keluar",
                Profile: "Profil",
                "No pagination": "Tidak ada halaman",
                Time: "Waktu",
                Name: "Nama",
                Size: "Ukuran",
                Home: "Rumah",
                Copy: "Salin",
                Move: "Pindah",
                Rename: "Ubah nama",
                Required: "Silakan isi bidang ini",
                Zip: "Zip",
                "Batch Download": "Unduh Batch",
                Unzip: "Unzip",
                Delete: "Hapus",
                Download: "Unduh",
                "Copy link": "Salin tautan",
                Done: "Selesai",
                File: "File",
                "Drop files to upload": "Letakkan file untuk diunggah",
                Close: "Tutup",
                "Select Folder": "Pilih Berkas",
                Users: "Pengguna",
                Files: "Arsip",
                Role: "Peran",
                Cancel: "Batal",
                Paused: "Dijeda",
                Confirm: "Konfirmasi",
                Create: "Buat",
                User: "Pengguna",
                Admin: "Admin",
                Save: "Simpan",
                Read: "Baca",
                Write: "Tulis",
                Upload: "Unggah",
                Permissions: "Izin",
                Homedir: "Direktori Rumah",
                "Leave blank for no change": "Biarkan kosong tanpa perubahan",
                "Are you sure you want to do this?": "Anda yakin ingin melakukan ini?",
                "Are you sure you want to allow access to everyone?": "Apakah anda yakin ingin mengizinkan akses ke semua orang?",
                "Are you sure you want to stop all uploads?": "Apakah anda yakin ingin menghentikan semua unggahan?",
                "Something went wrong": "Ada yang salah",
                "Invalid directory": "Direktori salah",
                "This field is required": "Bagian ini diperlukan",
                "Username already taken": "Nama pengguna sudah digunakan",
                "User not found": "Pengguna tidak ditemukan",
                "Old password": "Kata sandi lama",
                "New password": "Kata sandi baru",
                "Wrong password": "Kata sandi salah",
                Updated: "Diperbarui",
                Deleted: "Dihapus",
                "Your file is ready": "File Anda sudah siap",
                View: "View",
                Search: "Search",
                "Download permission": "Unduh",
                Guest: "Guest",
                "Show hidden": "Tunjukkan tersembunyi"
            },
            ra = ta,
            sa = {
                Selected: "Se??ilen: {0} - {1}",
                "Uploading files": "Y??kleniyor {0}% - {1}",
                "File size error": "{0} ??ok b??y??k, l??tfen {1} den k??????k dosya y??kleyin",
                "Upload failed": "{0} y??klenemedi",
                "Per page": "Sayfa ba????na {0} tane",
                Folder: "Klas??r",
                "Login failed, please try again": "Giri?? ba??ar??s??z. L??tfen tekrar deneyin",
                "Already logged in": "Zaten giri?? yap??lm????.",
                "Please enter username and password": "L??tfen kullan??c?? ad??n??z?? ve ??ifrenizi giriniz.",
                "Not Found": "Bulunamad??",
                "Not Allowed": "??zin verilmedi",
                "Please log in": "L??tfen giri?? yap??n",
                "Unknown error": "Bilinmeyen hata",
                "Add files": "Dosya Ekle",
                New: "Yeni",
                "New name": "Yeni Ad",
                Username: "Kullan??c?? Ad??",
                Password: "Parola",
                Login: "Giri??",
                Logout: "????k????",
                Profile: "Profil",
                "No pagination": "Sayfa Yok",
                Time: "Zaman",
                Name: "Ad",
                Size: "Boyut",
                Home: "Anasayfa",
                Copy: "Kopyala",
                Move: "Ta????",
                Rename: "Yeniden adland??r",
                Required: "L??tfen bu alan?? doldurun",
                Zip: "Zip",
                "Batch Download": "Batch ??ndirme",
                Unzip: "Zipi ????kart",
                Delete: "Sil",
                Download: "??ndir",
                "Copy link": "Ba??lant??y?? Kopyala",
                Done: "Tamam",
                File: "Dosya",
                "Drop files to upload": "Y??klemek i??in dosyay?? s??r??kle",
                Close: "Kapat",
                "Select Folder": "Klas??r Se??",
                Users: "Kullan??c??lar",
                Files: "Dosyalar",
                Role: "Rol",
                Cancel: "??ptal",
                Paused: "Durduruldu",
                Confirm: "Onayla",
                Create: "Olu??tur",
                User: "Kullan??c??",
                Admin: "Admin",
                Save: "Kaydet",
                Read: "Okuma",
                Write: "Yazma",
                Upload: "Y??kleme",
                Permissions: "??zimler",
                Homedir: "Ana Klas??r",
                "Leave blank for no change": "De??i??iklik yapmamak i??in bo?? b??rak??n",
                "Are you sure you want to do this?": "Bunu yapmak istedi??inizden emin misiniz?",
                "Are you sure you want to allow access to everyone?": "Herkese eri??ime izin vermek istedi??inizinden emin misiniz?",
                "Are you sure you want to stop all uploads?": "T??m y??klemeleri durdurmak istedi??inizden emin misiniz?",
                "Something went wrong": "Bir ??eyler yanl???? gitti",
                "Invalid directory": "Ge??ersiz dizin",
                "This field is required": "Bu alan gereklidir",
                "Username already taken": "Kullan??c?? ad?? ??nceden al??nm????",
                "User not found": "Kullan??c?? bulunamad??",
                "Old password": "Eski parola",
                "New password": "Yeni parola",
                "Wrong password": "parola hatal??",
                Updated: "G??ncellendi",
                Deleted: "Silindi",
                "Your file is ready": "Dosyan??z Haz??r",
                View: "View",
                Search: "Search",
                "Download permission": "??ndir",
                Guest: "Guest",
                "Show hidden": "Gizlenenleri g??ster"
            },
            la = sa,
            da = {
                Selected: "Pasirinkta: {0} i?? {1}",
                "Uploading files": "??keliama {0}% i?? {1}",
                "File size error": "{0} yra per didelis, pra??ome ??kelti ma??esnius failus nei {1}",
                "Upload failed": "{0} nepavyko ??kelti",
                "Per page": "{0} puslapyje",
                Folder: "Aplankas",
                "Login failed, please try again": "Nepavyko prisijungti, bandykite dar kart??",
                "Already logged in": "Jau esate prisijung??s.",
                "Please enter username and password": "Pra??ome ??vesti prisijungimo vard?? ir slapta??od??.",
                "Not Found": "Nerasta",
                "Not Allowed": "Neleid??iama",
                "Please log in": "Pra??ome prisijungti",
                "Unknown error": "Ne??inoma klaida",
                "Add files": "??kelti failus",
                New: "Naujas",
                "New name": "Naujas pavadinimas",
                Username: "Prisijungimo vardas",
                Password: "Slapta??odis",
                Login: "Prisijungti",
                Logout: "Atsijungti",
                Profile: "Profilis",
                "No pagination": "Nepuslapiuoti",
                Time: "Laikas",
                Name: "Pavadinimas",
                Size: "Dydis",
                Home: "Prad??ia",
                Copy: "Kopijuoti",
                Move: "Perkelti",
                Rename: "Pervadinti",
                Required: "Pra??ome u??pildyti ???? lauk??",
                Zip: "Zip",
                "Batch Download": "Atsi??sti paket??",
                Unzip: "I??pakuoti",
                Delete: "Pa??alinti",
                Download: "Atsi??sti",
                "Copy link": "Kopijuoti nuorod??",
                Done: "Atlikta",
                File: "Failas",
                "Drop files to upload": "Nutempti failus ??k??limui",
                Close: "U??verti",
                "Select Folder": "Pasirinkite aplank??",
                Users: "Vartotojai",
                Files: "Failai",
                Role: "Vaidmuo",
                Cancel: "At??aukti",
                Paused: "Pristabdytas",
                Confirm: "Patvirtinti",
                Create: "Sukurti",
                User: "Vartotojas",
                Admin: "Admin",
                Save: "I??saugoti",
                Read: "Nuskaityti",
                Write: "??ra??yti",
                Upload: "??kelti",
                Permissions: "Leidimai",
                Homedir: "Prad??ios aplankas",
                "Leave blank for no change": "Palikite tu????i??, jei nenorite nieko keisti",
                "Are you sure you want to do this?": "Ar J??s ??sitikin??s, kad norite tai atlikti?",
                "Are you sure you want to allow access to everyone?": "Ar J??s ??sitikin??s, kad norite atverti prieig?? prie fail?? bet kam?",
                "Are you sure you want to stop all uploads?": "Ar J??s ??sitikin??s, kad norite sustabdyti visus ??k??limus?",
                "Something went wrong": "Ka??kas negerai",
                "Invalid directory": "Neteisingas aplankas",
                "This field is required": "???? lauk?? privalote u??pildyti",
                "Username already taken": "Toks prisijungimo vardas jau egzistuoja",
                "User not found": "Vartotojas nerastas",
                "Old password": "Senas slapta??odis",
                "New password": "Naujas slapta??odis",
                "Wrong password": "Klaidingas slapta??odis",
                Updated: "Atnaujintas",
                Deleted: "I??trintas",
                "Your file is ready": "J??s?? failas paruo??tas",
                View: "View",
                Search: "Search",
                "Download permission": "Atsi??sti",
                Guest: "Guest",
                "Show hidden": "Rodyti pasl??pt??"
            },
            ca = da,
            ua = {
                Selected: "Selecionado: {0} de {1}",
                "Uploading files": "Fazendo o upload {0}% de {1}",
                "File size error": "{0} ?? muito grande, por favor fa??a o upload de arquivos menores do que {1}",
                "Upload failed": "{0} falhou ao fazer o upload",
                "Per page": "{0} Por P??gina",
                Folder: "Pasta",
                "Login failed, please try again": "Login falhou, por favor tente novamente.",
                "Already logged in": "J?? est?? logado.",
                "Please enter username and password": "Por favor entre com o nome de usu??rio e senha.",
                "Not Found": "N??o Encontrado",
                "Not Allowed": "N??o Autorizado",
                "Please log in": "Por favor, fa??a o login",
                "Unknown error": "Erro desconhecido",
                "Add files": "Adicionar arquivos",
                New: "Novo",
                "New name": "Novo nome",
                Username: "Nome de usu??rio",
                Password: "Senha",
                Login: "Entrar",
                Logout: "Sair",
                Profile: "Perfil",
                "No pagination": "Sem Pagina????o",
                Time: "Tempo",
                Name: "Nome",
                Size: "Tamanho",
                Home: "P??gina inicial",
                Copy: "Copiar",
                Move: "Mover",
                Rename: "Renomear",
                Required: "Por favor preencha este campo",
                Zip: "Comprimir",
                "Batch Download": "Download em lotes",
                Unzip: "Descomprimir",
                Delete: "Deletar",
                Download: "Download",
                "Copy link": "Copiar link",
                Done: "Finalizado",
                File: "Arquivo",
                "Drop files to upload": "Arraste arquivos para fazer o upload",
                Close: "Fechar",
                "Select Folder": "Selecionar Pasta",
                Users: "Usu??rios",
                Files: "Arquivos",
                Role: "Posi????o",
                Cancel: "Cancelar",
                Paused: "Parado",
                Confirm: "Confirmar",
                Create: "Criar",
                User: "Usu??rio",
                Admin: "Administrador",
                Save: "Salvar",
                Read: "Ler",
                Write: "Escrever",
                Upload: "Upload",
                Permissions: "Permiss??es",
                Homedir: "Pasta da p??gina inicial",
                "Leave blank for no change": "Deixe em branco para n??o fazer nenhuma altera????o",
                "Are you sure you want to do this?": "Tem certeza que deseja fazer isto?",
                "Are you sure you want to allow access to everyone?": "Tem certeza que deseja permitir o acesso a todos?",
                "Are you sure you want to stop all uploads?": "Tem certeza que deseja parar todos os uploads?",
                "Something went wrong": "Algo deu errado",
                "Invalid directory": "Diret??rio inv??lido",
                "This field is required": "Este arquivo ?? obrigat??rio",
                "Username already taken": "O nome de usu??rio j?? existe",
                "User not found": "Usu??rio n??o encontrado",
                "Old password": "Senha atual",
                "New password": "Nova senha",
                "Wrong password": "Senha inv??lida",
                Updated: "Atualizado",
                Deleted: "Excluido",
                "Your file is ready": "Seu arquivo est?? pronto",
                View: "View",
                Search: "Search",
                "Download permission": "Download",
                Guest: "Guest",
                "Show hidden": "Mostrar oculto"
            },
            pa = ua,
            ma = {
                Selected: "Geselecteerd: {0} van {1}",
                "Uploading files": "Ge??pload: {0}% van {1}",
                "File size error": "{0} is te groot, maximale grootte is {1}",
                "Upload failed": "{0} upload mislukt",
                "Per page": "{0} per pagina",
                Folder: "Map",
                "Login failed, please try again": "Login mislukt, probeer het nog eens...",
                "Already logged in": "U bent al ingelogd...",
                "Please enter username and password": "Geef gebruikersnaam en wachtwoord",
                "Not Found": "Niet gevonden",
                "Not Allowed": "Niet toegestaan",
                "Please log in": "Log eerst in",
                "Unknown error": "Onbekende fout",
                "Add files": "Bestanden toevoegen",
                New: "Nieuw",
                "New name": "Nieuwe naam",
                Username: "Gebruikersnaam",
                Password: "Wachtwoord",
                Login: "Log in",
                Logout: "Log uit",
                Profile: "Profiel",
                "No pagination": "Geen onderverdeling in pagina's",
                Time: "Tijd",
                Name: "Naam",
                Size: "Grootte",
                Home: "Thuismap",
                Copy: "Kopieer",
                Move: "Verplaats",
                Rename: "Hernoem",
                Required: "Vereist veld",
                Zip: "Zip",
                "Batch Download": "Groepsdownload",
                Unzip: "Uitpakken",
                Delete: "Verwijder",
                Download: "Download",
                "Copy link": "Kopieer link",
                Done: "Klaar",
                File: "Bestand",
                "Drop files to upload": "Sleep bestanden hierheen om ze te uploaden",
                Close: "Sluiten",
                "Select Folder": "Selecteer Map",
                Users: "Gebruikers",
                Files: "Bestanden",
                Role: "Rol",
                Cancel: "Afbreken",
                Paused: "Gepauseerd",
                Confirm: "Bevestig",
                Create: "Nieuw",
                User: "Gebruiker",
                Admin: "Beheerder",
                Save: "Opslaan",
                Read: "Lezen",
                Write: "Schrijven",
                Upload: "Uploaden",
                Permissions: "Permissies",
                Homedir: "Thuismap",
                "Leave blank for no change": "Laat leeg om ongewijzigd te laten",
                "Are you sure you want to do this?": "Weet u het zeker?",
                "Are you sure you want to allow access to everyone?": "Weet u zeker dat u iedereen toegang wil geven?",
                "Are you sure you want to stop all uploads?": "Weet u zeker dat u alle uploads wil stoppen?",
                "Something went wrong": "Er is iets foutgegaan",
                "Invalid directory": "Ongeldige map",
                "This field is required": "This field is required",
                "Username already taken": "Naam is al in gebruik",
                "User not found": "Gebruiker niet gevonden",
                "Old password": "Oud wachtwoord",
                "New password": "Nieuw wachtwoord",
                "Wrong password": "Fout wachtwoord",
                Updated: "Aangepast",
                Deleted: "Verwijderd",
                "Your file is ready": "Uw bestand is verwerkt",
                View: "View",
                Search: "Search",
                "Download permission": "Download",
                Guest: "Guest",
                "Show hidden": "Verborgen weergeven"
            },
            fa = ma,
            ha = {
                Selected: "?????????: {1} ??????????????? {0} ???",
                "Uploading files": "????????? {1} ?????? {0}%",
                "File size error": "{0} ????????????, ????????????????????? {1}",
                "Upload failed": "{0} ????????????",
                "Per page": "?????? {0} ???",
                Folder: "?????????",
                "Login failed, please try again": "????????????, ?????????",
                "Already logged in": "????????????",
                "Please enter username and password": "??????????????????????????????",
                "Not Found": "?????????",
                "Not Allowed": "?????????",
                "Please log in": "?????????",
                "Unknown error": "????????????",
                "Add files": "????????????",
                New: "??????",
                "New name": "?????????",
                Username: "?????????",
                Password: "??????",
                Login: "??????",
                Logout: "??????",
                Profile: "????????????",
                "No pagination": "?????????",
                Time: "??????",
                Name: "??????",
                Size: "??????",
                Home: "??????",
                Copy: "??????",
                Move: "??????",
                Rename: "?????????",
                Required: "??????????????????",
                Zip: "??????",
                "Batch Download": "????????????",
                Unzip: "?????????",
                Delete: "??????",
                Download: "??????",
                "Copy link": "????????????",
                Done: "??????",
                File: "??????",
                "Drop files to upload": "????????????????????????",
                Close: "??????",
                "Select Folder": "???????????????",
                Users: "??????",
                Files: "??????",
                Role: "??????",
                Cancel: "??????",
                Paused: "??????",
                Confirm: "??????",
                Create: "??????",
                User: "??????",
                Admin: "?????????",
                Save: "??????",
                Read: "??????",
                Write: "??????",
                Upload: "??????",
                Permissions: "??????",
                Homedir: "?????????",
                "Leave blank for no change": "?????????????????????",
                "Are you sure you want to do this?": "??????????????????????",
                "Are you sure you want to allow access to everyone?": "?????????????????????????????????????",
                "Are you sure you want to stop all uploads?": "?????????????????????????????????????",
                "Something went wrong": "????????????",
                "Invalid directory": "????????????",
                "This field is required": "????????????????????????",
                "Username already taken": "?????????????????????",
                "User not found": "???????????????",
                "Old password": "?????????",
                "New password": "?????????",
                "Wrong password": "????????????",
                Updated: "?????????",
                Deleted: "?????????",
                "Your file is ready": "?????????????????????",
                View: "??????",
                Search: "??????",
                "Download permission": "??????",
                Guest: "??????",
                "Show hidden": "????????????"
            },
            ga = ha,
            wa = {
                Selected: "??????????????: {0} ???? {1}",
                "Uploading files": "?????????????? {0}% ???? {1}",
                "File size error": "{0} ?? ???????????? ??????????, ????????, ???????????? ?????????????? ????-?????????? ???? {1}",
                "Upload failed": "{0} ???????????? ?????? ??????????????",
                "Per page": "{0} ???? ????????????????",
                Folder: "??????????",
                "Login failed, please try again": "???????????? ?????? ????????????????, ???????????????? ????????????",
                "Already logged in": "???????? ?????? ????????????.",
                "Please enter username and password": "???????? ???????????????? ?????????????????????????? ?????? ?? ????????????.",
                "Not Found": "???? ?? ????????????????",
                "Not Allowed": "???? ?? ??????????????????",
                "Please log in": "???????? ?????????????? ????",
                "Unknown error": "???????????????????? ????????????",
                "Add files": "???????????? ??????????????",
                New: "????????",
                "New name": "???????? ??????",
                Username: "?????????????????????????? ??????",
                Password: "????????????",
                Login: "????????????????",
                Logout: "??????????",
                Profile: "????????????",
                "No pagination": "???????? ??????????????????",
                Time: "????????",
                Name: "??????",
                Size: "????????????",
                Home: "????????????",
                Copy: "????????????????",
                Move: "????????????",
                Rename: "????????????????????????",
                Required: "????????, ?????????????????? ???????? ????????",
                Zip: "??????????",
                "Batch Download": "?????????????? ??????????????????",
                Unzip: "????????????????????????",
                Delete: "??????????????????",
                Download: "??????????????????",
                "Copy link": "?????????????? ????????",
                Done: "??????????????????",
                File: "????????",
                "Drop files to upload": "?????????????? ???? ?????????????? ???? ??????????????",
                Close: "??????????????",
                "Select Folder": "???????????? ??????????",
                Users: "????????????????????",
                Files: "??????????????",
                Role: "??????????",
                Cancel: "??????????",
                Paused: "??????????",
                Confirm: "????????????????????????",
                Create: "????????????",
                User: "????????????????????",
                Admin: "??????????????????????????",
                Save: "????????????",
                Read: "????????",
                Write: "??????????????",
                Upload: "????????",
                Permissions: "????????????????????",
                Homedir: "???????????? ????????????????????",
                "Leave blank for no change": "???????????????? ????????????, ???? ???? ???????? ??????????????",
                "Are you sure you want to do this?": "?????????????? ???? ??????, ???? ???????????? ???? ?????????????????? ?????????",
                "Are you sure you want to allow access to everyone?": "?????????????? ???? ??????, ???? ???????????? ???? ?????????????????? ???????????? ???? ?????????????",
                "Are you sure you want to stop all uploads?": "?????????????? ???? ??????, ???? ???????????? ???? ???????????? ???????????? ?????????????????",
                "Something went wrong": "???????? ???? ????????????",
                "Invalid directory": "?????????????????? ????????????????????",
                "This field is required": "???????? ???????? ?? ????????????????????????",
                "Username already taken": "?????????????????????????? ?????? ???????? ?? ??????????",
                "User not found": "?????????????????????? ???? ?? ??????????????",
                "Old password": "?????????? ????????????",
                "New password": "???????? ????????????",
                "Wrong password": "???????????? ????????????",
                Updated: "????????????????",
                Deleted: "??????????????",
                "Your file is ready": "?????????? ???????? ?? ??????????",
                View: "??????????????",
                Search: "??????????",
                "Download permission": "??????????",
                Guest: "????????",
                "Show hidden": "?????????????????? ???? ????????????"
            },
            va = wa,
            ba = {
                Selected: "Izabrano: {0} od {1}",
                "Uploading files": "Slanje {0}% od {1}",
                "File size error": "{0} fajl je preveliki, molim po??aljite fajl manji od {1}",
                "Upload failed": "{0} gre??ka kod slanja",
                "Per page": "{0} Po strani",
                Folder: "Folder",
                "Login failed, please try again": "Neuspe??na prijava, probajte ponovo",
                "Already logged in": "Ve?? prijavljen.",
                "Please enter username and password": "Unesite korisni??ko ime i lozinku.",
                "Not Found": "Nije prona??en",
                "Not Allowed": "Nije dozvoljeno",
                "Please log in": "Molim prijavite se",
                "Unknown error": "Nepoznata gre??ka",
                "Add files": "Dodaj fajlove",
                New: "Novi",
                "New name": "Novo ime",
                Username: "Korisni??ko ime",
                Password: "Lozinka",
                Login: "Prijavi se",
                Logout: "Odjavi se",
                Profile: "Profil",
                "No pagination": "Bez listanja po strani",
                Time: "Vreme",
                Name: "Ime",
                Size: "Veli??ina",
                Home: "Po??etna",
                Copy: "Kopiraj",
                Move: "Premesti",
                Rename: "Promeni ime",
                Required: "Ovo polje je obavezno",
                Zip: "Zip",
                "Batch Download": "Grupno preuzimanje",
                Unzip: "Unzip",
                Delete: "Obri??i",
                Download: "Preuzmi",
                "Copy link": "Kopiraj link",
                Done: "Gotovo",
                File: "Fajl",
                "Drop files to upload": "Spusti fajlove za slanje",
                Close: "Zatvori",
                "Select Folder": "Izaberi folder",
                Users: "Korisnici",
                Files: "Fajlovi",
                Role: "Prava",
                Cancel: "Otka??i",
                Paused: "Pauzirano",
                Confirm: "Potvrdi",
                Create: "Kreiraj",
                User: "Korisnik",
                Admin: "Administrator",
                Save: "Sa??uvaj",
                Read: "??itanje",
                Write: "Upis",
                Upload: "Slanje",
                Permissions: "Prava pristupa",
                Homedir: "Po??etni folder",
                "Leave blank for no change": "Ostavi prazno da ne promeni??",
                "Are you sure you want to do this?": "Da li ste sigurni?",
                "Are you sure you want to allow access to everyone?": "Da li ste sigurni da ??elite da dozvolite pristup svima?",
                "Are you sure you want to stop all uploads?": "Da li ste sigurni da ??elite da prekinete sva slanja?",
                "Something went wrong": "Dogodila se nepoznata gre??ka",
                "Invalid directory": "Pogre??an folder",
                "This field is required": "Ovo polje je obavezno",
                "Username already taken": "Korisni??ko ime ve?? postoji",
                "User not found": "Korisnik nije prona??en",
                "Old password": "Stara lozinka",
                "New password": "Nova lozinka",
                "Wrong password": "Pogre??na lozinka",
                Updated: "Izmenjeno",
                Deleted: "Obrisano",
                "Your file is ready": "Va?? fajl je spreman",
                View: "View",
                Search: "Search",
                "Download permission": "Preuzimanje",
                Guest: "Gost",
                "Show hidden": "Prika??i skriveno"
            },
            ya = ba,
            ka = {
                Selected: "Selectionn?? : {0} sur {1}",
                "Uploading files": "Upload {0}% sur {1}",
                "File size error": "{0} est trop volumineux, merci d'uploader des fichiers inf??rieurs ?? {1}",
                "Upload failed": "{0} ??chec(s) d'envoi",
                "Per page": "{0} par page",
                Folder: "Dossier",
                "Login failed, please try again": "Identification ??chou??, veuillez r??essayer...",
                "Already logged in": "Vous ??tes d??j?? connect??.",
                "Please enter username and password": "Saisissez votre nom d'utilisateur et votre mot de passe.",
                "Not Found": "Introuvable",
                "Not Allowed": "Non autoris??",
                "Please log in": "Merci de vous connecter",
                "Unknown error": "Erreur inconnue",
                "Add files": "Ajout de fichier",
                New: "Nouveau",
                "New name": "Nouveau nom",
                Username: "Nom d'utilisateur",
                Password: "Mot de passe",
                Login: "Connexion",
                Logout: "D??connexion",
                Profile: "Profil",
                "No pagination": "Pas de pagination",
                Time: "Date",
                Name: "Nom",
                Size: "Taille",
                Home: "Accueil",
                Copy: "Copier",
                Move: "D??placer",
                Rename: "Renommer",
                Required: "Merci de remplir ce champ",
                Zip: "Compresser",
                "Batch Download": "T??l??charger par lot",
                Unzip: "D??compresser",
                Delete: "Supprimer",
                Download: "T??l??charger",
                "Copy link": "Copier le lien",
                Done: "Fait",
                File: "Fichier",
                "Drop files to upload": "Glisser votre fichier pour l'uploader",
                Close: "Fermer",
                "Select Folder": "Selectionner le dossier",
                Users: "Utilisateur",
                Files: "Fichiers",
                Role: "R??le",
                Cancel: "Annuler",
                Paused: "En pause",
                Confirm: "Confirmer",
                Create: "Cr??er",
                User: "Utilisateur",
                Admin: "Administrateur",
                Save: "Enregistrer",
                Read: "Lire",
                Write: "??crire",
                Upload: "Uploader",
                Permissions: "Permissions",
                Homedir: "Dossier principal",
                "Leave blank for no change": "Laisser vide si pas de modification",
                "Are you sure you want to do this?": "??tes-vous s??r de vouloir faire ceci ?",
                "Are you sure you want to allow access to everyone?": "??tes-vous s??r de vouloir autoriser l'acc??s ?? tout le monde ?",
                "Are you sure you want to stop all uploads?": "??tes-vous s??r de vouloir arr??ter tous vos envois ?",
                "Something went wrong": "Quelque chose a mal tourn??",
                "Invalid directory": "Dossier invalide",
                "This field is required": "Ce champ est obligatoire",
                "Username already taken": "Nom d'utilisateur d??j?? utilis??",
                "User not found": "Utilisateur introuvable",
                "Old password": "Ancien mot de passe",
                "New password": "Nouveau mot de passe",
                "Wrong password": "Mot de passe incorrect",
                Updated: "Mis ?? jour",
                Deleted: "Supprim??",
                "Your file is ready": "Votre fichier est pr??t",
                View: "View",
                Search: "Search",
                "Download permission": "T??l??charger",
                Guest: "Guest",
                "Show hidden": "Afficher masqu??"
            },
            za = ka,
            Pa = {
                Selected: "Vybran??: {0} z {1}",
                "Uploading files": "Nahr??vam {0}% z {1}",
                "File size error": "{0} je pr??li?? ve??k??, nahr??vajte s??bory men??ie ako {1}",
                "Upload failed": "{0} sa nepodarilo nahra??",
                "Per page": "{0} na str??nku",
                Folder: "Adres??r",
                "Login failed, please try again": "Prihl??senie ne??spe??n??, sk??ste to znova",
                "Already logged in": "U?? ste prihl??sen??.",
                "Please enter username and password": "Zadajte prihlasovacie meno a heslo.",
                "Not Found": "Nen??jden??",
                "Not Allowed": "Nepovolen??",
                "Please log in": "Prihl??ste sa",
                "Unknown error": "Nezn??ma chyba",
                "Add files": "Prida?? s??bory",
                New: "Nov??",
                "New name": "Nov?? meno",
                Username: "Prihlasovacie meno",
                Password: "Heslo",
                Login: "Prihl??si?? sa",
                Logout: "Odhl??si?? sa",
                Profile: "Profil",
                "No pagination": "Bez str??nkovania",
                Time: "??as",
                Name: "Meno",
                Size: "Ve??kos??",
                Home: "Hlavn?? adres??r",
                Copy: "Kop??rova??",
                Move: "Presun????",
                Rename: "Premenova??",
                Required: "Vypl??te toto pole",
                Zip: "Archivova?? do zip",
                "Batch Download": "Hromadn?? s??ahovanie",
                Unzip: "Rozbali?? zip arch??v",
                Delete: "Vymaza??",
                Download: "Stiahnu??",
                "Copy link": "Skop??rova?? odkaz",
                Done: "Hotovo",
                File: "S??bor",
                "Drop files to upload": "Pre nahratie presu??te s??bory sem",
                Close: "Zavrie??",
                "Select Folder": "Vyberte adres??r",
                Users: "Pou????vatelia",
                Files: "S??bory",
                Role: "Typ ????tu",
                Cancel: "Zru??i??",
                Paused: "Pozastaven??",
                Confirm: "Potvrdi??",
                Create: "Vytvori??",
                User: "Pou????vate??",
                Admin: "Admin",
                Save: "Ulo??i??",
                Read: "????tanie",
                Write: "Zapisovanie",
                Upload: "Nahr??vanie",
                Permissions: "Opr??vnenia",
                Homedir: "Hlavn?? adres??r",
                "Leave blank for no change": "Ak nechcete zmeni?? nechajte pr??zdne",
                "Are you sure you want to do this?": "Naozaj to chcete urobi???",
                "Are you sure you want to allow access to everyone?": "Naozaj chcete povoli?? pr??stup bez hesla?",
                "Are you sure you want to stop all uploads?": "Naozaj chcete zastavi?? v??etky nahr??vania?",
                "Something went wrong": "Nie??o sa pokazilo",
                "Invalid directory": "Neplatn?? adres??r",
                "This field is required": "Toto pole je povinn??",
                "Username already taken": "Toto prihlasovacie meno sa u?? pou????va",
                "User not found": "Pou????vate?? sa nena??iel",
                "Old password": "Star?? heslo",
                "New password": "Nov?? heslo",
                "Wrong password": "Zl?? heslo",
                Updated: "Aktualizovan??",
                Deleted: "Vymazan??",
                "Your file is ready": "V???? s??bor je pripraven??",
                View: "Zobrazi??",
                Search: "Vyh??ad??vanie",
                "Download permission": "S??ahovanie",
                Guest: "Hos??",
                "Show hidden": "Zobrazi?? skryt??"
            },
            Ua = Pa,
            Ca = {
                Selected: "Wybrano: {0} z {1}",
                "Uploading files": "Przesy??anie {0}% z {1}",
                "B????d rozmiaru pliku": "{0} jest za du??y, prze??lij mniejszy plik {1}",
                "Upload failed": "{0} plik??w nie uda??o si?? przes??a??",
                "Per page": "{0} Na stron??",
                Folder: "Folder",
                "Login failed, please try again": "Z??y login lub has??o.",
                "Already logged in": "Already logged in.",
                "Please enter username and password": "Wpisz login i has??o.",
                "Not Found": "Nie znaleziono",
                "Not Allowed": "Nie dozwolony",
                "Please log in": "Prosz?? si?? zalogowa??",
                "Unknown error": "Nieznany b????d",
                "Add files": "Dodaj plik",
                New: "Nowy",
                "New name": "Nowa nazwa",
                Username: "Login",
                Password: "Has??o",
                Login: "Zaloguj",
                Logout: "Wyloguj",
                Profile: "Profile",
                "No pagination": "Brak podzia??u na strony",
                Time: "Czas",
                Name: "Nazwa",
                Size: "Rozmiar",
                Home: "Folder g????wny",
                Copy: "Kopiuj",
                Move: "Przenie??",
                Rename: "Zmie?? nazw??",
                Required: "Prosz?? wype??ni?? to pole",
                Zip: "Zip",
                "Batch Download": "Pobieranie zbiorcze",
                Unzip: "Rozpakuj",
                Delete: "Usu??",
                Download: "Download",
                "Copy link": "Kopiuj link",
                Done: "Done",
                File: "Plik",
                "Drop files to upload": "Upu???? pliki do przes??ania",
                Close: "Zamknij",
                "Select Folder": "Wybierz katalog",
                Users: "U??ytkownik",
                Files: "Pliki",
                Role: "Role",
                Cancel: "Anuluj",
                Paused: "Pauza",
                Confirm: "Potwierd??",
                Create: "Stw??rz",
                User: "U??ytkownik",
                Admin: "Admin",
                Save: "Zapisz",
                Read: "Podgl??d",
                Write: "Zapisz",
                Upload: "Upload",
                Permissions: "Uprawnienia",
                Homedir: "Folder G????wny",
                "Leave blank for no change": "Pozostaw puste, bez zmian",
                "Are you sure you want to do this?": "Jeste?? pewny ??e chcesz to zrobi???",
                "Are you sure you want to allow access to everyone?": "Czy na pewno chcesz zezwoli?? na dost??p wszystkim?",
                "Are you sure you want to stop all uploads?": "Czy na pewno chcesz zatrzyma?? wszystkie przesy??ane pliki?",
                "Something went wrong": "Co?? posz??o nie tak",
                "Invalid directory": "Nieprawid??owy katalog",
                "This field is required": "To pole jest wymagane",
                "Username already taken": "Nazwa u??ytkownika zaj??ta",
                "User not found": "U??ytkownik nie znaleziony",
                "Old password": "Stare has??o",
                "New password": "Nowe has??o",
                "Wrong password": "Nieprawid??owe has??o",
                Updated: "Zaktualizowano",
                Deleted: "Usuni??te",
                "Your file is ready": "Tw??j plik jest gotowy",
                View: "Podgl??d",
                Search: "Szukaj",
                "Download permission": "Download",
                Guest: "Go????",
                "Show hidden": "Poka?? ukryte"
            },
            ja = Ca,
            Sa = {
                Selected: "Selezionati: {0} di {1}",
                "Uploading files": "Caricamento {0}% di {1}",
                "File size error": "{0} File troppo grande. Dimensione massima consentita {1}",
                "Upload failed": "{0} Caricamento fallito",
                "Per page": "{0} per pagina",
                Folder: "Cartella",
                "Login failed, please try again": "Username o password non corretti",
                "Already logged in": "Sei gi?? connesso",
                "Please enter username and password": "Inserisci username e password",
                "Not Found": "Nessun risultato",
                "Not Allowed": "Non consentito",
                "Please log in": "Per cortesia autenticati",
                "Unknown error": "Errore sconosciuto",
                "Add files": "Aggiungi files",
                New: "Nuovo",
                "New name": "Nuovo nome",
                Username: "Username",
                Password: "Password",
                Login: "Entra",
                Logout: "Esci",
                Profile: "Cambia password",
                "No pagination": "Uno per pagina",
                Time: "Data",
                Name: "Nome",
                Size: "Dimensione",
                Home: "Cartella principale",
                Copy: "Copia",
                Move: "Sposta",
                Rename: "Rinomina",
                Required: "Campo obbligatorio",
                Zip: "Comprimi",
                "Batch Download": "Scarica batch",
                Unzip: "Estrai",
                Delete: "Elimina",
                Download: "Scarica",
                "Copy link": "Copia collegamento",
                Done: "Completato",
                File: "File",
                "Drop files to upload": "Trascina i files che vuoi caricare",
                Close: "Chiudi",
                "Select Folder": "Seleziona cartella",
                Users: "Utenti",
                Files: "Files",
                Role: "Ruolo",
                Cancel: "Annulla",
                Paused: "Sospeso",
                Confirm: "Conferma",
                Create: "Crea",
                User: "Utente",
                Admin: "Amministratore",
                Save: "Salva",
                Read: "Lettura",
                Write: "Scrittura",
                Upload: "Caricamento",
                Permissions: "Permessi",
                Homedir: "Cartella principale",
                "Leave blank for no change": "Lascia in bianco per non effettuare modifiche",
                "Are you sure you want to do this?": "Sei sicuro di voler eliminare gli elementi selezionati?",
                "Are you sure you want to allow access to everyone?": "Sei sicuro di voler consentire libero accesso a tutti?",
                "Are you sure you want to stop all uploads?": "Vuoi sospendere tutti i caricamenti?",
                "Something went wrong": "Qualcosa ?? andato storto",
                "Invalid directory": "Cartella non corretta",
                "This field is required": "Questo campo ?? obbligatorio",
                "Username already taken": "Username gi?? esistente",
                "User not found": "Utente non trovato",
                "Old password": "Vecchia password",
                "New password": "Nuova password",
                "Wrong password": "Password errata",
                Updated: "Aggiornato",
                Deleted: "Eliminato",
                "Your file is ready": "Il tuo file ?? disponibile",
                View: "Leggi",
                Search: "Cerca",
                "Download permission": "Scarica",
                Guest: "Guest",
                "Show hidden": "Mostra nascosto"
            },
            Na = Sa,
            Aa = {
                Selected: "????????? ??????: {0}/{1}",
                "Uploading files": "{1} ??? {0}% ????????? ??????",
                "File size error": "{1} ????????? ????????? ???????????? ???????????????.",
                "Upload failed": "{0} ????????? ??????",
                "Per page": "{0}?????? ??????",
                Folder: "??????",
                "Login failed, please try again": "????????? ??????, ?????? ??????????????????.",
                "Already logged in": "?????? ????????????????????????.",
                "Please enter username and password": "????????? ????????? ??????????????? ??????????????????.",
                "Not Found": "?????? ??? ??????",
                "Not Allowed": "???????????? ??????",
                "Please log in": "?????????????????????.",
                "Unknown error": "??? ??? ?????? ??????",
                "Add files": "?????????",
                New: "??????",
                "New name": "????????? ??????",
                Username: "????????? ??????",
                Password: "????????????",
                Login: "?????????",
                Logout: "????????????",
                Profile: "?????????",
                "No pagination": "?????? ??????",
                Time: "????????? ??????",
                Name: "??????",
                Size: "??????",
                Home: "???",
                Copy: "??????",
                Move: "??????",
                Rename: "?????? ??????",
                Required: "??? ????????? ??????????????????.",
                Zip: "??????",
                "Batch Download": "?????? ????????????",
                Unzip: "?????? ??????",
                Delete: "??????",
                Download: "????????????",
                "Copy link": "?????? ??????",
                Done: "??????",
                File: "??????",
                "Drop files to upload": "???????????? ????????? ????????? ???????????????.",
                Close: "??????",
                "Select Folder": "?????? ??????",
                Users: "?????????",
                Files: "??????",
                Role: "??????",
                Cancel: "??????",
                Paused: "???????????????",
                Confirm: "??????",
                Create: "??????",
                User: "?????????",
                Admin: "?????????",
                Save: "??????",
                Read: "??????",
                Write: "??????",
                Upload: "?????????",
                Permissions: "??????",
                Homedir: "??? ??????",
                "Leave blank for no change": "???????????? ???????????? ?????? ????????????.",
                "Are you sure you want to do this?": "??? ????????? ?????????????????????????",
                "Are you sure you want to allow access to everyone?": "??????????????? ????????? ?????????????????????????",
                "Are you sure you want to stop all uploads?": "?????? ???????????? ?????????????????????????",
                "Something went wrong": "????????? ??????????????????.",
                "Invalid directory": "????????? ??????",
                "This field is required": "??? ????????? ???????????????.",
                "Username already taken": "?????? ?????? ?????? ????????? ???????????????.",
                "User not found": "???????????? ?????? ??? ????????????.",
                "Old password": "?????? ????????????",
                "New password": "??? ????????????",
                "Wrong password": "????????? ????????????",
                Updated: "???????????????",
                Deleted: "?????????",
                "Your file is ready": "????????? ?????????????????????.",
                View: "??????",
                Search: "??????",
                "Download permission": "????????????",
                Guest: "?????????",
                "Show hidden": "?????? ??????"
            },
            Da = Aa,
            _a = {
                Selected: "Vybran??: {0} z {1}",
                "Uploading files": "Nahr??v??m {0}% z {1}",
                "File size error": "{0} je p????li?? velk??, nahr??vejte soubory men???? jak {1}",
                "Upload failed": "{0} se nepoda??ilo nahr??t",
                "Per page": "{0} na str??nku",
                Folder: "Adres????",
                "Login failed, please try again": "P??ihl????en?? ne??sp????n??, zkuste to znova",
                "Already logged in": "U?? jste p??ihl????en??.",
                "Please enter username and password": "Zadejte p??ihla??ovac?? jm??no a heslo.",
                "Not Found": "Nenalezeno",
                "Not Allowed": "Nepovolen??",
                "Please log in": "P??ihlaste se",
                "Unknown error": "Nezn??m?? chyba",
                "Add files": "Nahr??t soubory",
                New: "Nov??",
                "New name": "Nov?? jm??no",
                Username: "P??ihla??ovac?? jm??no",
                Password: "Heslo",
                Login: "P??ihl??sit se",
                Logout: "Odhl??sit se",
                Profile: "Profil",
                "No pagination": "Bez str??nkov??n??",
                Time: "??as",
                Name: "Jm??no",
                Size: "Velikost",
                Home: "Hlavn?? adres????",
                Copy: "Kop??rovat",
                Move: "P??esunout",
                Rename: "P??ejmenovat",
                Required: "Vypl??te toto pole",
                Zip: "Archivovat do zip",
                "Batch Download": "Hromadn?? stahov??n??",
                Unzip: "Rozbalit zip arch??v",
                Delete: "Smazat",
                Download: "St??hnout",
                "Copy link": "Zkop??rovat odkaz",
                Done: "Hotovo",
                File: "Soubor",
                "Drop files to upload": "Pro nahr??n?? p??esu??te soubory sem",
                Close: "Zav????t",
                "Select Folder": "Vyberte adres????",
                Users: "U??ivatel??",
                Files: "Soubory",
                Role: "Typ ????tu",
                Cancel: "Zru??it",
                Paused: "Pozastaven??",
                Confirm: "Potvrdit",
                Create: "Vytvo??it",
                User: "U??ivatel",
                Admin: "Admin",
                Save: "Ulo??it",
                Read: "??ten??",
                Write: "Zapisov??n??",
                Upload: "Nahr??v??n??",
                Permissions: "Opr??vn??n??",
                Homedir: "Hlavn?? adres????",
                "Leave blank for no change": "Pokud nechcete zm??nit, nechejte pr??zdn??",
                "Are you sure you want to do this?": "Skute??n?? to chcete ud??lat?",
                "Are you sure you want to allow access to everyone?": "Skute??n?? chcete povolit p????stup bez hesla?",
                "Are you sure you want to stop all uploads?": "Skute??n?? chcete zastavit v??echna nahr??v??n???",
                "Something went wrong": "N??co se pokazilo",
                "Invalid directory": "Neplatn?? adres????",
                "This field is required": "Toto pole je povinn??",
                "Username already taken": "Toto p??ihla??ovac?? jm??no se u?? pou????v??",
                "User not found": "U??ivatel se nena??el",
                "Old password": "Star?? heslo",
                "New password": "Nov?? heslo",
                "Wrong password": "??patn?? heslo",
                Updated: "Aktualizovan??",
                Deleted: "Smazan??",
                "Your file is ready": "V???? soubor je p??ipraven??",
                View: "Zobrazit",
                Search: "Vyhled??v??n??",
                "Download permission": "Stahov??n??",
                Guest: "Host",
                "Show hidden": "Zobrazit skryt??"
            },
            Fa = _a,
            La = {
                Selected: "Seleccionados: {0} de {1}",
                "Uploading files": "Subindo arquivo {0}% de {1}",
                "File size error": "{0} O arquivo ?? demasiado grande. Por favor, cargue arquivos de menos de {1}",
                "Upload failed": "{0} Erro ao subir",
                "Per page": "{0} Por p??xina",
                Folder: "Cartafol",
                "Login failed, please try again": "Houbo un erro no acceso, proba de novo.",
                "Already logged in": "Xa iniciaches sesi??n.",
                "Please enter username and password": "Por favor, insire usuario e contrasinal.",
                "Not Found": "Non se atopou",
                "Not Allowed": "Non permitido",
                "Please log in": "Por favor, inicie sesi??n",
                "Unknown error": "Erro desco??ecido",
                "Add files": "Engadir Arquivos",
                New: "Novo",
                "New name": "Novo nome",
                Username: "Usuario",
                Password: "Contrasinal",
                Login: "Iniciar sesi??n",
                Logout: "Sa??r",
                Profile: "Perfil",
                "No pagination": "Sen paxinaci??n",
                Time: "Hora",
                Name: "Nome",
                Size: "Tama??o",
                Home: "Inicio",
                Copy: "Copiar",
                Move: "Mover",
                Rename: "Renomear",
                Required: "Por favor, encha este campo",
                Zip: "Arquivo comprimido",
                "Batch Download": "Descarga en lotes",
                Unzip: "Descomprimir",
                Delete: "Eliminar",
                Download: "Baixar",
                "Copy link": "Copiar ligaz??n",
                Done: "Feito",
                File: "Arquivo",
                "Drop files to upload": "Arrastra e solta os arquivos para carregar",
                Close: "Pechar",
                "Select Folder": "Escoller Cartafol",
                Users: "Usuarios",
                Files: "Arquivos",
                Role: "Privilexio",
                Cancel: "Cancelar",
                Paused: "Pausado",
                Confirm: "Confirmar",
                Create: "Crear",
                User: "Usuario",
                Admin: "Administrador",
                Save: "Gardar",
                Read: "Ler",
                Write: "Escribir",
                Upload: "Carregar",
                Permissions: "Permisos",
                Homedir: "Cartafol de Inicio",
                "Leave blank for no change": "Deixa en branco para non facer cambios",
                "Are you sure you want to do this?": "Est??s seguro de que queres facer isto?",
                "Are you sure you want to allow access to everyone?": "Est??s seguro de que queres darlle acceso a calquera?",
                "Are you sure you want to stop all uploads?": "Est??s seguro de que queres deter todas as cargas?",
                "Something went wrong": "Algo sa??u mal",
                "Invalid directory": "Direcci??n non v??lida",
                "This field is required": "Este campo ?? obrigatorio",
                "Username already taken": "O usuario xa existe",
                "User not found": "Non se atopou o usuario",
                "Old password": "Contrasinal antiga",
                "New password": "Nova contrasinal",
                "Wrong password": "Contrasinal errada",
                Updated: "Actualizado",
                Deleted: "Eliminado",
                "Your file is ready": "O teu arquivo est?? listo",
                View: "Ver",
                "Show hidden": "Amosar oculto"
            },
            Ea = La,
            $a = {
                Selected: "??????????????: {0} ???? {1}",
                "Uploading files": "???????????????? {0}% of {1}",
                "File size error": "{0} ?????????????? ??????????????, ????????????????????, ?????????????????? ???????? ???????????? {1}",
                "Upload failed": "{0} ???? ?????????????? ??????????????????",
                "Per page": "{0} ???? ????????????????",
                Folder: "??????????",
                "Login failed, please try again": "???????? ???? ????????????????. ???????????????????? ???????????????????? ?????? ??????",
                "Already logged in": "?????? ??????????????????????.",
                "Please enter username and password": "????????????????????, ?????????????? ?????? ???????????????????????? ?? ????????????.",
                "Not Found": "???? ??????????????",
                "Not Allowed": "???? ??????????????????",
                "Please log in": "????????????????????, ??????????????",
                "Unknown error": "?????????????????????? ????????????",
                "Add files": "???????????????? ??????????",
                New: "??????????????",
                "New name": "?????????? ??????",
                Username: "?????? ????????????????????????",
                Password: "????????????",
                Login: "????????",
                Logout: "??????????",
                Profile: "??????????????",
                "No pagination": "?????? ???????????????? ???? ????????????????",
                Time: "??????????",
                Name: "??????",
                Size: "????????????",
                Home: "??????????????",
                Copy: "????????????????????",
                Move: "??????????????????????",
                Rename: "??????????????????????????",
                Required: "????????????????????, ?????????????????? ?????? ????????",
                Zip: "???????????????????????? zip",
                "Batch Download": "???????????????? ????????????????",
                Unzip: "?????????????????????????????? zip ??????????",
                Delete: "??????????????",
                Download: "??????????????",
                "Copy link": "?????????????????????? ????????????",
                Done: "????????????",
                File: "????????",
                "Drop files to upload": "???????????????????? ?????????? ?????? ????????????????",
                Close: "??????????????",
                "Select Folder": "???????????????? ??????????",
                Users: "????????????????????????",
                Files: "??????????",
                Role: "????????",
                Cancel: "????????????",
                Paused: "????????????????????????????",
                Confirm: "??????????????????????",
                Create: "??????????????",
                User: "????????????????????????",
                Admin: "??????????",
                Save: "??????????????????",
                Read: "????????????",
                Write: "????????????",
                Upload: "????????????????",
                Permissions: "????????????????????",
                Homedir: "???????????????? ??????????",
                "Leave blank for no change": "???????????????? ???????? ????????????, ?????????? ???????????????? ?????? ??????????????????",
                "Are you sure you want to do this?": "???? ??????????????, ?????? ???????????? ?????????????????? ?????? ?????????????????",
                "Are you sure you want to allow access to everyone?": "???? ??????????????, ?????? ???????????? ???????????????????????? ???????????? ?????????",
                "Are you sure you want to stop all uploads?": "???? ??????????????, ?????? ???????????? ???????????????????? ?????? ?????????????????",
                "Something went wrong": "??????-???? ?????????? ???? ??????",
                "Invalid directory": "???????????????????????????????? ??????????",
                "This field is required": "?????? ???????? ????????????????????????",
                "Username already taken": "?????? ???????????????????????? ?????? ????????????",
                "User not found": "???????????????????????? ???? ????????????",
                "Old password": "???????????? ????????????",
                "New password": "?????????? ????????????",
                "Wrong password": "???????????????? ????????????",
                Updated: "??????????????????",
                Deleted: "??????????????",
                "Your file is ready": "?????? ???????? ??????????",
                View: "????????????????",
                Search: "??????????",
                "Download permission": "????????????????????",
                Guest: "??????????",
                "Show hidden": "???????????????? ??????????????"
            },
            xa = $a,
            Ta = {
                Selected: "Kijel??l??s: {0} Kijel??lve {1}",
                "Uploading files": "Felt??lt??s {0}% Felt??ltve {1}",
                "File size error": "{0} T??l nagy f??jl {1}",
                "Upload failed": "{0} Sikertelen felt??lt??s",
                "Per page": "{0} Oldalank??nt",
                Folder: "Mappa",
                "Login failed, please try again": "Sikertelen bel??p??s, pr??b??lja ??jra",
                "Already logged in": "Bejelentkezve.",
                "Please enter username and password": "K??rj??k, adja meg a felhaszn??l??nev??t ??s jelszav??t.",
                "Not Found": "Nem tal??lhat??",
                "Not Allowed": "Nem megengedett",
                "Please log in": "K??rj??k jelentkezzen be",
                "Unknown error": "Ismeretlen hiba",
                "Add files": "F??jl hozz??ad??sa",
                New: "??j",
                "New name": "??j felhaszn??l??",
                Username: "Felhaszn??l??n??v",
                Password: "Jelsz??",
                Login: "Bel??p??s",
                Logout: "Kil??p??s",
                Profile: "Profil",
                "No pagination": "Nincs lap",
                Time: "Id??",
                Name: "N??v",
                Size: "M??ret",
                Home: "F??k??nyvt??r",
                Copy: "M??sol",
                Move: "??thelyez",
                Rename: "??tnevez",
                Required: "K??rem t??ltse ki ezt a mez??t",
                Zip: "Becsomagol",
                "Batch Download": "K??tegelt let??lt??s",
                Unzip: "Kicsomagol??s",
                Delete: "T??rl??s",
                Download: "Let??lt??s",
                "Copy link": "Link m??sol??sa",
                Done: "K??sz",
                File: "F??jl",
                "Drop files to upload": "Dobja el a felt??ltend?? f??jlokat",
                Close: "Bez??r",
                "Select Folder": "Mappa kijel??l??se",
                Users: "Felhaszn??l??k",
                Files: "F??jlok",
                Role: "Szerep",
                Cancel: "M??gse",
                Paused: "Sz??netel",
                Confirm: "Meger??s??t",
                Create: "L??trehoz",
                User: "Felhaszn??l??",
                Admin: "Adminisztr??tor",
                Save: "Ment??s",
                Read: "Olvas??s",
                Write: "??r??s",
                Upload: "Felt??lt??s",
                Permissions: "Enged??lyek",
                Homedir: "F?? mappa",
                "Leave blank for no change": "Hagyja ??resen v??ltoztat??s n??lk??l",
                "Are you sure you want to do this?": "Biztosan meg akarja v??ltoztatni?",
                "Are you sure you want to allow access to everyone?": "Biztos, hogy mindenkinek enged??lyezi a hozz??f??r??st?",
                "Are you sure you want to stop all uploads?": "Biztosan le??ll??tja az ??sszes felt??lt??st?",
                "Something went wrong": "Valami elromlott",
                "Invalid directory": "??rv??nytelen mappa",
                "This field is required": "Mez?? kit??lt??se k??telez??",
                "Username already taken": "A felhaszn??l??n??v m??r foglalt",
                "User not found": "Felhaszn??l?? nem tal??lhat??",
                "Old password": "R??gi jelsz??",
                "New password": "??j jelsz??",
                "Wrong password": "Rossz jelsz??",
                Updated: "Felt??lt??s",
                Deleted: "T??rl??s",
                "Your file is ready": "Your file is ready",
                View: "N??zet",
                Search: "Keres??s",
                "Download permission": "Let??lt??s enged??lyez??s",
                Guest: "Vend??g",
                "Show hidden": "Rejtett megjelen??t??se"
            },
            Ra = Ta,
            Ba = {
                Selected: "Vald: {0} of {1}",
                "Uploading files": "Laddar upp {0}% of {1}",
                "File size error": "{0} ??r f??r stor, max filstorlek ??r {1}",
                "Upload failed": "{0} uppladdning misslyckades",
                "Per page": "{0} Per sida",
                Folder: "Mapp",
                "Login failed, please try again": "Inloggning misslyckades, f??rs??k igen.",
                "Already logged in": "Redan inloggad.",
                "Please enter username and password": "Ange anv??ndarnamn och l??senord.",
                "Not Found": "Ej funnen",
                "Not Allowed": "Ej till??ten",
                "Please log in": "Var vanlig logga in.",
                "Unknown error": "Ok??nt fel",
                "Add files": "L??gg till filer",
                New: "Ny",
                "New name": "Nytt namn",
                Username: "Anv??ndarnamn",
                Password: "L??senord",
                Login: "Logga in",
                Logout: "Logga ut",
                Profile: "Profil",
                "No pagination": "Sidhantering",
                Time: "Tid",
                Name: "Namn",
                Size: "Storlek",
                Home: "Hem",
                Copy: "Kopiera",
                Move: "Flytta",
                Rename: "Byt namn",
                Required: "V??nligen fyll i detta f??lt",
                Zip: "Zip",
                "Batch Download": "Batch nedladdning",
                Unzip: "Unzip",
                Delete: "Borttag",
                Download: "Ladda ned",
                "Copy link": "Kopiera l??nk",
                Done: "Klar",
                File: "Fil",
                "Drop files to upload": "Sl??pp filer f??r uppladdning",
                Close: "St??ng",
                "Select Folder": "V??lj mapp",
                Users: "Anv??ndare",
                Files: "Filer",
                Role: "Roll",
                Cancel: "Avbryt",
                Paused: "Pausad",
                Confirm: "Godk??nn",
                Create: "Skapa",
                User: "Anv??ndare",
                Admin: "Admin",
                Save: "Spara",
                Read: "L??s",
                Write: "Skriv",
                Upload: "Ladda upp",
                Permissions: "Beh??righeter",
                Homedir: "Hem mapp",
                "Leave blank for no change": "L??mna blankt f??r ingen ??ndring",
                "Are you sure you want to do this?": "??r du s??ker p?? att du vill g??ra detta?",
                "Are you sure you want to allow access to everyone?": "Vill du verkligen ge access till alla?",
                "Are you sure you want to stop all uploads?": "Vill du stoppa alla uppladdningar?",
                "Something went wrong": "N??got gick fel",
                "Invalid directory": "Ogiltig mapp",
                "This field is required": "Detta f??lt kr??vs",
                "Username already taken": "Anv??ndarnamnet finns redan",
                "User not found": "Anv??ndaren hittas inte",
                "Old password": "Gammalt l??senord",
                "New password": "Nytt l??senord",
                "Wrong password": "fel l??senord",
                Updated: "Uppdaterad",
                Deleted: "Borttagen",
                "Your file is ready": "Din fil ??r klar",
                View: "Visa",
                Search: "S??k",
                "Download permission": "Ladda ned",
                Guest: "G??st",
                "Show hidden": "Visa dold"
            },
            qa = Ba,
            Oa = {
                methods: {
                    lang: function(e) {
                        for (var a = {
                                english: ea,
                                spanish: oa,
                                german: ia,
                                indonesian: ra,
                                turkish: la,
                                lithuanian: ca,
                                portuguese: pa,
                                dutch: fa,
                                chinese: ga,
                                bulgarian: va,
                                serbian: ya,
                                french: za,
                                slovak: Ua,
                                polish: ja,
                                italian: Na,
                                korean: Da,
                                czech: Fa,
                                galician: Ea,
                                russian: xa,
                                hungarian: Ra,
                                swedish: qa
                            }, o = Ha.state.config.language, n = arguments.length, i = new Array(n > 1 ? n - 1 : 0), t = 1; t < n; t++) i[t - 1] = arguments[t];
                        var r = i;
                        return a[o] && void 0 != a[o][e] ? a[o][e].replace(/{(\d+)}/g, (function(e, a) {
                            return "undefined" != typeof r[a] ? r[a] : e
                        })) : e
                    },
                    is: function(e) {
                        return this.$store.state.user.role == e
                    },
                    can: function(e) {
                        return this.$store.getters.hasPermissions(e)
                    },
                    formatBytes: function(e) {
                        var a = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : 2;
                        if (0 === e) return "0 Bytes";
                        var o = 1024,
                            n = a < 0 ? 0 : a,
                            i = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
                            t = Math.floor(Math.log(e) / Math.log(o));
                        return parseFloat((e / Math.pow(o, t)).toFixed(n)) + " " + i[t]
                    },
                    formatDate: function(e) {
                        return Qe.a.unix(e).format(Ha.state.config.date_format ? Ha.state.config.date_format : "YY/MM/DD hh:mm:ss")
                    },
                    checkUser: function() {
                        var e = this;
                        p.getUser().then((function(a) {
                            a.username !== Ha.state.user.username && (e.$store.commit("destroyUser", a), e.$toast.open({
                                message: e.lang("Please log in"),
                                type: "is-danger"
                            }))
                        }))["catch"]((function() {
                            e.$toast.open({
                                message: e.lang("Please log in"),
                                type: "is-danger"
                            })
                        }))
                    },
                    handleError: function(e) {
                        this.checkUser(), "string" != typeof e ? e && e.response && e.response.data && e.response.data.data ? this.$toast.open({
                            message: this.lang(e.response.data.data),
                            type: "is-danger",
                            duration: 5e3
                        }) : this.$toast.open({
                            message: this.lang("Unknown error"),
                            type: "is-danger",
                            duration: 5e3
                        }) : this.$toast.open({
                            message: this.lang(e),
                            type: "is-danger",
                            duration: 5e3
                        })
                    },
                    getDownloadLink: function(e) {
                        return n["default"].config.baseURL + "/download&path=" + encodeURIComponent(c["Base64"].encode(e))
                    },
                    hasPreview: function(e) {
                        return this.isText(e) || this.isImage(e)
                    },
                    isText: function(e) {
                        return this.hasExtension(e, Ha.state.config.editable)
                    },
                    isImage: function(e) {
                        return this.hasExtension(e, [".jpg", ".jpeg", ".gif", ".png", ".bmp", ".svg", ".tiff", ".tif"])
                    },
                    hasExtension: function(e, a) {
                        return !_.a.isEmpty(a) && new RegExp("(" + a.join("|").replace(/\./g, "\\.") + ")$", "i").test(e)
                    },
                    capitalize: function(e) {
                        return e.charAt(0).toUpperCase() + e.slice(1)
                    }
                }
            },
            Va = Oa;
        n["default"].use(Ye["a"]);
        var Ha = new Ye["a"].Store({
            state: {
                initialized: !1,
                config: [],
                user: {
                    role: "guest",
                    permissions: [],
                    name: "",
                    username: ""
                },
                cwd: {
                    location: "/",
                    content: []
                },
                tree: {}
            },
            getters: {
                hasPermissions: function(e) {
                    return function(a) {
                        return _.a.isArray(a) ? _.a.intersection(e.user.permissions, a).length == a.length : !!_.a.find(e.user.permissions, (function(e) {
                            return e == a
                        }))
                    }
                }
            },
            mutations: {
                initialize: function(e) {
                    e.initialized = !0, this.commit("resetCwd"), this.commit("resetTree"), this.commit("destroyUser")
                },
                resetCwd: function(e) {
                    e.cwd = {
                        location: "/",
                        content: []
                    }
                },
                resetTree: function(e) {
                    e.tree = {
                        path: "/",
                        name: Va.methods.lang("Home"),
                        children: []
                    }
                },
                setConfig: function(e, a) {
                    e.config = a
                },
                setUser: function(e, a) {
                    e.user = a
                },
                destroyUser: function(e) {
                    e.user = {
                        role: "guest",
                        permissions: [],
                        name: "",
                        username: ""
                    }
                },
                setCwd: function(e, a) {
                    e.cwd.location = a.location, e.cwd.content = [], _.a.forEach(_.a.sortBy(a.content, [function(e) {
                        return _.a.toLower(e.type)
                    }]), (function(a) {
                        e.cwd.content.push(a)
                    }))
                },
                updateTreeNode: function(e, a) {
                    var o = function e(o) {
                        for (var n in o)
                            if (o.hasOwnProperty(n)) {
                                if ("path" === n && o[n] === a.path) return void Object.assign(o, {
                                    path: a.path,
                                    children: a.children
                                });
                                "object" === Object(A["a"])(o[n]) && e(o[n])
                            }
                    };
                    o(e.tree)
                }
            },
            actions: {}
        });
        n["default"].use(z["a"]);
        var Ma = new z["a"]({
                mode: "hash",
                routes: [{
                    path: "/",
                    name: "browser",
                    component: Te
                }, {
                    path: "/login",
                    name: "login",
                    component: w
                }, {
                    path: "/users",
                    name: "users",
                    component: Ke,
                    beforeEnter: function(e, a, o) {
                        "admin" == Ha.state.user.role && o()
                    }
                }]
            }),
            Ia = o("8a03"),
            Za = o.n(Ia),
            Ga = o("caf9");
        o("15f5"), o("b2a2");
        n["default"].config.productionTip = !1, n["default"].config.baseURL = window.location.origin + window.location.pathname + "?r=", d.a.defaults.withCredentials = !0, d.a.defaults.baseURL = n["default"].config.baseURL, d.a.defaults.headers["Content-Type"] = "application/json", n["default"].use(Za.a, {
            defaultIconPack: "fas"
        }), n["default"].use(Ga["a"], {
            preLoad: 1.3
        }), n["default"].mixin(Va), new n["default"]({
            router: Ma,
            store: Ha,
            created: function() {
                var e = this;
                p.getConfig().then((function(a) {
                    e.$store.commit("setConfig", a.data.data), p.getUser().then((function(a) {
                        e.$store.commit("initialize"), e.$store.commit("setUser", a), e.$router.push("/")["catch"]((function() {}))
                    }))["catch"]((function() {
                        e.$notification.open({
                            message: e.lang("Something went wrong"),
                            type: "is-danger",
                            queue: !1,
                            indefinite: !0
                        })
                    }))
                }))["catch"]((function() {
                    e.$notification.open({
                        message: e.lang("Something went wrong"),
                        type: "is-danger",
                        queue: !1,
                        indefinite: !0
                    })
                }))
            },
            render: function(e) {
                return e(k)
            }
        }).$mount("#app")
    },
    "9fdd": function(e, a, o) {},
    a002: function(e, a, o) {},
    abb7: function(e, a, o) {
        "use strict";
        var n = o("a002"),
            i = o.n(n);
        i.a
    },
    b069: function(e, a, o) {
        "use strict";
        var n = o("e214"),
            i = o.n(n);
        i.a
    },
    b6df: function(e, a, o) {},
    d7ef: function(e, a, o) {
        "use strict";
        var n = o("0b8c"),
            i = o.n(n);
        i.a
    },
    db9b: function(e, a, o) {},
    e214: function(e, a, o) {},
    f507: function(e, a, o) {
        "use strict";
        var n = o("4290"),
            i = o.n(n);
        i.a
    }
});
//# sourceMappingURL=app.js.map