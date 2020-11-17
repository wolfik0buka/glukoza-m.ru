<template>
    <div class="orderSummary" v-if="$store.getters.orders">
        <div class="card">
            <div class="card__content">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="cardKpi__label">Заказов за период</div>
                        <div class="font-s20 font-w500">{{ ordersCount }}</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="cardKpi__label">В работе</div>
                        <div class="font-s20 font-w500">{{ ordersInWorkCount }}</div>
                        <div class="text-muted">{{ ordersInWorkSum | currency }} руб.</div>
                        <div v-if="false" class="text-muted">{{ ordersInWorkPercent }}% от всех</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="cardKpi__label">Успешно завершены</div>
                        <div class="font-s20 font-w500">{{ ordersSuccessCount }}</div>
                        <div class="text-muted">{{ ordersSuccessSum | currency }} руб.</div>
                        <div v-if="false" class="text-muted">{{ ordersSuccessPercent }}% от всех</div>
                        
                    </div>
                    <div class="col-sm-3">
                        <div class="cardKpi__label">Провалены</div>
                        <div>
                            <span class="font-s20 font-w500">{{ ordersFailedCount }}</span>
                        </div>
                        <div class="text-muted">{{ ordersFailedSum | currency }} руб.</div>
                        <div v-if="false" class="text-muted">{{ ordersFailedPercent }}% от всех</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "OrderSummary",
        props: [],
        data() {
            return {};
        },
        computed: {
            orders() {
                let orders = [];
                this.$store.getters.orders.forEach(month => {
                    month.days.forEach(day => {
                        day.orders.forEach(order => {
                            orders.push(order);
                        });
                    });
                });
                return orders;
            },
            ordersCount() {
                return this.orders.length;
            },
            ordersOnePercent() {
                return this.ordersCount / 100;
            },
            ordersInWork() {
                return this.orders.filter(order => !order.is_done);
            },
            ordersInWorkCount() {
                return this.ordersInWork.length;
            },
            ordersInWorkPercent() {
                return Math.round(this.ordersInWorkCount / this.ordersOnePercent);
            },
            ordersInWorkSum() {
                return _.sumBy(this.ordersInWork, 'order_sum_raw');
            },
            ordersSuccess() {
                return this.orders.filter(order => order.is_success);
            },
            ordersSuccessCount() {
                return this.ordersSuccess.length;
            },
            ordersSuccessPercent() {
                return Math.round(this.ordersSuccessCount / this.ordersOnePercent);
            },
            ordersSuccessSum() {
                return _.sumBy(this.ordersSuccess, 'order_sum_raw');
            },
            ordersFailed() {
                return this.orders.filter(order => order.status === 4);
            },
            ordersFailedCount() {
                return this.ordersFailed.length;
            },
            ordersFailedPercent() {
                return Math.round(this.ordersFailedCount / this.ordersOnePercent);
            },
            ordersFailedSum() {
                return _.sumBy(this.ordersFailed, 'order_sum_raw');
            },
        },
        methods: {},
        components: {
        },
        beforeMount() {
        }
    };
</script>

<style lang="scss">
    .orderSummary{
        margin-top: 0;
        font-size: 15px;
    }
</style>