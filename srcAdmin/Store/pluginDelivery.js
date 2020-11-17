const pluginDelivery = function(store) {
    store.subscribe((mutation, state) => {
        if (mutation.type === "setOrderCity") {
            store.dispatch("removeOrderPickupPoint");
            if (state.order.delivery_price_fixed===0) {
                store.dispatch("removeProductDelivery").then(() => {
                    store.commit("setOrder", state.order);
                });
                store.commit("setOrderDelivery", {});
            }

        }
    });
};

module.exports = pluginDelivery;

