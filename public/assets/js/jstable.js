/*!
 * JSTable v1.6.1
 */
const JSTableDefaultConfig = {
    perPage: 5,
    perPageSelect: [5, 10, 15, 20, 25],
    sortable: !0,
    searchable: !0,
    nextPrev: !0,
    firstLast: !1,
    prevText: "&lsaquo;",
    nextText: "&rsaquo;",
    firstText: "&laquo;",
    lastText: "&raquo;",
    ellipsisText: "&hellip;",
    truncatePager: !0,
    pagerDelta: 2,
    classes: {
        top: "dt-top",
        info: "dt-info",
        input: "dt-input",
        table: "dt-table",
        bottom: "dt-bottom",
        search: "dt-search",
        sorter: "dt-sorter",
        wrapper: "dt-wrapper",
        dropdown: "dt-dropdown",
        ellipsis: "dt-ellipsis",
        selector: "form-select",
        container: "dt-container",
        pagination: "dt-pagination",
        loading: "dt-loading",
        message: "dt-message",
    },
    labels: {
        placeholder: "Search...",
        perPage: "{select} entries per page",
        noRows: "No entries found",
        info: "Showing {start} to {end} of {rows} entries",
        loading: "Loading...",
        infoFiltered: "Showing {start} to {end} of {rows} entries (filtered from {rowsTotal} entries)",
    },
    layout: { top: "{select}{search}", bottom: "{info}{pager}" },
    serverSide: !1,
    deferLoading: null,
    ajax: null,
    ajaxParams: {},
    queryParams: { page: "page", search: "search" },
    addQueryParams: !0,
    rowAttributesCreator: null,
    searchDelay: null,
};
class JSTable {
    constructor(e, t = {}) {
        let s = e;
        "string" == typeof e && (s = document.querySelector(e)),
        null !== s &&
        ((this.config = this._merge(JSTableDefaultConfig, t)),
            (this.table = new JSTableElement(s)),
            (this.currentPage = 1),
            (this.columnRenderers = []),
            (this.columnsNotSearchable = []),
            (this.searchQuery = null),
            (this.sortColumn = null),
            (this.sortDirection = "asc"),
            (this.isSearching = !1),
            (this.dataCount = null),
            (this.filteredDataCount = null),
            (this.searchTimeout = null),
            (this.pager = new JSTablePager(this)),
            this._build(),
            this._buildColumns(),
            this.update(null === this.config.deferLoading),
            this._bindEvents(),
            this._emit("init"),
            this._parseQueryParams());
    }
    _build() {
        let e = this.config;
        (this.wrapper = document.createElement("div")), (this.wrapper.className = e.classes.wrapper);
        var t = [
            "<div class='",
            e.classes.top,
            "'>",
            e.layout.top,
            "</div>",
            "<div class='",
            e.classes.container,
            "'>",
            "<div class='",
            e.classes.loading,
            " hidden'>",
            e.labels.loading,
            "</div>",
            "</div>",
            "<div class='",
            e.classes.bottom,
            "'>",
            e.layout.bottom,
            "</div>",
        ].join("");
        if (((t = t.replace("{info}", "<div class='" + e.classes.info + "'></div>")), e.perPageSelect)) {
            var s = ["<div class='", e.classes.dropdown, "'>", "<label>", e.labels.perPage, "</label>", "</div>"].join(""),
                a = document.createElement("select");
            (a.className = e.classes.selector),
                e.perPageSelect.forEach(function (t) {
                    var s = t === e.perPage,
                        r = new Option(t, t, s, s);
                    a.add(r);
                }),
                (s = s.replace("{select}", a.outerHTML)),
                (t = t.replace(/\{select\}/g, s));
        } else t = t.replace(/\{select\}/g, "");
        if (e.searchable) {
            var r = ["<div class='", e.classes.search, "'>", "<input class='", e.classes.input, "' placeholder='", e.labels.placeholder, "' type='text'>", "</div>"].join("");
            t = t.replace(/\{search\}/g, r);
        } else t = t.replace(/\{search\}/g, "");
        this.table.element.classList.add(e.classes.table),
            (t = t.replace("{pager}", "<div class='" + e.classes.pagination + "'></div>")),
            (this.wrapper.innerHTML = t),
            this.table.element.parentNode.replaceChild(this.wrapper, this.table.element),
            this.wrapper.querySelector("." + e.classes.container).appendChild(this.table.element),
            this._updatePagination(),
            this._updateInfo();
    }
    async update(e = !0) {
        var t = this;
        this.currentPage > this.pager.getPages() && (this.currentPage = this.pager.getPages());
        let s = t.wrapper.querySelector(" ." + t.config.classes.loading);
        if (
            (s.classList.remove("hidden"),
                this.table.header.getCells().forEach(function (e, s) {
                    let a = t.table.head.rows[0].cells[s];
                    (a.innerHTML = e.getInnerHTML()), e.classes.length > 0 && (a.className = e.classes.join(" "));
                    for (let t in e.attributes) a.setAttribute(t, e.attributes[t]);
                    a.setAttribute("data-sortable", e.isSortable);
                }),
                e)
        )
            return this.getPageData(this.currentPage)
                .then(function (e) {
                    t.table.element.classList.remove("hidden"),
                        (t.table.body.innerHTML = ""),
                        e.forEach(function (e) {
                            t.table.body.appendChild(e.getFormatted(t.columnRenderers, t.config.rowAttributesCreator));
                        }),
                        s.classList.add("hidden");
                })
                .then(function () {
                    t.getDataCount() <= 0 && (t.wrapper.classList.remove("search-results"), t.setMessage(t.config.labels.noRows)), t._emit("update");
                })
                .then(function () {
                    t._updatePagination(), t._updateInfo();
                });
        t.table.element.classList.remove("hidden"),
            (t.table.body.innerHTML = ""),
        this.getDataCount() <= 0 && (t.wrapper.classList.remove("search-results"), t.setMessage(t.config.labels.noRows)),
            this._getData().forEach(function (e) {
                t.table.body.appendChild(e.getFormatted(t.columnRenderers, t.config.rowAttributesCreator));
            }),
            s.classList.add("hidden");
    }
    _updatePagination() {
        let e = this.wrapper.querySelector(" ." + this.config.classes.pagination);
        (e.innerHTML = ""), e.appendChild(this.pager.render(this.currentPage));
    }
    _updateInfo() {
        let e = this.wrapper.querySelector(" ." + this.config.classes.info),
            t = this.isSearching ? this.config.labels.infoFiltered : this.config.labels.info;
        if (e && t.length) {
            var s = t
                .replace("{start}", this.getDataCount() > 0 ? this._getPageStartIndex() + 1 : 0)
                .replace("{end}", this._getPageEndIndex() + 1)
                .replace("{page}", this.currentPage)
                .replace("{pages}", this.pager.getPages())
                .replace("{rows}", this.getDataCount())
                .replace("{rowsTotal}", this.getDataCountTotal());
            e.innerHTML = s;
        }
    }
    _getPageStartIndex() {
        return (this.currentPage - 1) * this.config.perPage;
    }
    _getPageEndIndex() {
        let e = this.currentPage * this.config.perPage - 1;
        return e > this.getDataCount() - 1 ? this.getDataCount() - 1 : e;
    }
    _getData() {
        return (
            this._emit("getData", this.table.dataRows),
                this.table.dataRows.filter(function (e) {
                    return e.visible;
                })
        );
    }
    _fetchData() {
        var e = this;
        let t = { searchQuery: this.searchQuery, sortColumn: this.sortColumn, sortDirection: this.sortDirection, start: this._getPageStartIndex(), length: this.config.perPage, datatable: 1 };
        t = Object.assign({}, this.config.ajaxParams, t);
        let s = this.config.ajax + "?" + this._queryParams(t);
        return fetch(s, { method: "GET", credentials: "same-origin", headers: { Accept: "application/json", "Content-Type": "application/json" } })
            .then(function (e) {
                return e.json();
            })
            .then(function (t) {
                return e._emit("fetchData", t), (e.dataCount = t.recordsTotal), (e.filteredDataCount = t.recordsFiltered), t.data;
            })
            .then(function (e) {
                let t = [];
                return (
                    e.forEach(function (e) {
                        t.push(JSTableRow.createFromData(e));
                    }),
                        t
                );
            })
            .catch(function (e) {
                console.error(e);
            });
    }
    _queryParams(e) {
        return Object.keys(e)
            .map((t) => encodeURIComponent(t) + "=" + encodeURIComponent(e[t]))
            .join("&");
    }
    getDataCount() {
        return this.isSearching ? this.getDataCountFiltered() : this.getDataCountTotal();
    }
    getDataCountFiltered() {
        return this.config.serverSide ? this.filteredDataCount : this._getData().length;
    }
    getDataCountTotal() {
        return this.config.serverSide ? (null !== this.config.deferLoading ? this.config.deferLoading : this.dataCount) : this.table.dataRows.length;
    }
    getPageData() {
        if (this.config.serverSide) return this._fetchData();
        let e = this._getPageStartIndex();
        var t = this._getPageEndIndex();
        return Promise.resolve(this._getData()).then(function (s) {
            return s.filter(function (s, a) {
                return a >= e && a <= t;
            });
        });
    }
    async search(e) {
        var t = this;
        if (this.searchQuery === e.toLowerCase()) return !1;
        if (((this.searchQuery = e.toLowerCase()), this.config.searchDelay)) {
            if (this.searchTimeout) return !1;
            this.searchTimeout = setTimeout(function () {
                (t.searchTimeout = null), t._parseQueryParams();
            }, this.config.searchDelay);
        }
        return (
            (this.currentPage = 1),
                (this.isSearching = !0),
                this.searchQuery.length
                    ? (this.config.serverSide ||
                    this.table.dataRows.forEach(function (e) {
                        (e.visible = !1),
                        t.searchQuery.split(" ").reduce(function (s, a) {
                            var r;
                            let i = e.getCells();
                            return (
                                (i = i.filter(function (e, s) {
                                    if (t.columnsNotSearchable.indexOf(s) < 0) return !0;
                                })),
                                    (r = i.some(function (e, t) {
                                        if (e.getTextContent().toLowerCase().indexOf(a) >= 0) return !0;
                                    })),
                                s && r
                            );
                        }, !0) && (e.visible = !0);
                    }),
                        this.wrapper.classList.add("search-results"),
                        this.update().then(function () {
                            t._emit("search", e);
                        }))
                    : (this.table.dataRows.forEach(function (e) {
                        e.visible = !0;
                    }),
                        (this.isSearching = !1),
                        t.wrapper.classList.remove("search-results"),
                        t.update(),
                        !1)
        );
    }
    sort(e, t, s = !1) {
        var a = this;
        if (((this.sortColumn = e || 0), (this.sortDirection = t), this.sortColumn < 0 || this.sortColumn > this.table.getColumnCount() - 1)) return !1;
        var r = this.table.header.getCell(this.sortColumn),
            i = this.table.dataRows;
        this.table.header.getCells().forEach(function (e) {
            e.removeClass("asc"), e.removeClass("desc");
        }),
            r.addClass(this.sortDirection),
        this.config.serverSide ||
        ((i = i.sort(function (e, t) {
            var s = e.getCellTextContent(a.sortColumn).toLowerCase(),
                r = t.getCellTextContent(a.sortColumn).toLowerCase();
            return (
                (s = s.replace(/(\$|\,|\s|%)/g, "")),
                    (r = r.replace(/(\$|\,|\s|%)/g, "")),
                    (s = isNaN(s) || "" === s ? s : parseFloat(s)),
                    (r = isNaN(r) || "" === r ? r : parseFloat(r)),
                    ("" === s && "" !== r) || (isNaN(s) && !isNaN(r))
                        ? "asc" === a.sortDirection
                        ? 1
                        : -1
                        : ("" !== s && "" === r) || (!isNaN(s) && isNaN(r))
                        ? "asc" === a.sortDirection
                            ? -1
                            : 1
                        : "asc" === a.sortDirection
                            ? s === r
                                ? 0
                                : s > r
                                    ? 1
                                    : -1
                            : s === r
                                ? 0
                                : s < r
                                    ? 1
                                    : -1
            );
        })),
            (this.table.dataRows = i)),
        (this.config.serverSide && s) || this.update(),
            this._emit("sort", this.sortColumn, this.sortDirection);
    }
    async paginate(e) {
        var t = this;
        return (
            (this.currentPage = e),
                this.update().then(function () {
                    t._emit("paginate", t.currentPage, e);
                })
        );
    }
    _bindEvents() {
        var e = this;
        this.wrapper.addEventListener("click", function (t) {
            var s = t.target;
            if (s.hasAttribute("data-page")) {
                t.preventDefault();
                let a = parseInt(s.getAttribute("data-page"), 10);
                if ((e.paginate(a), e.config.addQueryParams)) {
                    const t = new URL(window.location.href);
                    t.searchParams.set(e.config.queryParams.page, a), window.history.replaceState(null, null, t);
                }
            }
            if ("TH" === s.nodeName && s.hasAttribute("data-sortable")) {
                if ("false" === s.getAttribute("data-sortable")) return !1;
                t.preventDefault(), e.sort(s.cellIndex, s.classList.contains("asc") ? "desc" : "asc");
            }
        }),
        this.config.perPageSelect &&
        this.wrapper.addEventListener("change", function (t) {
            var s = t.target;
            if ("SELECT" === s.nodeName && s.classList.contains(e.config.classes.selector)) {
                t.preventDefault();
                let a = parseInt(s.value, 10);
                e._emit("perPageChange", e.config.perPage, a), (e.config.perPage = a), e.update();
            }
        }),
        this.config.searchable &&
        this.wrapper.addEventListener("change", function (t) {
            if ("INPUT" === t.target.nodeName && t.target.classList.contains(e.config.classes.input) && (t.preventDefault(), e.search(t.target.value), e.config.addQueryParams)) {
                const s = new URL(window.location.href);
                s.searchParams.set(e.config.queryParams.search, t.target.value), window.history.replaceState(null, null, s);
            }
        });
    }
    on(e, t) {
        (this.events = this.events || {}), (this.events[e] = this.events[e] || []), this.events[e].push(t);
    }
    off(e, t) {
        (this.events = this.events || {}), e in this.events != !1 && this.events[e].splice(this.events[e].indexOf(t), 1);
    }
    _emit(e) {
        if (((this.events = this.events || {}), e in this.events != !1)) for (var t = 0; t < this.events[e].length; t++) this.events[e][t].apply(this, Array.prototype.slice.call(arguments, 1));
    }
    setMessage(e) {
        var t = this.table.getColumnCount(),
            s = document.createElement("tr");
        (s.innerHTML = '<td class="' + this.config.classes.message + '" colspan="' + t + '">' + e + "</td>"), (this.table.body.innerHTML = ""), this.table.body.appendChild(s);
    }
    _buildColumns() {
        var e = this;
        let t = null,
            s = null;
        this.config.columns &&
        this.config.columns.forEach(function (a) {
            isNaN(a.select) || (a.select = [a.select]),
                a.select.forEach(function (r) {
                    var i = e.table.header.getCell(r);
                    if (void 0 !== i) {
                        if ((a.hasOwnProperty("render") && "function" == typeof a.render && (e.columnRenderers[r] = a.render), a.hasOwnProperty("sortable"))) {
                            let r = !1;
                            i.hasSortable ? (r = i.isSortable) : ((r = a.sortable), i.setSortable(r)), r && (i.addClass(e.config.classes.sorter), a.hasOwnProperty("sort") && 1 === a.select.length && ((t = a.select[0]), (s = a.sort)));
                        }
                        a.hasOwnProperty("searchable") && (i.addAttribute("data-searchable", a.searchable), !1 === a.searchable && e.columnsNotSearchable.push(r));
                    }
                });
        }),
            this.table.header.getCells().forEach(function (a, r) {
                null === a.isSortable && a.setSortable(e.config.sortable), a.isSortable && (a.addClass(e.config.classes.sorter), a.hasSort && ((t = r), (s = a.sortDirection)));
            }),
        null !== t && e.sort(t, s, !0);
    }
    _merge(e, t) {
        var s = this;
        return (
            Object.keys(e).forEach(function (a) {
                !t.hasOwnProperty(a) || "object" != typeof t[a] || t[a] instanceof Array || null === t[a] ? t.hasOwnProperty(a) || (t[a] = e[a]) : s._merge(e[a], t[a]);
            }),
                t
        );
    }
    async _parseQueryParams() {
        const e = new URLSearchParams(window.location.search);
        let t = e.get(this.config.queryParams.search);
        if (t) {
            this.wrapper.querySelectorAll("." + this.config.classes.input).forEach(function (e) {
                e.value = t;
            }),
                await this.search(t);
        }
        let s = e.get(this.config.queryParams.page);
        s && (await this.paginate(parseInt(s)));
    }
}
class JSTableElement {
    constructor(e) {
        (this.element = e),
            (this.body = this.element.tBodies[0]),
            (this.head = this.element.tHead),
            (this.rows = Array.from(this.element.rows).map(function (e, t) {
                return new JSTableRow(e, e.parentNode.nodeName, t);
            })),
            (this.dataRows = this._getBodyRows()),
            (this.header = this._getHeaderRow());
    }
    _getBodyRows() {
        return this.rows.filter(function (e) {
            return !e.isHeader && !e.isFooter;
        });
    }
    _getHeaderRow() {
        return this.rows.find(function (e) {
            return e.isHeader;
        });
    }
    getColumnCount() {
        return this.header.getColumnCount();
    }
    getFooterRow() {
        return this.rows.find(function (e) {
            return e.isFooter;
        });
    }
}
class JSTableRow {
    constructor(e, t = "", s = null) {
        (this.cells = Array.from(e.cells).map(function (e) {
            return new JSTableCell(e);
        })),
            (this.d = this.cells.length),
            (this.isHeader = "THEAD" === t),
            (this.isFooter = "TFOOT" === t),
            (this.visible = !0),
            (this.rowID = s);
        var a = this;
        (this.attributes = {}),
            [...e.attributes].forEach(function (e) {
                a.attributes[e.name] = e.value;
            });
    }
    getCells() {
        return Array.from(this.cells);
    }
    getColumnCount() {
        return this.cells.length;
    }
    getCell(e) {
        return this.cells[e];
    }
    getCellTextContent(e) {
        return this.getCell(e).getTextContent();
    }
    static createFromData(e) {
        let t = document.createElement("tr");
        if (e.hasOwnProperty("data")) {
            if (e.hasOwnProperty("attributes")) for (const s in e.attributes) t.setAttribute(s, e.attributes[s]);
            e = e.data;
        }
        return (
            e.forEach(function (e) {
                let s = document.createElement("td");
                if (((s.innerHTML = e && e.hasOwnProperty("data") ? e.data : e), e && e.hasOwnProperty("attributes"))) for (const t in e.attributes) s.setAttribute(t, e.attributes[t]);
                t.appendChild(s);
            }),
                new JSTableRow(t)
        );
    }
    getFormatted(e, t = null) {
        let s = document.createElement("tr");
        var a = this;
        for (let e in this.attributes) s.setAttribute(e, this.attributes[e]);
        let r = t ? t.call(this, this.getCells()) : {};
        for (const e in r) s.setAttribute(e, r[e]);
        return (
            this.getCells().forEach(function (t, r) {
                var i = document.createElement("td");
                (i.innerHTML = t.getInnerHTML()), e.hasOwnProperty(r) && (i.innerHTML = e[r].call(a, t.getElement(), r)), t.classes.length > 0 && (i.className = t.classes.join(" "));
                for (let e in t.attributes) i.setAttribute(e, t.attributes[e]);
                s.appendChild(i);
            }),
                s
        );
    }
    setCellClass(e, t) {
        this.cells[e].addClass(t);
    }
}
class JSTableCell {
    constructor(e) {
        (this.textContent = e.textContent),
            (this.innerHTML = e.innerHTML),
            (this.className = ""),
            (this.element = e),
            (this.hasSortable = e.hasAttribute("data-sortable")),
            (this.isSortable = this.hasSortable ? "true" === e.getAttribute("data-sortable") : null),
            (this.hasSort = e.hasAttribute("data-sort")),
            (this.sortDirection = e.getAttribute("data-sort")),
            (this.classes = []);
        var t = this;
        (this.attributes = {}),
            [...e.attributes].forEach(function (e) {
                t.attributes[e.name] = e.value;
            });
    }
    getElement() {
        return this.element;
    }
    getTextContent() {
        return this.textContent;
    }
    getInnerHTML() {
        return this.innerHTML;
    }
    setClass(e) {
        this.className = e;
    }
    setSortable(e) {
        this.isSortable = e;
    }
    addClass(e) {
        this.classes.push(e);
    }
    removeClass(e) {
        this.classes.indexOf(e) >= 0 && this.classes.splice(this.classes.indexOf(e), 1);
    }
    addAttribute(e, t) {
        this.attributes[e] = t;
    }
}
class JSTablePager {
    constructor(e) {
        this.instance = e;
    }
    getPages() {
        let e = Math.ceil(this.instance.getDataCount() / this.instance.config.perPage);
        return 0 === e ? 1 : e;
    }
    render() {
        var e = this.instance.config;
        let t = this.getPages(),
            s = document.createElement("ul");
        s.setAttribute("class", "pagination");
        if (t > 1) {
            let a = 1 === this.instance.currentPage ? 1 : this.instance.currentPage - 1,
                r = this.instance.currentPage === t ? t : this.instance.currentPage + 1;
            e.firstLast && s.appendChild(this.createItem("page-item", 1, e.firstText)),
            e.nextPrev && s.appendChild(this.createItem("page-item", a, e.prevText)),
                this.truncate().forEach(function (e) {
                    s.appendChild(e);
                }),
            e.nextPrev && s.appendChild(this.createItem("page-item", r, e.nextText)),
            e.firstLast && s.appendChild(this.createItem("page-item", t, e.lastText));
        }
        return s;
    }
    createItem(e, t, s, a) {
        let r = document.createElement("li");
        return (r.className = e), (r.innerHTML = a ? "<span>" + s + "</span>" : '<a class="page-link" href="#" data-page="' + t + '">' + s + "</a>"), r;
    }
    isValidPage(e) {
        return e > 0 && e <= this.getPages();
    }
    truncate() {
        var e,
            t = this,
            s = t.instance.config,
            a = 2 * s.pagerDelta,
            r = t.instance.currentPage,
            i = r - s.pagerDelta,
            n = r + s.pagerDelta,
            l = this.getPages(),
            o = [],
            c = [];
        if (this.instance.config.truncatePager) {
            r < 4 - s.pagerDelta + a ? (n = 3 + a) : r > this.getPages() - (3 - s.pagerDelta + a) && (i = this.getPages() - (2 + a));
            for (var h = 1; h <= l; h++) (1 === h || h === l || (h >= i && h <= n)) && o.push(h);
            o.forEach(function (a) {
                e && (a - e == 2 ? c.push(t.createItem("", e + 1, e + 1)) : a - e != 1 && c.push(t.createItem(s.classes.ellipsis, 0, s.ellipsisText, !0))), c.push(t.createItem(a == r ? "active" : "", a, a)), (e = a);
            });
        } else for (let e = 1; e <= this.getPages(); e++) c.push(this.createItem(e === r ? "active" : "", e, e));
        return c;
    }
}
window.JSTable = JSTable;