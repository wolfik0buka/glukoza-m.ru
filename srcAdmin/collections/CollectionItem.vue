<template>
    <div  class="card mt-5">
        <div class="card__content">
            <div class="pull-right">
                <button @click="isOpened=!isOpened" class="btn btn-secondary">Открыть</button>
            </div>
            <div class="font-s16">{{ collection.name }}</div>
            <div class="text-muted mt-5">Товаров: {{ collection.products.length }}</div>
        </div>
        <div v-if="isOpened" class="border-t">
            <div class="col-sm-6 border-r remove-padding">
                <div class="card__title ml-15 mb-15 mt-15">Товары в коллекции</div>
                <div class="card__list">
                    <div v-if="collection.products.length < 1">Не привязано ни одного товара</div>
                    <div v-if="collection.products.length > 0">
                        <div class="card__listItem" v-for="product in collection.products">
                            {{ product.name }}
                            <button @click="removeProduct(product)" class="btn ml-15 btn-sm btn-secondary">-</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group mt-15">
                    <label>Добавить товар</label>
                    <input type="text" class="form-control" v-model="searchQuery" placeholder="Поиск товара..">
                </div>
                <div class="row">
                    <div v-if="search.loading" class="loader"></div>
                    <div class="card__list">
                        <div v-if="search.products.length > 0">
                            <div
                              class="card__listItem"
                              v-for="product in search.products">
                                {{ product.name }}
                                <button @click="addProduct(product)" class="btn ml-15 btn-sm btn-secondary">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "CollectionItem",
        props: ['collection_raw'],
        data() {
            return {
                collection: false,
                isOpened: false,
                search: {
                    query: '',
                    loading: false,
                    products: []
                },

            }
        },
        computed: {
            searchQuery: {
                get() {
                    return this.search.query
                },
                set(query, oldQuery) {
                    this.search.query = query
                    this.search.products = []
                    if (query.length>=2 && query!==oldQuery) {
                        this.searchProducts(query, this)
                    }
                },
            }
        },
        methods: {
            addProduct(product) {
                let payload = {
                    collection_id: this.collection.id,
                    product_id: product.id,
                }
                axios.post('/admin/collections/add', payload).then(response => {
                    this.collection = response.data
                })
            },
            removeProduct(product) {
                let payload = _.cloneDeep(product.pivot)
                this.collection.products.splice(this.collection.products.indexOf(product), 1)
                axios.post('/admin/collections/remove', payload)
            },
            searchProducts: _.debounce((query, self) => {
                self.search.loading = true
                axios.post('/admin/collections/search', { query: query }).then(response => {
                    self.search.products = response.data
                    self.search.loading = false
                })
            }, 500)
        },
        beforeMount() {
            this.collection = this.collection_raw
        }
    }
</script>

<style scoped>

</style>