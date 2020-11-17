<template>
    <div>
        
        <div v-if="isOrderCancelled" class="alert alert-danger mb-10">
            <div class="font-w600 font-s18">
                Заказ снят
            </div>
            <div class="color-text-default" v-if="selectedReason">
                <div class="font-w600">
                    {{ selectedReason.name }}
                </div>
                <label class="pt-5" v-if="selectedReason.message">
                    {{ selectedReason.message }}
                </label>
                <div v-if="order.cancel_details">
                    {{ order.cancel_details }}
                </div>
            </div>
        </div>
        
        
        
        <button
            v-if="!isOrderCancelled"
            @click="modalExpanded=true"
            class="btn btn-default">
            Снять заказ
        </button>
    
        <button
            v-if="isOrderCancelled && !order.cancel_reason_id"
            @click="modalExpanded=true"
            class="btn btn-default">
            Указать причину
        </button>
        
        <div v-if="modalExpanded" class="orderCancellation__modal">
            <div class="orderCancellation__modal__content">
                <div class="card">
                    <div class="card__content">
                        <button @click="modalExpanded=false" class="btn btn-default">Закрыть форму</button>
                    </div>
                    
                    <div v-if="!selectedReason" class="card__list font-roboto">
                        <div class="card__content card__title font-s22">
                            Выберите причину снятия заказа
                        </div>
                        <div
                            v-for="reason in orderCancelReasons"
                            @click="selectedReason = reason"
                            :key="'reason_'+reason.id"
                            class="card__listItem card__listItem-with-hover font-s16">
                            {{ reason.name }}
                        </div>
                    </div>
                    
                    <div v-if="selectedReason" class="card__content">
                        <div class="card__title font-s22 font-roboto mb-10">
                            {{ selectedReason.name }}
                        </div>
                        <button
                            @click="selectedReason=false"
                            class="btn btn-default mb-10">
                            Выбрать другой статус
                        </button>
                        
                        <template v-if="selectedReason.message">
                            <div class="orderCancellation__question">
                                {{ selectedReason.message }}
                            </div>
                            <textarea
                                v-model="cancelDetails"
                                class="form-control mb-10"
                                rows="6">
                            </textarea>
                        </template>
                        
                        <button
                            v-if="isCancellationAvailable"
                            @click="runOrderCancellation()"
                            class="btn btn-primary btn-lg btn-block orderCancellation__button">
                            Снять заказ
                        </button>
                    </div>
                
                </div>
            </div>
        </div>
    
    </div>
</template>

<script>
    module.exports = {
        name: "OrderCancellation",
        props: [],
        data() {
            return {
                modalExpanded: false,
                selectedReason: false,
                cancelDetails: '',
            };
        },
        computed: {
            order() {
                return this.$store.state.order;
            },
            orderCancelReasons() {
                return this.$store.state.orderCancelReasons;
            },
            isOrderCancelled() {
                return this.order.status === 4;
            },
            isCancelDetailsAdded() {
                return this.cancelDetails.length > 1;
            },
            isCancellationAvailable() {
                if (this.selectedReason) {
                    return this.selectedReason.answer_required
                        ? this.isCancelDetailsAdded
                        : true;
                }
                return false;
            }
        },
        methods: {
            runOrderCancellation() {
                this.order.cancel_reason_id = this.selectedReason.id;
                this.order.cancel_details = this.cancelDetails;
                this.order.status = 4;
                this.$store.dispatch("updateOrder", this.order);
                this.modalExpanded = false;
            },
        },
        mounted() {
            this.$store.dispatch("getOrderCancelReasons").then(cancelReasons => {
                if (this.order.cancel_reason_id) {
                    this.selectedReason = _.find(cancelReasons, reason => {
                        return reason.id === this.order.cancel_reason_id;
                    });
                }
            })
        }
    };
</script>

<style lang="less">
    .orderCancellation {
        &__modal {
            position: fixed;
            right: 0;
            bottom: 0;
            top: 0;
            width: 500px;
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            
            &__content {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                width: 100%;
                height: 100%;
                background: #fff;
                
                .card {
                    width: 100%;
                    height: 100%;
                    flex: none;
                }
            }
        }
        &__button{
            height: 50px !important;
        }
        
        &__question {
            font-size: 16px;
            border: 1px solid #eee;
            border-left-color: #0083e4;
            padding: 20px;
            margin: 20px 0;
            border-left-width: 5px;
            border-radius: 3px;
            background: #e7f2fb;
        }
    }
</style>