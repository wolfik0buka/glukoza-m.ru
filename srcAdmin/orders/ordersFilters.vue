<template>
    <div class="card">
        <div class="adminOrders__filters">
            
            <div class="adminOrders__filters__left">
                
                <div v-show="filterDates" class="adminOrders__filter" style="width:195px;">
                    <div class="adminOrders__filterTitle">Дата заказа</div>
                    <input
                        id="js__filterDates"
                        type="text"
                        class="control btn btn-default"
                        v-model=filterDates>
                </div>
                
                <div class="adminOrders__filter" style="width:130px;">
                    <div class="adminOrders__filterTitle">Завершенные</div>
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown">
                            {{ filterDone > 0 ? 'Показать' : 'Скрыть' }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a @click="filterDone = 0">Скрыть</a>
                            <a @click="filterDone = 1">Показать</a>
                        </div>
                    </div>
                </div>
                <div v-if="$store.state.deliveries" class="adminOrders__filter" style="width:160px;">
                    <div class="adminOrders__filterTitle">Способ получения</div>
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown">
                            {{ filterDelivery ? filterDelivery.name : 'Любой' }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a @click="filterDelivery = false">Любой</a>
                            <a
                                @click="filterDelivery = delivery.id"
                                v-for="delivery in $store.state.deliveries">{{ delivery.name }}
                            </a>
                        </div>
                    </div>
                </div>
                <div v-if="$store.state.doers" class="adminOrders__filter" style="width:160px;">
                    <div class="adminOrders__filterTitle">Исполнитель</div>
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown">
                            {{ filterDoer ? filterDoer.name : 'Error!' }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a
                                v-for="doer in $store.state.doers"
                                :key="doer.id"
                                @click="filterDoer = doer.id">
                                {{ doer.name }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="adminOrders__filter" style="width:115px;">
                    <div class="adminOrders__filterTitle">Тип лида</div>
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown">
                            {{ filterLeadType ? filterLeadType.name : 'Error!' }} <span class="caret"></span>
                        </button>
                        <div class="dropdown-menu">
                            <a
                                v-for="leadType in $store.state.leadTypes"
                                :key="leadType.id"
                                @click="filterLeadType = leadType.id">
                                {{ leadType.name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="adminOrders__filters__right">
                <div
                    class="adminOrders__filter"
                    v-if="$store.state.filters.delivery || $store.state.filters.doer || $store.state.filters.leadType || !$store.state.filters.showDoneOrder"
                    style="width:140px;">
                    <div class="adminOrders__filterTitle"></div>
                    <div class="btn-group btn-block">
                        <button
                            type="button"
                            @click="resetFilters()"
                            class="btn btn-default btn-block dropdown-toggle center">Сбросить все
                        </button>
                    </div>
                </div>
                <div class="adminOrders__filter icon">
                    <div class="adminOrders__filterTitle"></div>
                    <div class="btn-group btn-block">
                        <button
                            type="button"
                            class="btn btn-default btn-block dropdown-toggle"
                            data-toggle="dropdown">
                            <svg fill="#333333" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 0h24v24H0z" fill="none"/>
                                <path
                                    d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.59 1.69-.98l2.49 1c.23.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a
                                @click="saveSettings('orders_showDayNames', false)"
                                v-if="$store.state.settings.orders_showDayNames">
                                Скрыть даты (дни)
                            </a>
                            <a
                                @click="saveSettings('orders_showDayNames', true)"
                                v-if="!$store.state.settings.orders_showDayNames">
                                Показать даты (дни)
                            </a>
                            <a
                                @click="saveSettings('orders_showMonthNames', false)"
                                v-if="$store.state.settings.orders_showMonthNames">
                                Скрыть даты (месяцы)
                            </a>
                            <a
                                @click="saveSettings('orders_showMonthNames', true)"
                                v-if="!$store.state.settings.orders_showMonthNames">
                                Показать даты (месяцы)
                            </a>
                            <a
                                @click="saveSettings('orders_showSummary', false)"
                                v-if="$store.state.settings.orders_showSummary">
                                Скрыть статистику
                            </a>
                            <a
                                @click="saveSettings('orders_showSummary', true)"
                                v-if="!$store.state.settings.orders_showSummary">
                                Показать статистику
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "orders-filters",
        props: [],
        data() {
            return {};
        },
        computed: {
            filterDone: {
                get() {
                    return this.$store.state.filters.showDoneOrder;
                },
                set(val) {
                    this.$store.commit("subset", ["filters", "showDoneOrder", val]);
                    this.$store.dispatch("saveFilters");
                }
            },
            filterDoer: {
                get() {
                    return _.find(this.$store.state.doers, {id: this.$store.state.filters.doer});
                },
                set(doer_id) {
                    this.$store.commit("subset", ["filters", "doer", doer_id]);
                    this.$store.dispatch("saveFilters");
                }
            },
            filterLeadType: {
                get() {
                    return _.find(this.$store.state.leadTypes, {id: this.$store.state.filters.leadType});
                },
                set(leadTypeId) {
                    this.$store.commit("subset", ["filters", "leadType", leadTypeId]);
                    this.$store.dispatch("saveFilters");
                }
            },
            filterDelivery: {
                get() {
                    let delivery = _.find(this.$store.state.deliveries, delivery => {
                        return delivery.id === this.$store.state.filters.delivery;
                    });
                    return delivery ? delivery : false;
                },
                set(delivery_id) {
                    this.$store.commit("subset", ["filters", "delivery", delivery_id]);
                    this.$store.dispatch("saveFilters");
                }
            },
            filterDates: {
                get() {
                    if (this.$store.state.filters.dateFrom) {
                        let dates = [];
                        dates.push(this.$store.state.filters.dateFrom);
                        dates.push(this.$store.state.filters.dateTo);

                        return dates.join(", ");
                    }
                    return false;
                },
                set(formattedDates) {
                    let dateFrom = false;
                    let dateTo = false;
                    if (formattedDates) {
                        dateFrom = formattedDates.split(", ")[0];
                        dateTo = formattedDates.split(", ")[1];
                    }
                    this.$store.dispatch("setOrderFilterDates", {dateFrom:dateFrom,dateTo:dateTo});
                    this.$store.dispatch('getOrders');
                }
            },
        },
        methods: {
            resetFilters() {
                this.$store.commit("subset", ["filters", "delivery", false]);
                this.$store.commit("subset", ["filters", "doer", false]);
                this.$store.commit("subset", ["filters", "showDoneOrder", 1]);
                this.$store.commit("subset", ["filters", "leadType", false]);
                this.$store.commit("subset", ["filters", "query", '']);
                this.$store.dispatch("saveFilters");
            },
            saveSettings(name, value) {
                this.$store.commit("subset", ["settings", name, value]);
                this.$store.dispatch("saveSettings");
            },
            makeDatepicker() {
                setTimeout(() => {
                    $("input#js__filterDates").datepicker({
                        autoClose: false,
                        maxDate: moment().add(1, "days").toDate(),
                        multipleDates: 2,
                        multipleDatesSeparator: ", ",
                        showOtherMonths: true,
                        dateFormat: 'yyyy-mm-dd',
                        view:"months",
                        clearButton: true,
                        clear: 'Очистить',
                        position: "bottom left",
                        onSelect: (formatedDates, dates, instance) => {
                            if (formatedDates==="") {
                                this.filterDates = false;
                                instance.hide();
                            }
                            if (formatedDates.split(", ").length === 2) {
                                this.filterDates = formatedDates;
                                instance.hide();
                            }
                        },
                        onHide: (instance) => {}
                    });
                }, 1000);
            },
        },
        mounted() {
            this.makeDatepicker();
        }
    };
</script>

<style lang="scss">
    .adminOrders {
        &__filters {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            &__left, &__right {
                display: flex;
                flex-direction: row;
                justify-content: flex-start;
            }
        }
        &__filter {
            width: 170px;
            padding: 5px 10px 8px;
            &.icon {
                width: auto;
            }
            &Title {
                font-size: 13px;
                font-weight: 400;
                padding: 5px 0 4px 0;
                color: #757575;
                height: 24px;
            }
            & .btn-default{
                overflow: hidden;
            }
        }
    }
    
    #js__filterDates{
        border: 1px solid #ccc;
        font-size: 14.5px;
        text-align: left;
    }
</style>