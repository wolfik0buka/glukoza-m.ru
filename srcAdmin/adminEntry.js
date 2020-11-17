import {libs} from "./adminVendor";


window.App = {};

App.openInNewTab = url => {
    let win = window.open(url, '_blank');
    win.focus();
}

App.urlParams = () => {
    var queryString = window.location.search.slice(1);
    var obj = {};

    if (queryString) {
        queryString = queryString.split("#")[0];
        var arr = queryString.split("&");

        for (var i = 0; i < arr.length; i++) {
            var a = arr[i].split("=");
            var paramName = a[0];
            var paramValue = typeof (a[1]) === "undefined" ? true : a[1];
            paramName = paramName.toLowerCase();
            if (typeof paramValue === "string") paramValue = paramValue.toLowerCase();

            if (paramName.match(/\[(\d+)?\]$/)) {
                var key = paramName.replace(/\[(\d+)?\]/, "");
                if (!obj[key]) obj[key] = [];
                if (paramName.match(/\[\d+\]$/)) {
                    var index = /\[(\d+)\]/.exec(paramName)[1];
                    obj[key][index] = paramValue;
                } else {
                    obj[key].push(paramValue);
                }
            } else {
                if (!obj[paramName]) {
                    obj[paramName] = paramValue;
                } else if (obj[paramName] && typeof obj[paramName] === "string") {
                    obj[paramName] = [obj[paramName]];
                    obj[paramName].push(paramValue);
                } else {
                    obj[paramName].push(paramValue);
                }
            }
        }
    }
    if (typeof obj.from === "undefined") {
        obj.from = moment().subtract(3, "months").startOf("month").format("YYYY-MM-DD");
    }
    if (typeof obj.to === "undefined") {
        obj.to = moment().format("YYYY-MM-DD");
    }

    return obj;
}

window._ = require("lodash");


const moment = require('../srcVUE/components/libs/moment/moment');
require('../srcVUE/components/libs/moment/locale/ru.js');
moment.locale('ru');
moment().format();
window.moment = moment;

window.inArray = function(array, value) { return array.indexOf(value) !== -1; };

if (!Array.prototype.find) {
    Object.defineProperty(Array.prototype, 'find', {
        value: function(predicate) {
            if (this == null) { throw new TypeError('"this" is null or not defined'); }
            var o = Object(this);
            var len = o.length >>> 0;
            if (typeof predicate !== 'function') { throw new TypeError('predicate must be a function'); }
            var thisArg = arguments[1];
            var k = 0;
            while (k < len) {
                var kValue = o[k];
                if (predicate.call(thisArg, kValue, k, o)) { return kValue; }
                k++;
            }
            return undefined;
        },
        configurable: true,
        writable: true
    });
}


window.Mess = require("../srcVUE/components/libs/mess");
Mess.init();

window.VueScript2 = require("vue-script2/dist/vue-script2.min");

import axios from "axios";
//axios.defaults.headers.common["X-CSRF-TOKEN"] = document.querySelector("meta[name="csrf-token"]").getAttribute("content")
window.axios = axios;

require("vue-wysiwyg/dist/vueWysiwyg.css");

window.storage = require("store");
storage.addPlugin(require("store/plugins/expire"));
storage.addPlugin(require("store/plugins/defaults"));
storage.defaults({
    orders_filters: {
        query: "",
        year: false,
        doer: false,
        delivery: false,
        leadType: false,
        showDoneOrder: 1,
        dateFrom: false,
        dateTo: false
    },
    productsFilters: {
        query: "",
    },
    settings: {
        showDatesInOrders: false,
        orders_showMonthNames: true,
        orders_showDayNames: true,
        orders_showSummary: false,
    }
});



import('./adminVendor.js').then(function(vendor) {

    App.vendor = vendor.default;

    window.Vue = App.vendor.vue;
    window.VueRouter = App.vendor.vueRouter;
    window.Vuex = App.vendor.vuex;

    Vue.use(VueRouter);
    Vue.use(Vuex);
    Vue.config.devtools = (process.env.NODE_ENV !== "production");
    Vue.use(App.vendor.wysiwyg, {
        // modules: bold, italic, justifyLeft, justifyCenter, justifyRight, headings, orderedList, unorderedList, table, removeFormat
        hideModules: {
            code: true,
            image: true,
            link: true,
            separator: true,
            underline: true
        },
        forcePlainTextOnPaste: true
    });

    window.eventbus = new Vue();

    const store = new Vuex.Store(require("./adminStore").default);

    const router = new VueRouter({
        mode: "history",
        base: "/",
        routes: [],
    });

    Vue.filter("numString", function(number, text_forms) {
        let forms = text_forms.split("|");
        let n = Math.abs(number) % 100;
        let n1 = number % 10;
        let result_form = false;

        if (n > 10 && n < 20) {
            result_form = forms[2];
        }
        else if (n1 > 1 && n1 < 5) {
            result_form = forms[1];
        }
        else if (n1 == 1) {
            result_form = forms[0];
        }
        else {
            result_form = forms[2];
        }

        return `${number} ${result_form}`;
    });

    /**
     * Отформатировать телефонный номер.
     * Для мобильных код отбивается пробелами, для домашних — скобками.
     * Грамотно обрабатываются только русские номера,
     * для остальных функция пытается отформатировать более-менее нормально.
     * Для отбивания цифр используются не просто дефисы, а специальные цифровые чёрточки, не хухры.
     *
     * Примеры:
     *  * 74722112233 → +7 (4722) 11‒22‒33
     *  * 84951112233 → +7 (495) 111‒22‒33
     *  * 89201112233 → +7 920 111‒22‒33
     *  * 19991112233 → +1999 111‒22‒33
     *  * 112233      → 112233
     *
     * @param {string} phone Телефонный номер
     * @returns {string} Возвращает отформатированный телефонный номер
     */
    Vue.filter("formatPhone", function(phone) {
        if (!phone) return null;
        let digits = phone.replace(/^8/, "7").
            replace(/[^0-9]/gim, "").
            replace(/[^\d]+/, "");
        if (digits.length < 11) {
            return phone;
        }
        // Домашний?
        if (digits.substr(0, 2) === "74") {
            // Для белгородских (и похожих) домашних возвращаем нормальное форматирование
            // Для остальных пытаемся
            return digits.substr(0, 3) === "747" ?
                digits.replace(/^(\d)(\d+)(\d\d)(\d\d)(\d\d)$/, "+$1 ($2) $3‒$4‒$5") :
                digits.replace(/^(\d)(\d+)(\d\d\d)(\d\d)(\d\d)$/, "+$1 ($2) $3‒$4‒$5");
        }
        // Для русских мобильных возвращаем нормальное форматирование
        // Для остальных пытаемся
        return digits.substr(0, 2) === "79" ?
            digits.replace(/^(\d)(\d+)(\d\d\d)(\d\d)(\d\d)$/, "+$1 $2 $3‒$4‒$5") :
            digits.replace(/^(\d)(\d+)(\d\d\d)(\d\d)(\d\d)$/, "+$1$2 $3‒$4‒$5");
    });

    Vue.filter("momentFormat", function(datetime, format) { return moment(datetime, "YYYY-MM-DD hh:mm:ss").format(format); });
    Vue.filter("bigFirstLetter", function(str) { if (!str) return str; return str[0].toUpperCase() + str.slice(1); });
    Vue.filter("onlyDigits", function(str) { return +(''+str.replace(/\D/g, '').trim()); });

    window.formatCurrency = function(value) {
        let val = (value / 1).toFixed(2).replace(".", ",");
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    };
    Vue.filter("currency", formatCurrency);

    Vue.directive("onClickaway", require("../srcVUE/components/clickAway").default);
    Vue.component("numberSpin", require("../srcVUE/components/numberSpin.vue").default);
    Vue.component("priceEditor", require("../srcVUE/components/priceEditor.vue").default);
    Vue.component("textEditor", require("../srcVUE/components/textEditor.vue").default);
    Vue.component("order", require("./order/Order.vue").default);
    Vue.component("point-selector", require("./order/PointSelector.vue").default);
    Vue.component("page-sklad", require("./sklad/pageSklad.vue").default);
    Vue.component("collections-list", require("./collections/Collections.vue").default);
    Vue.component("reports", require("./reports/Reports.vue").default);
    Vue.component("orders-page", require("./orders/ordersPage.vue").default);
    Vue.component("product", require("./product/Product.vue").default);
    Vue.component("products", require("./products/Products.vue").default);
    Vue.component("single-upload", require("./singleUpload.vue").default);
    Vue.component("control-select", require("./selectControl.vue").default);
    Vue.component("modal", require("../srcVUE/components/Modal.vue").default);


    App.instance = new Vue({
        el: "#app",
        store,
        router,
        components: {},
        mounted() {         
            eventbus.$on("updateDeliveryProduct", () => {
                this.$store.dispatch("updateDeliveryProduct");
            });
        }
    });
});

require("./less/adminStyles.less");
