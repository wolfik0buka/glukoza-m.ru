<template>
    <div>

        <div class="col-xs-12">
            <div class="radio" v-for="delivery in types">
                <label>
                    <input type="radio"
                           :value="delivery.id"
                           v-model="type">{{ delivery.name_full }}
                </label>
            </div>
        </div>

        <div class="col-xs-12">
            <p><strong>Выберите магазин:</strong></p>
            <div class="form-group">
                <select class="form-control"
                        name="delivery"
                        v-model="order.delivery">
                    <option v-for="shop in localShops" :value="shop.id">{{ shop.name }}</option>
                </select>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'basketDeliveryPickup',
        props: ['types'],
        data(){
            return{
                type: 3,
                localShops: [
                    {id: 5, name: 'г. Санкт-Петербург, ул. Сикейроса д. 10 к. 4 лит А ТК "Бульвар" помещение 4/2'},
                    {id: 1, name: 'г. Санкт-Петербург, Большой Сампсониевский пр., д. 62, оф. 202'},
                    {id: 8, name: 'г. Вологда, ул.Благовещенская, д. 26'}
                ]
            }
        },
        computed: {
            order: {
                get() {
                    return this.$store.getters.order
                },
                set() {
                    return this.$store.dispatch('updateOrder', this.order)
                }
            },
        },
        methods: {
            setDelivery(shop) {
                this.order.delivery = shop.id
                this.order.dop_fld = shop.name
                this.$store.dispatch('updateOrder', this.order)
            }
        },
        beforeMount() {
            this.type = this.types[0]
        }
    }
</script>
