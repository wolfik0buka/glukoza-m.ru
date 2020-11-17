module.exports = {
    doers: [
        {
            id: false,
            name: "Любой",
            is(val) {
                return val === false;
            }
        },
        {
            id: -1,
            name: "Не назначен",
            is(val) {
                return val === -1;
            }
        },
        {
            id: 1,
            name: "Комендантский",
            is(val) {
                return val === 1;
            }
        },
        {
            id: 2,
            name: "Ладожская",
            is(val) {
                return val === 2;
            }
        },
        {
            id: 3,
            name: "Озерки",
            is(val) {
                return val === 3;
            }
        },
    ],
    leadTypes: [
        {
            id: false,
            name: "Любой",
            is(val) {
                return val === false;
            }
        },
        {
            id: 0,
            name: "Заказ",
            is(val) {
                return val === -1;
            }
        },
        {
            id: 1,
            name: "Запрос",
            is(val) {
                return val === 1;
            }
        },
        {
            id: 2,
            name: "Онмеди",
            is(val) {
                return val === 2;
            }
        }
    ],
    filters: storage.get("orders_filters"),
    productsFilters: storage.get("productsFilters"),
    settings: storage.get("settings"),
    products: [],
    product: false,
    order: false,
    orderCancelReasons: [],
    sberbankOrderStatus: false,
    orders: false,
    leads: false,
    deliveries: false,
    cats: false,
    error: false
};