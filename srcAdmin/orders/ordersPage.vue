<template>
    <div class="font-roboto">
        
        <h2>
            Заказы
            <ordersAddNew></ordersAddNew>
        </h2>
        
        <div class="adminOrders__wrapper">
            
            <div class="adminOrders__wrapperSidebar">
                
                <orderFilters></orderFilters>
                
                <ordersSearch></ordersSearch>
                
                <ordersSummary
                    v-if="$store.state.settings.orders_showSummary">
                </ordersSummary>
                
                <div v-if="!$store.getters.orders" class="card">
                    <div class="card__content">
                        <div class="loader"></div>
                    </div>
                </div>
            
            </div>
            
            <div class="adminOrders__wrapperContent">
                <div v-if="$store.getters.orders" v-for="month in $store.getters.orders" class="adminOrders__month">
                    
                    <div
                        class="adminOrders__monthName"
                        v-if="$store.state.settings.orders_showMonthNames">
                        {{ month.date|momentFormat("MMMM YYYY") }}
                    </div>
                    
                    <div class="adminOrders__day" v-for="day in month.days" :key="day.name">
                        <div
                            v-if="day.orders.length > 0 && $store.state.settings.orders_showDayNames"
                            class="adminOrders__dayName">
                            {{ day.date|momentFormat("D MMM") }}
                        </div>
                        
                        <div class="card">
                            <table class="adminOrders__table">
                                <thead>
                                    <tr>
                                        <th width="90">Номер заказа</th>
                                        <th width="50">Время заказа</th>
                                        <th width="200">Имя клиента</th>
                                        <th width="120">Способ получения</th>
                                        <!--<th width="120">Исполнитель</th>-->
                                        <th width="25" class="remove-padding-l remove-padding-r">Состав заказа</th>
                                        <th width="40">Иконка</th>
                                        <th width="40">Иконка</th>
                                        <th width="40">Иконка</th>
                                        <th class="text-right" width="90">Сумма заказа</th>
                                        <th width=""></th>
                                    </tr>
                                </thead>
                                
                                <ordersOrder
                                    v-for="order in day.orders"
                                    :order="order"
                                    :key="order.id">
                                </ordersOrder>
                            
                            </table>
                        </div>
                    </div>
                
                </div>
            
            </div>
        
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "orders-page",
        props: [],
        data() {
            return {
                countOrdersShow: 50,
            };
        },
        computed: {
            months() {
                return this.$store.getters.orders;
            },
            urlParams() {
                return this.$store.getters.urlParams;
            }
        },
        methods: {
            getMonthName(month_number) {
                return moment().month(month_number).format("MMMM");
            },
            getDayName(month, day) {
                return moment().month(month).day(day).format("D MMM");
            },
            getManager(manager_id) {
                if (!manager_id) {
                    return this.$store.state.doers[1];
                }
                return _.find(this.$store.state.doers, {id: manager_id});
            }
        },
        beforeMount() {
            this.$store.dispatch("getOrders");
            this.$store.dispatch("getDeliveries");
        },
        created() {

        },
        components: {
            orderFilters: require("./ordersFilters").default,
            ordersSearch: require("./ordersSearch").default,
            ordersAddNew: require("./ordersAddNew").default,
            ordersSummary: require("./ordersSummary").default,
            ordersOrder: require("./ordersOrder").default,
        }
    };
</script>

<style lang="scss">
    .adminOrders {
        &__wrapper {
            display: flex;
            flex-direction: column;
            &Content {
                width: 100%;
            }
            &Sidebar {
                width: 100%;
                margin-bottom: 20px;
                .card {
                    overflow: unset;
                }
                .dropdown-menu {
                    padding: 0;
                    border-radius: 0;
                    border: 0;
                    box-shadow: 0 6px 12px rgba(0, 0, 0, .4);
                    a {
                        display: block;
                        text-align: left;
                        width: 100%;
                        padding: 8px 15px;
                        color: #333;
                        border-bottom: 1px solid #e7eaec;
                        font-size: 15px;
                        cursor: pointer;
                        white-space: nowrap;
                        &:hover {
                            background: #e7eaec;
                            text-decoration: none;
                        }
                    }
                }
                button.dropdown-toggle {
                    background: #fff;
                    border-radius: 2px;
                    display: flex;
                    font-size: 15px;
                    flex-direction: row;
                    align-items: center;
                    justify-content: space-between;
                    &.center {
                        justify-content: center;
                    }
                }
            }
        }
        &__month {
            margin-bottom: 40px !important;
            &Name {
                font-size: 24px;
                padding: 15px 0 10px;
                text-transform: uppercase;
            }
        }
        &__day {
            &Name {
                background: #1f618e;
                text-transform: uppercase;
                font-size: 14px;
                padding: 2px 8px;
                display: inline-block;
                color: #fff;
            }
        }
        &__table {
            width: 100%;
            background: #e7eaec;
            thead {
                tr {
                    th {
                        font-size: 0;
                        padding-top: 0;
                        padding-bottom: 0;
                    }
                }
            }
            tr {
                background: white;
                td {
                    padding: 3px 8px;
                    border-bottom: 1px solid #e7eaec;
                    font-size: 14.4px;
                    &.compact {
                        padding: 2px 8px;
                    }
                    i.icon {
                        display: flex;
                        flex-direction: row;
                        align-items: center;
                        justify-content: center;
                        height: 27px;
                        width: 27px;
                        border-radius: 50%;
                        text-align: center;
                        line-height: 27px;
                        color: #fff;
                        margin: 0;
                        font-size: 17px;
                        cursor: default;
                        &.red {
                            background: #e65454;
                        }
                        &.green {
                            background: #32a06d;
                        }
                        &.yellow {
                            background: #fc0;
                        }
                    }
                    .onmediOrderId {
                        background: #dedede;
                        text-align: center;
                        padding: 1px 8px;
                        font-size: 13px;
                        font-weight: 500;
                        display: inline-block;
                        color: #333333;
                        margin-top: 2px;
                        border-radius: 10px;
                        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.27);
                    }
                }
                &.done {
                    color: #777;
                    background: #e7eaec;
                    td {
                        a {
                            color: #777;
                        }
                        .icon {
                            opacity: .7;
                        }
                    }
                }
                &.preorder {
                }
            }
        }
    }
</style>