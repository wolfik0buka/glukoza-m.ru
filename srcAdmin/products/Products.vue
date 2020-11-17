<template>
    <div class="mt-15">
        <h2>Номенклатура</h2>

        <div class="mb-15">
            <a
              :href=linkToAddNewProduct
              class="btn btn-primary">
                Новый товар
            </a>
        </div>

        <div v-if="productsCount > 0">

            <div class="row">
                <div class="col-sm-3">
                    <div class="card card__content cardKpi">
                        <div class="cardKpi__label">Товаров</div>
                        <div class="cardKpi__number">{{ productsCount }}</div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card card__content cardKpi">
                        <div class="cardKpi__label">В наличии</div>
                        <div class="cardKpi__number">{{ countProductsAvailable }}</div>
                    </div>
                </div>
            </div>

            <div class="card mt-15">

                <div class="card__content">
                    <input type="text" class="form-control" v-model="searchQuery" placeholder="Поиск по названию, артикулу">
                    <div class="pt-15">
                        <span v-if="searchQuery"> Найдено {{ productsFilteredCount }} из {{ productsCount }}</span>
                    </div>
                </div>

                <table class="table table-condensed productsTable">
                    <thead>
                        <tr>
                            <th width="40px">ID</th>
                            <th>Товар</th>
                            <th class="text-center" width="80px">Наличие</th>
                            <th class="text-center" width="70px">Sale</th>
                            <th class="text-center" width="70px">Hit</th>
                            <th class="text-center" width="90px">Под заказ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in productsVisible">
                            <td>{{ product.id }}</td>
                            <td>
                                <a :href=product.linkEdit >{{ product.name }}</a>
                            </td>
                            <td class="font-s13">
                                <span class="text-success" v-if="product.is_available">Доступен</span>
                                <span class="text-danger" v-if="!product.is_available">Отсутствует</span>
                            </td>
                            <td class="text-center">
                                <input :value="product.hit" type="checkbox" :id="'sale'+product.id" onchange="saleSwitch(product.id,this.value)">
                            </td>
                            <td class="text-center">
                                <input :value="product.sale" type="checkbox" :id="'hit'+product.id" onchange="hitSwitch(product.id,this.value)">
                            </td>
                            <td class="text-center">
                                <input :value="product.podzakaz" type="checkbox" :id="'podzakaz'+product.id" onchange="podzakazSwitch(product.id,this.value)">
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div v-if="visibleProductsCount < productsFilteredCount" class="text-center">
                <button @click="visibleProductsCount = visibleProductsCount + 30" class="btn btn-default">Показать еще 30</button>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: "Products",
        props: [],

        data() {
            return {
                visibleProductsCount: 50,
                linkToAddNewProduct: '/admin_new/index.php?page=nom&id=-1'
            };
        },
        computed: {
            products() {
                return _.map(this.$store.state.products, product => {
                    product.linkEdit = `/admin_new/index.php?page=nom&id=${product.id}`;
                    return product;
                });
            },
            productsCount() {
                return this.products.length;
            },
            searchQuery: {
                get() {
                    return this.$store.state.productsFilters.query;
                },
                set(val) {
                    this.$store.commit("subset", ["productsFilters", "query", val.toLowerCase()]);
                    this.$store.dispatch("saveProductsFilters");
                }
            },
            productsFiltered() {
                let products = _.filter(this.products, product => {

                    let pass = product.usluga === 0;

                    if (pass && this.searchQuery) {
                        let indexString = [
                            product.name,
                            product.id,
                            product.art,
                            product.description,
                            product.desc_full
                        ].join(" ");

                        pass = indexString.toLowerCase().indexOf(this.searchQuery) >= 0;
                    }

                    return pass;
                });

                return _.sortBy(products, "id").reverse();
            },
            productsFilteredCount() {
                return this.productsFiltered.length;
            },
            productsVisible() {
                return this.productsFiltered.splice(0, this.visibleProductsCount);
            },
            countProductsAvailable() {
                return _.filter(this.products, {"is_available": true}).length;
            }
        },
        methods: {
        
        },
        beforeMount() {
            this.$store.dispatch("getProducts");
        }
    };
</script>

<style lang="scss">
    .productsTable {
        thead {
            tr {
                th:first-of-type {
                    padding-left: 15px;
                }
            }
        }
        tbody {
            tr {
                td:first-of-type {
                    padding-left: 15px;
                }
            }
        }
    }

    .cardKpi {
        margin-bottom: 20px;
        &__label {
            color: #757575;
            font-size: 14px;
            padding-bottom: 5px;
        }
        &__number {
            font-family: "Roboto", Arial, sans-serif;
            font-size: 26px;
            font-weight: 500;
        }
    }
</style>