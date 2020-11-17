<template>
    <tbody v-if="[2,3,11].indexOf(order.delivery) > -1">
        <tr>
            <td colspan="3">
                <div class="card__title font-w500 font-roboto mt-15">Cdek</div>
            </td>
        </tr>
        <tr v-if="!delivery_track_id">
            <td>Заказ в СДЭК</td>
            <td>
                <button
                    @click="$store.dispatch('cdekCreateOrder')"
                    :disabled="!isCreateOrderCdekAvailable"
                    class="btn btn-default">
                    Выгрузить заказ в CDEK
                </button>
                <div
                    v-if="+order.weight<1"
                    class="orderEditor__timeshot">
                    Укажите вес
                </div>
                <div
                    v-if="isCdekStreetAndHouseRequired && !isCdekStreetAndHouseAdded"
                    class="orderEditor__timeshot">
                    Укажите точный адрес
                </div>
            </td>
        </tr>
        <tr v-if="isCdekStreetAndHouseRequired">
            <td class="pt-15">
                Точный адрес
            </td>
            <td>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="pt-0">Улица</label>
                        <input
                            @change="$store.dispatch('updateOrder', order)"
                            type="text"
                            class="control"
                            v-model="order.adr_street">
                    </div>
                    <div class="col-sm-3 pl-0">
                        <label class="pt-0">Дом</label>
                        <input
                            @change="$store.dispatch('updateOrder', order)"
                            type="text"
                            class="control"
                            v-model="order.adr_house">
                    </div>
                    <div class="col-sm-3 pl-0">
                        <label class="pt-0">Кв./Офис</label>
                        <input
                            @change="$store.dispatch('updateOrder', order)"
                            type="text"
                            class="control"
                            v-model="order.adr_flat">
                    </div>
                </div>
        
            </td>
        </tr>
        <tr>
            <td>
                Вес посылки,
                <small class="text-muted">гр.</small>
            </td>
            <td>
                <div class="row">
                    <div class="col-sm-4">
                        <input
                            @change="$store.dispatch('updateOrder', order)"
                            type="number"
                            class="control"
                            v-model.number="order.weight">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                Размер коробки,
                <small class="text-muted">см</small>
            </td>
            <td>
                <div class="btn-group">
                    <button
                        @click="setPackageSize(size)"
                        v-for="size in packageSizes"
                        class="btn"
                        :class="[(order.size_x===size.size_x && order.size_y===size.size_y && order.size_z===size.size_z && !isCustomPackageSize) ? 'btn-success' : 'btn-default']"
                        :key="'packageSize_'+size.id">
                        {{ size.name }}
                    </button>
                    <button
                        @click="showCustomPackageSize=!showCustomPackageSize"
                        class="btn"
                        :class="[isCustomPackageSize ? 'btn-success' : 'btn-default']"
                        :key="'packageSize_custom'">
                        Свой размер
                    </button>
                </div>
                <div v-if="showCustomPackageSize" class="row">
                    <div class="col-sm-4">
                        <label>Ширина</label>
                        <input
                            @change="$store.dispatch('updateOrder', order)"
                            :disabled="isCdekOrderCreated"
                            type="text"
                            class="control"
                            v-model.number="order.size_x">
                    </div>
                    <div class="col-sm-4">
                        <label>Высота</label>
                        <input
                            @change="$store.dispatch('updateOrder', order)"
                            :disabled="isCdekOrderCreated"
                            type="text"
                            class="control"
                            v-model.number="order.size_y">
                    </div>
                    <div class="col-sm-4">
                        <label>Глубина</label>
                        <input
                            @change="$store.dispatch('updateOrder', order)"
                            :disabled="isCdekOrderCreated"
                            type="text"
                            class="control"
                            v-model.number="order.size_z">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>Трек-номер СДЭК</td>
            <td>
                <input type="number" class="control" v-model.number="delivery_track_id">
                <div v-if="delivery_track_id">
                    <div>
                        <a
                            target="_blank"
                            class="btn btn-default mt-10"
                            :href="'https://lk.cdek.ru/print/print-order?numberOrd='+delivery_track_id">
                            Распечатать накладную СДЭК
                        </a>
                    </div>
                    <a
                        target="_blank"
                        class="btn btn-default mt-10"
                        :href="'/track-orders/'+delivery_track_id">
                        Открыть отслеживание заказа
                    </a>
                    <div></div>
                    <button
                        @click="$store.dispatch('sendSmsCdekTrackingCode')"
                        class="btn btn-default mt-10">
                        Отправить СМС с трек-кодом покупателю
                    </button>
                    <div class="orderEditor__timeshot" v-if="order.delivery_track_sended_at">
                        СМС с трек-кодом отправлено {{ order.delivery_track_sended_at }}
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script>
    module.exports = {
        name: "orderProcessCdek",
        props: [],
        data() {
            return {
                packageSizes: require("./orderCdekPackageSizes").default,
                showCustomPackageSize: false
            };
        },
        computed: {
            order() {
                return this.$store.state.order;
            },
            delivery_track_id: {
                get() {
                    return this.order.delivery_track_id;
                },
                set(val) {
                    this.order.delivery_track_id = val;
                    this.$store.dispatch("updateOrder", this.order);
                },
            },
            isCreateOrderCdekAvailable() {
                let pass = !this.isCdekOrderCreated && +this.order.weight > 0;
                if (pass && this.isCdekStreetAndHouseRequired) {
                    pass = this.isCdekStreetAndHouseAdded;
                }
                return pass;
            },
            isCdekOrderCreated() {
                return (''+this.delivery_track_id).length > 4;
            },
            isCustomPackageSize() {
                return _.findIndex(this.packageSizes, {
                    size_x: this.order.size_x,
                    size_y: this.order.size_y,
                    size_z: this.order.size_z
                }) < 0;
            },
            isCdekStreetAndHouseAdded() {
                if (inArray([2,3],this.order.delivery)) {
                    return !_.isEmpty(this.order.adr_house) && !_.isEmpty(this.order.adr_street);
                } else return false;
            },
            isCdekStreetAndHouseRequired() {
                return inArray([2,3],this.order.delivery);
            }
        },
        methods: {
            setPackageSize(size) {
                this.order.size_x = size.size_x;
                this.order.size_y = size.size_y;
                this.order.size_z = size.size_z;
                this.showCustomPackageSize = false;

                this.$store.dispatch("updateOrder", this.order);
            }
        },
        mounted() {
            if (this.isCustomPackageSize) {
                this.showCustomPackageSize = true;
            }
        }
    };
</script>

<style lang="scss">

</style>