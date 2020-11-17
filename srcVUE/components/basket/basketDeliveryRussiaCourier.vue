<template>
    <div>
        <div class="basket__formItem">
            <div class="basket__formLabel">
                Город доставки
            </div>
            <div class="basket__formContainer">
                <input type="text"
                       id="delivery_city_target"
                       v-model="order.city_name"
                       placeholder="Начните вводить для поиска"
                       class="basket__formField autocomplete">
                <div v-if="order.delivery_price || order.delivery_days" class="top-15 font-s14 text-muted">
                    <p>
                        Цена: {{ order.delivery_price }} руб.
                        <br>
                        Ориентировочный срок доставки: {{ order.delivery_days | numString('день|дня|дней') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="basket__formItem">
            <div class="basket__formLabel">
                Адрес доставки
            </div>
            <div class="basket__formContainer">
                <textarea rows="8"
                          class="basket__formField textfield fullwidth"
                          @keyup="$store.dispatch('updateOrder', order)"
                          v-model="order.adr">
                </textarea>
                <div class="basket__formHelp">
                    Улица, дом, корпус, квартира/офис
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'basketDeliveryRussiaCourier',
        computed: {
            order() {
                return this.$store.getters.order
            },
            sourceDelivery() {
                return new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: '/services/search_city/%QUERY',
                        wildcard: '%QUERY',
                        filter: function (matches) { return matches }
                    }
                })
            }
        },
        mounted() {
            this.initTypeahead()
        },
        methods: {
            initTypeahead() {
                $('#delivery_city_target').typeahead( null, {
                    name: 'source-delivery',
                    display: (item) => {
                        return `${item.name}`
                    },
                    source: this.sourceDelivery
                }).on('typeahead:selected', (event, payload) => {
                    this.setDeliveryCity(payload)
                })
            },
            setDeliveryCity(payload) {
                this.order.city_id = payload.id
                this.order.city_name = payload.name
                this.$store.dispatch('updateOrder', this.order)
                this.calculateDeliveryPrice()
            },
            calculateDeliveryPrice() {
                axios.get(`/services/delivery_price/${this.order.city_id}`).then(response => {
                        this.order.delivery_price = (this.order.city_id == 137)
                            ? 300
                            : +response.data.payload.price
                        this.order.delivery_days = +response.data.payload.deliveryPeriodMin + 1
                        this.$store.dispatch('updateOrder', this.order)
                    })
            }
        }
    }
</script>
