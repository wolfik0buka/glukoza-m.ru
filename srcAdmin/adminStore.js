"use strict";
var _ = require('lodash');
var host = '';
import getterOrders from "./Store/getter_orders";

//module.exports
export default  {
    state: require("./Store/state"),
    mutations: {
        set(state, payload) {
            state[payload[0]] = payload[1];
        },
        subset(state, payload) {
            state[payload[0]][payload[1]] = payload[2];
        },
        setOrder(state, order) {
            if (order.error) {
                state.error = order.message;
            } else {
                state.order = order;
            }
        },
        setProduct(state, product) {
            state.product = product;
        },
        setProducts(state, products) {
            state.products = products;
        },
        setResponses(state, responses) {
            state.responses = responses;
        },
        setResponses(state, response) {
            state.response = response;
        },
        addProductToOrder(state, payload) {
            state.order.product_links.push(payload);
        },
        removeOrderProduct(state, link_id) {
            let index = _.findIndex(state.order.product_links, {id: link_id});
            state.order.product_links.splice(index, 1);
        },
        updateOrderProduct(state, link) {
            console.log("commit: updateOrderProduct");
            let index = _.findIndex(state.order.product_links, {id: link.id});
            state.order.product_links[index] = link;
        },
        setOrderCity(state, payload) {
            state.order.city_id = payload.city_id;
            state.order.city_name = payload.city_name;
        },
        setOrderDelivery(state, payload) {
            state.order.delivery_days = typeof payload.delivery_days === "number" ? payload.delivery_days : null;
            state.order.delivery_price = typeof payload.delivery_price === "number" ? payload.delivery_price : 0;
            state.order.delivery_point_id = typeof payload.delivery_point_id === "number" ? payload.delivery_point_id : null;
        },
    },
    actions: {
        makeTooltips() {
            setTimeout(function() {
                $("[data-toggle='tooltip']").tooltip();
            }, 1000);
        },
        getOrder(store, id) {
            return new Promise((resolve, reject) => {
                axios.post(host + "/admin/orders/" + id).then(response => {
                    store.commit("setOrder", response.data);
                    store.dispatch("makeTooltips");
                    resolve(response.data);
                }).catch(() => {
                    reject();
                });
            });
        },
        getProduct(store, id) {
            axios.post(host +`/admin/products/` + id).then(response => {
                store.commit("setProduct", response.data);
            });
        },
        getProducts(store, id) {
            axios.post(host + `/admin/products/all`).then(response => {
                store.commit("setProducts", response.data);
            });
        },
        

        addProduct(store, product) {
            axios.post(host + "/admin/products/add", product).then(response => {
                window.location.href = `/admin_new/index.php?page=nom&id=${response.data.product_id}`;
            });
        },
        updateProduct(store, product) {
            return new Promise((resolve, reject) => {
                if (product) {
                    product.weight = +product.weight;
                    product.size_x = +product.size_x;
                    product.size_y = +product.size_y;
                    product.size_z = +product.size_z;
                    store.commit("setProduct", product);
                }
                axios.post(host + "/admin/products/update", store.state.product).then(() => {
                    resolve();
                });
            });
        },
        getCats(store) {
            axios.post(host + "/admin/cats").then(response => {
                store.commit("set", ["cats", response.data]);
            });
        },
        getDeliveries(store) {
            axios.post(host + "/admin/deliveries").then(response => {
                store.commit("set", ["deliveries", _.keyBy(response.data, "id")]);
            });
        },
        setOrderFilterDates(store, dates) {
            store.commit("subset", ["filters", "dateFrom", dates.dateFrom]);
            store.commit("subset", ["filters", "dateTo", dates.dateTo]);
            store.dispatch('saveFilters');

            let url = host + '/admin_new/index.php?page=orders';
            if (dates.dateFrom) {
                url = `/admin_new/index.php?page=orders&from=${dates.dateFrom}&to=${dates.dateTo}`;
            }

            window.history.pushState('orders', 'Заказы', url);
        },
        getOrders(store) {
            if (store.state.orders.length > 0) { store.commit("set", ["orders", []]); }

            let url = '/admin/orders/all';
            if (App.urlParams().from && App.urlParams().to) {
                url = `${url}?from=${App.urlParams().from}&to=${App.urlParams().to}`;
            }

            axios.post(url).then(response => {
                store.commit("set", ["orders", response.data.orders]);

                store.dispatch("setOrderFilterDates", {
                    dateFrom:response.data.from,
                    dateTo:response.data.to
                });

                setTimeout(() => {
                    $("[data-toggle=\"tooltip\"]").tooltip();
                }, 1000);
            });
        },
        updateOrder(store, order = false) {
            console.log("action: updateOrder");
            return new Promise((resolve, reject) => {
                if (order) {
                    store.commit("setOrder", order);
                }
                axios.post("/admin/orders/update", store.state.order).then(() => {
                    resolve();
                });
            });
        },
        addProductToOrder(store, payload) {
            console.log("action: addProductToOrder" + payload.product_id);
            return new Promise((resolve, reject) => {
                if (!_.isUndefined(payload.product_id)) {
                    axios.post("/admin/orders/products/add", {
                        product_id: payload.product_id,
                        price: payload.price ? payload.price : 0,
                        order_id: store.state.order.id
                    }).then(response => {
                        store.commit("addProductToOrder", response.data);
                        resolve();
                    }).catch(() => {
                        reject();
                    });
                } else {
                    resolve();
                }
            });
        },
        removeOrderProduct(store, link_id) {
            return new Promise((resolve, reject) => {
                store.commit("removeOrderProduct", link_id);
                axios.get("/admin/orders/products/remove/" + link_id).
                    then(() => {
                        resolve();
                    }).
                    catch(() => {
                        reject();
                    });
            });
        },
        updateOrderProduct(store, link) {
            console.log("action: updateOrderProduct");
            return new Promise((resolve, reject) => {
                axios.post("/admin/orders/products/update", link).then(response => {
                        let order = _.cloneDeep(store.state.order);
                        order.product_links[_.findIndex(order.product_links,
                            {id: response.data.id})] = response.data;
                        store.commit("setOrder", order);
                        resolve();
                    });
            });
        },
        setOrderPickupPoint(store, point) {
            return new Promise((resolve, reject) => {
                axios.post("/admin/orders/set_point", point).then(response => {
                    let order = _.cloneDeep(store.state.order);
                    order.delivery_pickup_point = response.data;
                    order.delivery_point_id = response.data.point_id;
                    store.commit("setOrder", order);
                    store.dispatch("updateDeliveryProduct");
                    resolve();
                }).catch(() => {
                    reject();
                });
            });
        },
        updateLinkProductCats(store, links) {
            axios.post("/admin/products/update_cat_links", {
                links: links,
                product_id: store.state.product.id
            }).then(response => {
                let product = store.state.product;
                product.links_to_cats = response.data;
                store.commit("setProduct", product);
            });
        },
        saveProductsFilters(store) {
            storage.set("productsFilters", _.cloneDeep(store.state.productsFilters));
        },
        saveFilters(store) {
            storage.set("orders_filters", _.cloneDeep(store.state.filters));
        },
        saveSettings(store) {
            storage.set("settings", _.cloneDeep(store.state.settings));
        },
        removeOrderPickupPoint(store) {
            store.state.order.delivery_pickup_point = false;
            store.state.order.delivery_point_id = null;
            store.commit("setOrder", store.state.order);
        },
        removeProductDelivery(store) {
            return new Promise((resolve, reject) => {
                let link = _.find(store.state.order.product_links, l => {
                    return l.product.usluga === 1;
                });
                if (link) {
                    store.dispatch("removeOrderProduct", link.id).then(() => {
                        resolve();
                    });
                } else {
                    resolve();
                }
            });
        },
        updateDeliveryProduct(store) {

            let urlParts = [
                "services",
                "delivery",
                store.state.order.id,
                store.state.order.delivery,
                store.getters.orderProductsSum,
                (store.state.order.city_id ? store.state.order.city_id : 137)
            ];

            return new Promise((resolve, reject) => {
                if (store.state.order.delivery_price_fixed === 0) {
                    axios.get("/" + urlParts.join("/")).then(response => {
                        let current_odp = store.getters.orderDeliveryProduct;
                        let need_update_odp = false;

                        if (current_odp) { // если есть товар доставки
                            need_update_odp = !(+current_odp.product.id === +response.data.id && +current_odp.price ===
                                +response.data.price);

                            if (need_update_odp) {
                                store.dispatch("removeProductDelivery").then(() => {
                                    store.dispatch("updateDeliveryProduct", true);
                                    resolve();
                                });
                            } else {
                                resolve();
                            }
                        } else { // если нет товара доставки
                            store.dispatch("addProductToOrder", {
                                product_id: response.data.id,
                                price: response.data.price
                            }).then(() => {
                                resolve();
                            });

                        }
                    });
                } else {
                    resolve();
                }
            });
        },
        setFixedOrderDeliveryPrice(store, value) {
            store.state.order.delivery_price_fixed = value;
            store.commit("setOrder", store.state.order);
            store.dispatch("updateOrder");
            if (value === 1) {
                store.dispatch("updateDeliveryProduct");
            }
        },
        sendOrderInvoice(store) {
            axios.post(
                "/admin/orders/" + store.state.order.id + "/send_invoice", {}).
                then(response => {
                    store.state.order.send_invoice_at = moment().format("YYYY-MM-DD HH:mm:ss");
                    store.commit("setOrder", store.state.order);
                    store.dispatch("updateOrder");
                });
        },
        sendSmsOrderApproved(store) {
            axios.post("/admin/orders/sms/order_approved", {
                order_id: store.state.order.id
            }).then(function(response) {
                store.state.order.sms = moment().format("YYYY-MM-DD HH:mm:ss");
                store.commit("setOrder", store.state.order);
                store.dispatch("updateOrder");
            });
        },
        sendSmsCdekTrackingCode(store) {
            axios.post("/admin/orders/sms/cdek_tracking_code", {
                order_id: store.state.order.id
            }).then(function(response) {
                store.state.order.delivery_track_sended_at = moment().
                    format("YYYY-MM-DD HH:mm:ss");
                store.commit("setOrder", store.state.order);
                store.dispatch("updateOrder");
            });
        },
        sendSmsRussianPostTrackingCode(store) {
            axios.post("/add_post_id", {
                order_id: store.state.order.id,
                post_id: store.state.order.post_id
            }).then(() => {
                store.state.order.delivery_track_sended_at = moment().format("YYYY-MM-DD HH:mm:ss");
                store.commit("setOrder", store.state.order);
                store.dispatch("updateOrder");
            });
        },
        sendSmsPaymentDone(store) {
            axios.post("/admin/orders/sms/payment_done", {
                order_id: store.state.order.id
            }).then(function(response) {
                store.state.order.sms_payment_done_at = moment().
                    format("YYYY-MM-DD HH:mm:ss");
                store.commit("setOrder", store.state.order);
            });
        },
        sendSmsOrderTransferredToDelivery(store) {
            axios.post("/admin/orders/sms/order_transferred_to_delivery", {
                order_id: store.state.order.id
            }).then(function(response) {
                store.state.order.transferred_to_delivery_at = moment().
                    format("YYYY-MM-DD HH:mm:ss");
                store.commit("setOrder", store.state.order);
            });
        },
        sendSmsOrderChanged(store) {
            axios.post("/admin/orders/sms/order_changed", {
                order_id: store.state.order.id
            }).then(function(response) {
                store.state.order.sms_changed_at = moment().
                    format("YYYY-MM-DD HH:mm:ss");
                store.commit("setOrder", store.state.order);
            });
        },
        sendSmsOrderReadyToPickup(store) {
            axios.post("/admin/orders/sms/order_ready_to_pickup", {
                order_id: store.state.order.id
            }).then(function(response) {
                store.state.order.ready_to_pickup_at = moment().format("YYYY-MM-DD HH:mm:ss");
                store.commit("setOrder", store.state.order);
            });
        },
        sendPaymentLink(store) {
            axios.post(
                "/admin/orders/" + store.state.order.id + "/send_payment_link").
                then(response => {
                    store.state.order.paylink_sended_at = moment().
                        format("YYYY-MM-DD HH:mm:ss");
                    store.commit("setOrder", store.state.order);
                });
        },
        getSberbankOrderStatus(store) {
            return axios.post("/admin/orders/sberbank/status", {
                sberbank_order_id: store.state.order.sberbank_order_id
            }).then(response => {
                store.commit("set", ["sberbankOrderStatus", response.data]);
            });
        },
        cdekCreateOrder(store) {
            return new Promise((resolve, reject) => {
                axios.post(`/admin/cdek/create-order/${store.state.order.id}`).then(response => {
                    store.dispatch("getOrder", store.state.order.id).then(() => {
                        resolve();
                    });
                }).catch((response) => {
                    reject();
                });
            });
        },
        cloneOrder(store, orderId) {

            axios.post(`/admin/orders/${orderId}/clone`).then(r => {
                store.commit('subset', ['order', 'comment_my', store.state.order.comment_my+"\n Создан дубль заказа с id "+orderId]);
                store.dispatch('updateOrder');
                App.openInNewTab(`/admin_new/index.php?page=order&id=${r.data.id}`);
            });
        },
        getOrderCancelReasons(store) {
            return new Promise((resolve, reject) => {
                if (store.state.orderCancelReasons.length > 0) {
                    resolve(store.state.orderCancelReasons);
                } else {
                    axios.post("/admin/order-cancellation-reasons").then(response => {
                        store.commit("set", ["orderCancelReasons", response.data]);
                        resolve(store.state.orderCancelReasons);
                    }).catch(() => {
                        reject();
                    });
                }
            });
        },
        getResponses(store) {
            axios.get(host + `/admin/responses/all`).then(response => {
                store.commit("setResponses", response.data);
            });
        },
        getResponse(store, id) {
            axios.post(host +`/admin/responses/` + id).then(response => {
                store.commit("setResponse", response.data);
            });
        },
        updateResponse(store, response){
            axios.post(
                "/admin/responses/update", response).
                then(response => {
                    store.dispatch("getResponses");
                });
        }
    },
    getters: {
        orders: getterOrders,
        leads(state) {
            return state.leads;
        },
        orderProductsSum(state) {
            if (state.order) {
                return _.sumBy(state.order.product_links, link => {
                    return +link.product.usluga === 0
                        ? link.price * link.amount
                        : 0;
                });
            } else {
                return false;
            }
        },
        orderDeliveryProductIndex(state) {
            if (state.order.product_links) {
                let index = _.findIndex(state.order.product_links, link => {
                    return link.product.usluga === 1;
                });
                return (index !== -1) ? index : false;
            }
        },
        orderDeliveryProduct(state, getters) {
            return _.find(state.order.product_links, link => {
                return link.product.usluga === 1;
            });
        },
        orderFullSum(state, getters) {
            if (state.order && getters.orderProductsSum) {
                let deliveryPrice = getters.orderDeliveryProduct
                    ? getters.orderDeliveryProduct.price
                    : 0;
                return (+getters.orderProductsSum + +deliveryPrice) -
                    parseInt(state.order.bonus);
            }
            return false;
        },
        urlParams() {
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
    },
    modules: {},
    plugins: [
        require("./Store/pluginDelivery")
    ]
};