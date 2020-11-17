import Vue from "vue";
import Vuex from "vuex";
import VueRouter from "vue-router";
import axios from "axios";

window.Vue = Vue;
Vue.config.devtools = (process.env.NODE_ENV !== "production");

Vue.use(Vuex);

window.VueRouter = VueRouter;
Vue.use(VueRouter);

window.axios = axios;

window.App = {};

window._ = require("lodash");
window.__ = {};

__.scrollTopCurrent = function() {
    return window.pageYOffset || document.documentElement.scrollTop;
};

require("./components/lodash");
require("lazysizes");
require("./components/yandex_metrika");

window.moment = require("./components/libs/moment/moment");
moment.locale("ru");

$script(["https://cdn.glukoza-med.ru/js/maskedinput.js"], "maskedinput");

window.VueScript2 = require("vue-script2/dist/vue-script2.min");

axios.defaults.headers.common["X-CSRF-TOKEN"] = LaravelToken;

App.openInNewTab = function(url) {
    window.open(url, "_blank");
};

App.formatPhone = (phone) => {
    if (!phone) return null;
    let digits = phone.replace(/^8/, "7").replace(/[^0-9]/gim, "").replace(/[^\d]+/, "");
    if (digits.length < 11) {
        return phone;
    }
    // Домашний?
    if (digits.substr(0, 2) === "74") {
        // Для белгородских (и похожих) домашних возвращаем нормальное форматирование. Для остальных пытаемся
        return digits.substr(0, 3) === "747" ?
            digits.replace(/^(\d)(\d+)(\d\d)(\d\d)(\d\d)$/, "+$1 ($2) $3‒$4‒$5") :
            digits.replace(/^(\d)(\d+)(\d\d\d)(\d\d)(\d\d)$/, "+$1 ($2) $3‒$4‒$5");
    }
    // Для русских мобильных возвращаем нормальное форматирование. Для остальных пытаемся
    return digits.substr(0, 2) === "79" ?
        digits.replace(/^(\d)(\d+)(\d\d\d)(\d\d)(\d\d)$/, "+$1 $2 $3‒$4‒$5") :
        digits.replace(/^(\d)(\d+)(\d\d\d)(\d\d)(\d\d)$/, "+$1$2 $3‒$4‒$5");
};

App.isValidPhone = (phone) => {
    if (!phone) return false;
    let digits = phone.replace(/^8/, "7").replace(/[^0-9]/gim, "").replace(/[^\d]+/, "");
    if (digits.length < 11) {
        return false;
    }
    else {
        return true;
    }
};

require("./less/public.less");
import "../node_modules/swiper/dist/css/swiper.css";
import {swiper, swiperSlide} from "vue-awesome-swiper";
import RequestProductOutOfStock from "./components/RequestProductOutOfStock.vue";


Vue.directive("onClickaway", require("./components/clickAway").default);
Vue.component("swipe", swiper);
Vue.component("swipe-item", swiperSlide);
Vue.component("headerNav", require("./components/headerNav.vue").default);
Vue.component("headerCart", require("./components/headerCart.vue").default);
Vue.component("addToBasket", require("./components/addToBasket.vue").default);
Vue.component("notice-worktime", require("./components/notices/notice-worktime.vue").default);
Vue.component("request-product-out-of-stock", RequestProductOutOfStock);


const store = new Vuex.Store({
    state: state,
    mutations: {
        set(state, payload) {
            state[payload[0]] = payload[1];
        },
        setExpandMenuActiveCat(state, id) {
            state.catLevelOneActive = id;
        },
        setExpandCatState(state, payload) {
            state.isExpandMenuOpened = payload;
        }
    },
    actions: {
        setIsMobile(store) {
            store.commit("set", ["isMobile", window.matchMedia("all and (max-width: 768px)").matches]);
        }
    },
    modules: {
        basketStore: require("./components/basket/basketStore")
    }
});

const router = new VueRouter({
    mode: "history",
    base: "/",
    routes: [
        {
            path: "/",
            name: "mainPage",
            components: {
                "bestOffers": require("./components/mainPage/bestOffers.vue").default,
                "mainSlider": require("./components/mainPage/mainSlider.vue").default,
                "mainSliderSmall": require("./components/mainPage/mainSliderSmall.vue").default,
            }
        },
         {
            path: "/checkout",
            name: "checkout",
            components: {
                "basketLayout": require("./components/basket/basketLayout.vue").default,
            }
        },
    ],
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

Vue.filter("stripWhitespaces", function(str) { return str.replace(/\s+/g, ""); });

new Vue({
    el: "#app",
    store,
    router,
    methods: {
        setIsMobile() {
            this.$store.dispatch("setIsMobile");
        }
    },
    mounted() {
        this.setIsMobile();
        document.addEventListener("scroll", this.setIsMobile);
        window.addEventListener("resize", this.setIsMobile);
         initialOrder
            ? this.$store.commit("initOrder", initialOrder)
            : false;
        this.$store.commit("setUser", user_id);
    }
});