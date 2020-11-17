<template>
    <div class="adminOrders__search">
        <div class="card">
            
            <input
                type="text"
                v-model="query"
                class="form-control adminOrders__searchInput"
                placeholder="Поиск по номеру заказа, клиенту, телефону, email, комментарию">
            
            <div class="adminOrders__searchClear" v-if="query" @click="clear()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    <path d="M0 0h24v24H0z" fill="none"/>
                </svg>
            </div>
        
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "OrdersSearch",
        props: [],
        data() {
            return {};
        },
        computed: {
            query: {
                get() {
                    return this.$store.state.filters.query;
                },
                set(val) {
                    this.$store.commit("subset", ["filters", "query", val.toLowerCase()]);
                },
            }
        },
        methods: {
            clear() {
                this.$store.commit("subset", ["filters", "query", ""]);
                this.$store.dispatch("saveFilters");
            }
        },
        beforeMount() {}
    };
</script>

<style lang="scss">
    .adminOrders__search {
        padding: 0;
        position: relative;
        
        &Input {
            border: 0 !important;
            font-size: 15px !important;
            background: #eceff1;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12);
            padding: 0 10px !important;
            height: 40px;
            
            &, &:active, &:focus, &:hover {
                outline: none !important;
                box-shadow: none;
            }
        }
        
        &Clear {
            height: 40px;
            width: 40px;
            position: absolute;
            right: 10px;
            top: 0;
            opacity: .5;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            
            &:hover {
                opacity: 1;
            }
        }
    }
</style>