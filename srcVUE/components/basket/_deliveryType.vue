<template>
    <div class="basket__delivery">
        <div class="card basket__panel">
            
            <div class="panel-body">
                
                <h2>Способ получения</h2>
                
                <div class="basket__formItem top-10">
                    <div class="basket__formLabel">
                        Выберите ваш город
                    </div>
                    <div class="basket__formContainer">
                        <div class="radio">
                            <label>
                                <input type="radio" v-model="activeCity" :value="1">
                                <div class="input-helper"></div>
                                Санкт-Петербург
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" v-model="activeCity" :value="3">
                                <div class="input-helper"></div>
                                Другой город РФ
                            </label>
                        </div>
                    </div>
                </div>
                
                <div v-if="activeCity" class="basket__formItem bottom-0">
                    <div class="basket__formLabel">
                        Доступные способы получения:
                    </div>
                    <div class="basket__formContainer">
                        
                        <div v-if="activeCity===1">
                            
                            <div class="radio">
                                <label>
                                    <input type="radio" v-model="delivery_id" :value="5">
                                    {{ deliveryModel(5).name }}
                                    <div class="input-helper"></div>
                                    <div class="text-muted font-s13">
                                        <div v-if="deliveryModel(5).shop_address">
                                            {{ deliveryModel(5).shop_address }}
                                        </div>
                                        <div class="top-3 bottom-10" v-if="deliveryModel(5).is_free">
                                            <span class="label label-success">Бесплатно</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="radio">
                                <label>
                                    <input type="radio" v-model="delivery_id" :value="11">
                                    <div class="input-helper"></div>
                                    {{ deliveryModel(11).name }}
                                    <div class="top-3 bottom-10">
                                        <span class="label label-success">Бесплатно при оплате картой онлайн</span><br>
                                        <span class="label label-default">150 руб.</span>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="radio">
                                <label>
                                    <input type="radio" v-model="delivery_id" :value="3">
                                    <div class="input-helper"></div>
                                    {{ deliveryModel(3).name }}
                                    <div class="text-muted font-s13">
                                        <div v-if="deliveryModel(3).shop_address">
                                            {{ deliveryModel(3).shop_address }}
                                        </div>
                                        <div class="top-3 bottom-10">
                                            <span class="label label-default">{{ deliveryModel(3).initial_price }} руб.</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            
                            <div v-show="isDayToDayDeliveryAvailable" class="radio">
                                <label>
                                    <input type="radio" v-model="delivery_id" :value="14">
                                    <div class="input-helper"></div>
                                    {{ deliveryModel(14).name }}
                                    <div class="text-muted font-s13">
                                        <div v-if="deliveryModel(14).shop_address">
                                            {{ deliveryModel(14).shop_address }}
                                        </div>
                                        <div class="top-3 bottom-10">
                                            <span class="label label-default">{{ deliveryModel(14).initial_price }} руб.</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        
                        </div>
                        
                        <div v-if="activeCity === 3" class="expand">
                            <div class="radio">
                                <label>
                                    <input type="radio" v-model="delivery_id" :value="11">
                                    <div class="input-helper"></div>
                                    Самовывоз из пункта выдачи
                                    <div class="text-muted font-s14 bottom-15">Пункты выдачи в 310 городах России</div>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" v-model="delivery_id" :value="2">
                                    <div class="input-helper"></div>
                                    {{ deliveryModel(2).name }}
                                    <div class="text-muted font-s14 bottom-10">
                                        Стоимость доставки зависит от региона
                                    </div>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" v-model="delivery_id" :value="4">
                                    <div class="input-helper"></div>
                                    {{ deliveryModel(4).name }}
                                    <div class="top-0">
                                        <span class="label label-default">
                                            {{ deliveryModel(4).initial_price }} руб.
                                        </span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        
        </div>
        
        <div v-if="delivery_id" class="card basket__panel top--31">
            <div class="panel-body">
                <h2 class="bottom-30">{{ order.dop_fld }}</h2>
                
                <basketDeliverySpbCourier
                    v-if="[3,14].indexOf(delivery_id) !== -1 && activeCity === 1">
                </basketDeliverySpbCourier>
                
                <basketDeliveryRussiaCourier
                    v-if="delivery_id === 2 && activeCity === 3">
                </basketDeliveryRussiaCourier>
                
                <basketDeliveryRussianPost
                    v-if="delivery_id === 4 && activeCity === 3">
                </basketDeliveryRussianPost>
                
                <basketDeliveryRussiaPickupPoint
                    v-if="delivery_id === 11 && (activeCity === 1 || activeCity === 3)">
                </basketDeliveryRussiaPickupPoint>
                
                <basketPickupFromStore
                    v-if="[1,5,8,13].indexOf(delivery_id) !== -1">
                </basketPickupFromStore>
            
            </div>
        </div>
    
    </div>
</template>

<script>
    module.exports = {
        name: "basketDeliveryType",
        computed: {
            order: {
                get() {
                    return this.$store.getters.order;
                },
                set() {
                    this.$store.dispatch("updateOrder", this.order);
                }
            },
            activeCity: {
                get() {
                    return this.$store.getters.order.activeCity;
                },
                set(val) {
                    this.order.activeCity = val;
                    this.order.delivery = null;

                    this.order.delivery_days = null;
                    this.order.delivery_price = null;
                    this.order.delivery_is_free = false;
                    this.order.deliveryPoint = false;
                    this.order.city_id = null;
                    this.order.city_name = null;
                    this.order.dop_fld = null;
                    this.order.date_of_delivery = null;
                    this.order.post_index = null;

                    this.$store.dispatch("updateDelivery");
                },
            },
            delivery_id: {
                get() {
                    return this.$store.getters.order.delivery;
                },
                set(val) {
                    this.order.delivery = val;
                    this.order.dop_fld = this.deliveryModel(val).name;
                    this.order.date_of_delivery = null;
                    this.order.deliveryPoint = false;
                    this.order.delivery_days = null;
                    this.order.city_id = null;
                    this.order.city_name = null;

                    this.$store.dispatch("updateDelivery");
                }
            },
            isDayToDayDeliveryAvailable() {
                return moment(new Date).hour() < 16;
            }
        },
        methods: {
            deliveryModel(id) {
                return this.$store.getters.deliveryModel(id);
            }
        },
        components: {
            basketDeliverySpbCourier: require("./_deliverySpbCourier.vue").default,
            basketDeliveryRussiaCourier: require("./basketDeliveryRussiaCourier.vue").default,
            basketDeliveryRussianPost: require("./basketDeliveryRussianPost.vue").default,
            basketDeliveryRussiaPickupPoint: require("./basketDeliveryRussiaPickupPoint.vue").default,
            basketPickupFromStore: require("./basketPickupFromStore.vue").default
        }
    };
</script>
