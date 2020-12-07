<template>
    <div class="orderEditor">
        
        <template v-if="$store.state.error">
            <div class="card card__content text-center font-w500 font-roboto font-s18">
                {{ $store.state.error }}
            </div>
        </template>
        
        <div v-if="!$store.state.error">
            
            <div class="col-xs-12 mb-15">
                
                <div class="orderEditor__head">
                    <h2 class="font-s22 mb-10">Заказ №{{ order.glukoza_number_formatted }} от {{ order.date_order}}</h2>
                    <div v-if="order && order.onmedi_order_id>0" class="orderEditor__website font-roboto">
                        <div v-if="order.onmedi_order_id>0">Источник: Онмеди №{{ order.onmedi_order_id }}</div>
                    </div>
                </div>
                
                <div class="pb-10">
                    <button
                        @click="$store.dispatch('cloneOrder', order.id)"
                        class="btn btn-default">
                        Дублировать заказ
                    </button>
                    <button
                        v-if="!$store.state.order.delivery_price_fixed"
                        @click="$store.dispatch('updateDeliveryProduct')"
                        class="btn btn-default"
                        v-cloak>
                        Пересчитать доставку
                    </button>
                    <button
                        v-if="$store.state.order.delivery_price_fixed"
                        disabled
                        class="btn btn-default"
                        v-cloak>
                        Доставка зафиксирована
                    </button>
                </div>
                
                <orderProducts></orderProducts>
            </div>
            
            <div v-if="order" class="col-xs-6 remove-padding-r">
                <div class="card">
                    <div class="card__content">
                        <div class="card__title font-w500 font-roboto">Покупатель</div>
                    </div>
                    <table class="table orderEditor__table">
                        <thead>
                            <tr>
                                <th class="remove-border remove-padding" width="150px"></th>
                                <th class="remove-border remove-padding"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ФИО</td>
                                <td>
                                    <input
                                        type="text"
                                        class="control"
                                        @keyup="updateOrder()"
                                        v-model="order.fio">
                                </td>
                            </tr>
                            <tr>
                                <td>Телефон</td>
                                <td>
                                    <input
                                        type="text"
                                        class="control"
                                        @keyup="updateOrder()"
                                        v-model="order.phone">
                                </td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>
                                    <input
                                        type="text"
                                        class="control"
                                        @keyup=updateOrder()
                                        v-model=order.email>
                                </td>
                            </tr>
                            <tr v-if="order.comment">
                                <td>Комментарий</td>
                                <td>{{ order.comment }}</td>
                            </tr>
                            <tr>
                                <td>Способ оплаты</td>
                                <td>
                                    <select class="control" @change=changeDelivery v-model="order.payment_type_id">
                                        <option :value=1 :key=1>Наличными при получении</option>
                                        <option :value=2 :key=2>Банковской картой на сайте</option>
                                        <option :value=3 :key=3>Картой при получении</option>
                                        <option :value=4 :key=4>Банковской картой курьеру</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="card__title font-w500 font-roboto mt-15">Доставка заказа</div>
                                </td>
                            </tr>
                            <tr>
                                <td>Способ получения</td>
                                <td>
                                    <select
                                        class="control"
                                        @change="changeDelivery()"
                                        v-model="order.delivery">
                                        <option
                                            disabled
                                            :value="(+order.delivery===12 ? 12 : '')">
                                            Не выбрано
                                        </option>
                                        <option
                                            v-for="delivery in deliveries"
                                            :value="delivery.id"
                                            :key="delivery.id">
                                            {{ delivery.name}}
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr v-if="[4].indexOf(order.delivery)>=0">
                                <td>Почтовый индекс</td>
                                <td>
                                    <input type="text" class="control" v-model=postIndex>
                                </td>
                            </tr>
                            <tr v-if="[2,11].indexOf(order.delivery)>=0">
                                <td>Город</td>
                                <td>
                                    <citySelector v-if="order"></citySelector>
                                </td>
                            </tr>
                            <tr v-if="[2,3,4,14].indexOf(order.delivery)>=0">
                                <td>Адрес доставки</td>
                                <td>
                                    <textarea
                                        class="control"
                                        style="height:80px;"
                                        v-model=order.adr
                                        @change="updateOrder()"></textarea>
                                </td>
                            </tr>
                            <tr v-if="[2,3].indexOf(order.delivery)>=0">
                                <td>Дата доставки</td>
                                <td>
                                    <input
                                        id="delivery_date"
                                        @keyup=updateOrder()
                                        @change=updateOrder()
                                        type="text"
                                        class="control"
                                        v-model=order.date_of_delivery>
                                </td>
                            </tr>
                            <tr v-if="order.delivery===11">
                                <td>Пункт выдачи</td>
                                <td>
                                    <template v-if="order.city_id">
                                        <div v-if="order.delivery_pickup_point">
                                            <div class="mb-5">
                                                {{ order.delivery_pickup_point.point_id }} {{ order.delivery_pickup_point.point_address }}
                                            </div>
                                            <button @click="modal.pointSelector=true" class="btn btn-default">
                                                Изменить ПВЗ
                                            </button>
                                        </div>
                                        <div v-if="!order.delivery_pickup_point">
                                            <button @click="modal.pointSelector=true" class="btn btn-default">Выбрать ПВЗ</button>
                                        </div>
                                        <pointSelector
                                            @close="modal.pointSelector=false"
                                            :order="order"
                                            v-if="modal.pointSelector">
                                        </pointSelector>
                                    </template>
                                    
                                    <span v-if="!order.city_id" class="text-danger">
                                        Сначала нужно указать город
                                    </span>
                                
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div v-if="order" class="col-xs-6">
                <div class="card">
                    <div class="card__content pb-0">
                        <div class="card__title font-w500 font-roboto">Обработка заказа</div>
                    </div>
                    
                    <table class="table orderEditor__table">
                        <thead>
                            <tr>
                                <th class="remove-border" width="170px"></th>
                                <th class="remove-border"></th>
                            </tr>
                        </thead>
                        <tbody>
<!--                            <tr>-->
<!--                                <td>Отгрузка от</td>-->
<!--                                <td>-->
<!--                                    <select class="control" v-model="manager_id">-->
<!--                                        <option :value="null">Не выбрано</option>-->
<!--                                        <option :value="1">Комендантский</option>-->
<!--                                        <option :value="2">Ладожская</option>-->
<!--                                        <option :value="3">Озерки</option>-->
<!--                                    </select>-->
<!--                                </td>-->
<!--                            </tr>-->
                            <tr>
                                <td>Товарный чек</td>
                                <td>
                                    <a
                                        class="btn btn-default"
                                        target="_blank"
                                        :href="'/admin/orders/'+order.id+'/pdf_receipt'">
                                        <i class="fa fa-file-text-o"></i> Открыть
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Счет на оплату</td>
                                <td>
                                    <div class="btn-group">
                                        <a
                                            class="btn btn-default"
                                            target="_blank"
                                            :href="'/admin/orders/'+order.id+'/pdf_invoice'">
                                            <i class="fa fa-file-text-o"></i> Открыть
                                        </a>
                                        <button
                                            @click="$store.dispatch('sendOrderInvoice')"
                                            class="btn btn-default">
                                            <i class="fa fa-envelope-o"></i> Отправить покупателю
                                        </button>
                                    </div>
                                    <div class="orderEditor__timeshot" v-if="order.send_invoice_at">
                                        Счет отправлен {{ order.send_invoice_at }}
                                    </div>
                                </td>
                            </tr>
                            
                        </tbody>
                        
                        <tbody>
                            <tr>
                                <td colspan="2">
                                    <div class="card__title font-w500 font-roboto pt-10">Статусы</div>
                                </td>
                            </tr>
                            <tr>
                                <td>Согласовано с покупателем</td>
                                <td>
            
                                    <div class="btn-group">
                                        <button
                                            class="btn"
                                            @click="!orderApproved ? status_agreed=1 : null"
                                            :class="[status_agreed ? 'btn-success' : 'btn-default']">
                                            Да
                                        </button>
                                        <button
                                            class="btn"
                                            @click="orderApproved ? status_agreed=null : null"
                                            :class="[!status_agreed ? 'btn-danger' : 'btn-default']">
                                            Нет
                                        </button>
                                    </div>
            
                                    <div class="orderEditor__timeshot" v-if="order.status_agreed_at">
                                        Изменен {{ order.status_agreed_at }}
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!orderCancelled && status_pay!==1">
                                <td>Статус оплаты</td>
                                <td>
                                    <div class="btn-group">
                                        <button
                                            class="btn"
                                            @click="status_pay!==1 ? status_pay=1 : null"
                                            :class="[status_pay ? 'btn-success' : 'btn-default']">
                                            Оплачено
                                        </button>
                                        <button
                                            class="btn"
                                            @click="status_pay===1 ? status_pay=null : null"
                                            :class="[!status_pay ? 'btn-danger' : 'btn-default']">
                                            Не оплачено
                                        </button>
                                    </div>
                                    <div class="orderEditor__timeshot" v-if="order.status_pay_at">{{ order.status_pay_at }}</div>
                                    <div>
                                        <button
                                            v-if="!order.sms_payment_done_at && status_pay===1 && order.delivery===4"
                                            @click="$store.dispatch('sendSmsPaymentDone')"
                                            class="btn btn-default mt-10">
                                            Отправить смс «Оплата получена»
                                        </button>
                                        <div
                                            class="orderEditor__timeshot"
                                            v-if="order.sms_payment_done_at">
                                            Смс о получении оплаты отправлено {{ order.sms_payment_done_at }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!orderCancelled">
                                <td>Статус отправки</td>
                                <td>
                                    <select class="control" v-model="status">
                                        <option :value="1">Новый</option>
                                        <option :value="2">Передан в доставку</option>
                                        <option :value="3">Выполнен</option>
                                    </select>
                                    <div class="orderEditor__timeshot" v-if="order.status_at">
                                        Изменен {{ order.status_at }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <OrderCancellation></OrderCancellation>
                                </td>
                            </tr>
                        </tbody>
                        
                        <tbody v-if="!orderCancelled">
                            <tr>
                                <td colspan="2">
                                    <div class="card__title font-w500 font-roboto pt-10">Уведомления клиенту</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-lh120">Заказ подтвержден</td>
                                <td>
                                    <div>
                                        <button
                                            v-if="orderApproved"
                                            @click="$store.dispatch('sendSmsOrderApproved')"
                                            class="btn btn-default">
                                            Отправить смс
                                        </button>
                                        <div class="orderEditor__timeshot" v-if="!orderApproved">Заказ не подтвержден</div>
                                        <div class="orderEditor__timeshot" v-if="order.sms">Отправлено {{ order.sms }}</div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="orderApproved">
                                <td>Заказ изменен</td>
                                <td>
                                    <button
                                        @click="$store.dispatch('sendSmsOrderChanged')"
                                        class="btn btn-default">
                                        Отправить смс
                                    </button>
                                    <div class="orderEditor__timeshot" v-if="order.sms_changed_at">
                                        Смс отправлено {{ order.sms_changed_at }}
                                    </div>
                                </td>
                            </tr>
                            
                            <tr v-if="orderApproved">
                                <td>Передан в доставку</td>
                                <td>
                                    <button
                                        v-if="!order.transferred_to_delivery_at"
                                        @click="$store.dispatch('sendSmsOrderTransferredToDelivery')"
                                        class="btn btn-default">
                                        Отправить смс
                                    </button>
                                    <div
                                        class="orderEditor__timeshot"
                                        v-if="order.transferred_to_delivery_at">
                                        Отправлено {{ order.transferred_to_delivery_at }}
                                    </div>
                                </td>
                            </tr>
    
                            <tr v-if="orderApproved && [11].indexOf(order.delivery)>=0">
                                <td>Заказ доставлен в ПВЗ</td>
                                <td>
                                    <button
                                        @click="$store.dispatch('sendSmsOrderReadyToPickup')"
                                        class="btn btn-default">
                                        Отправить смс
                                    </button>
                                    <div
                                        class="orderEditor__timeshot"
                                        v-if="order.ready_to_pickup_at">
                                        Отправлено {{ order.ready_to_pickup_at }}
                                    </div>
                                </td>
                            </tr>
                            
                        </tbody>
                        
                        <orderSberbank></orderSberbank>
                        
                        <orderProcessRussianPost></orderProcessRussianPost>
                        
                        <orderProcessCdek></orderProcessCdek>
                    
                    </table>
                    
                    <div class="card__content pb-0">
                        <div class="card__title font-w500 font-roboto">Комментарии</div>
                    </div>
                    <div class="card__list">
                        <div class="card__listItem">
                            <textarea
                                class="form-control"
                                rows="8"
                                placeholder="Введите комментарий..."
                                v-model.lazy="comment_my"></textarea>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
    
    </div>
</template>

<script>
    module.exports = {
        name: "Order",
        props: ["order_id"],
        data() {
            return {
                modal: {
                    pointSelector: false
                }
            };
        },
        computed: {
            order() {
                return this.$store.state.order;
            },
            deliveries() {
                return this.$store.state.deliveries;
            },
            postIndex: {
                get() {
                    return this.order.post_index != -1 ? this.order.post_index : "";
                },
                set(val) {
                    this.order.post_index = val;
                    this.updateOrder();
                },
            },
            status_agreed: {
                get() {
                    return this.order.status_agreed;
                },
                set(val) {
                    this.order.status_agreed = val;
                    this.order.status_agreed_at = moment().format("YYYY-MM-DD HH:mm:ss");
                    this.updateOrder();
                },
            },
            status_pay: {
                get() {
                    return this.order.status_pay;
                },
                set(val) {
                    this.order.status_pay = val;
                    this.order.status_pay_at = moment().format("YYYY-MM-DD HH:mm:ss");
                    this.updateOrder();
                },
            },
            manager_id: {
                get() {
                    return this.order.manager_id;
                },
                set(val) {
                    this.order.manager_id = val;
                    this.updateOrder();
                },
            },
            status: {
                get() {
                    return this.order.status;
                },
                set(val, oldVal) {
                    if (val !== oldVal) {
                        this.order.status = val;
                        this.order.status_at = moment().format("YYYY-MM-DD HH:mm:ss");
                        this.updateOrder();
                    }
                },
            },
            comment_my: {
                get() {
                    return this.order.comment_my;
                },
                set(val) {
                    this.order.comment_my = val;
                    this.updateOrder();
                },
            },
            orderApproved() {
                return +this.order.status_agreed === 1;
            },
            orderCancelled() {
                return +this.order.status === 4;
            },
        },
        methods: {
            updateOrder() {
                this.$store.dispatch("updateOrder", this.order);
                this.initDatepicker();
            },
            initDatepicker() {
                setTimeout(() => {
                    let deliveryDatePicker = $("input#delivery_date").datepicker({
                        autoClose: true,
                        minDate: new Date(),
                        position: "top left",
                        onSelect: (formatedDate, date, picker) => {
                            this.order.date_of_delivery = moment(date).format("YYYY-MM-DD");
                            this.updateOrder();
                        }
                    });
                }, 1000);
            },
            changeDelivery() {
                this.$store.commit("setOrderDelivery", {});
                this.$store.dispatch("updateDeliveryProduct");
                this.updateOrder();
                this.setManagerId();
            },
            setManagerId() {
                let needSave = false;
                if (this.order.delivery === 1) {
                    this.order.manager_id = 1;
                    needSave = true;
                }
                if (this.order.delivery === 5) {
                    this.order.manager_id = 3;
                    needSave = true;
                }
                if (this.order.delivery === 13) {
                    this.order.manager_id = 2;
                    needSave = true;
                }
                if (needSave) {
                    this.$store.dispatch("updateOrder", this.order);
                }
            },
        },
        beforeMount() {
            this.$store.dispatch("getDeliveries");
            this.$store.dispatch("getOrder", this.order_id);
        },
        mounted() {
            this.initDatepicker();
        },
        components: {
            citySelector: require("./CitySelector.vue").default,
            pointSelector: require("./PointSelector.vue").default,
            orderProducts: require("./orderProducts.vue").default,
            orderProcessRussianPost: require("./orderProcessRussianPost.vue").default,
            orderProcessCdek: require("./orderProcessCdek.vue").default,
            orderSberbank: require("./orderSberbank.vue").default,
            OrderCancellation: require("./OrderCancellation.vue").default,
        }
    };
</script>

<style lang="less">
    .orderEditor {
        margin: 15px -15px;
        &__head {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: space-between;
        }
        &__website {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            background: #2ca777;
            color: #fff;
            padding: 3px 15px;
            border-radius: 30px;
        }
        &__table {
            tbody {
                tr {
                    td {
                        border: 0;
                        &:first-of-type {
                            padding-left: 15px;
                        }
                        &:last-of-type {
                            padding-right: 15px;
                        }
                    }
                }
            }
            & > tbody + tbody {
                border-top: 0;
            }
        }
        &__timeshot {
            color: #999;
            font-style: italic;
            font-size: 12px;
            padding-top: 3px;
        }
    }
</style>