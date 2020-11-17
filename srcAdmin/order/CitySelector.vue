<template>
    <div>
        <input
            autocomplete="off"
            name="43y5824cn7584574282"
            type="text"
            id="deliveryCity"
            v-model="$store.state.order.city_name"
            placeholder="Начните вводить для поиска"
            class="form-control autocomplete">
        <div v-if="$store.state.order.delivery_price" class="mt-5">
            Цена: {{ $store.state.order.delivery_price }} руб.<br>
            Дней: {{ $store.state.order.delivery_days }}
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "CitySelector",
        data() {
            return {};
        },
        computed: {
            sourceCity() {
                return new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace("value"),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: "/services/search_city/%QUERY",
                        wildcard: "%QUERY",
                        filter: function(matches) {
                            return matches;
                        }
                    }
                });
            },
            order() {
                return this.$store.state.order;
            }
        },
        mounted() {
            this.initTypeahead();
        },
        methods: {
            initTypeahead() {
                $("#deliveryCity").typeahead(null, {
                    name: "source-city",
                    display: (item) => {
                        return `${item.name}`;
                    },
                    source: this.sourceCity
                }).on("typeahead:selected", (event, payload) => {
                    this.setCity(payload);
                });
            },
            setCity(payload) {
                this.$store.commit("setOrderCity", {
                    city_id: payload.id,
                    city_name: payload.name,
                });
                this.$store.commit("setOrderDelivery", {});
                this.$store.dispatch("updateOrder");
                this.calculatePrice();
            },
            calculatePrice() {
                let url = `/services/city/points/price/${this.order.city_id}`;
                if (this.order.delivery === 2) {
                    url = `/services/delivery_price/${this.order.city_id}`;
                }
                axios.get(url).then(response => {
                    if (!response.data.error) {
                        this.$store.commit("setOrderDelivery", {
                            delivery_days: +response.data.payload.deliveryPeriodMin + 1,
                            delivery_price: (this.order.city_id === 137) ? 150 : +response.data.payload.price
                        });
                        this.$store.dispatch("updateOrder");
                        this.$store.dispatch("updateDeliveryProduct");
                    } else {
                        Mess.show("Проблема с CDEK при расчете стоимости. Сообщите разработчику");
                    }
                });
            },
        }
    };
</script>

<style lang="less">
    .twitter-typeahead {
        display: block;
        width: 100%;
        position: relative;
    }
    
    .tt-menu {
        background-color: #fff;
        padding: 0;
        box-shadow: 0 3px 5px #928c8c;
    }
    
    .tt-suggestion.tt-selectable {
        display: block;
        padding: 5px 15px;
        &:hover, &.tt-cursor {
            background-color: #43a9e4;
            color: #fff;
        }
    }
</style>