<template>
    <div class="font-roboto mt-30 mb-15">

        <input
          type="text"
          class="form-control font-s16 font-roboto"
          v-model="searchQuery"
          placeholder="Поиск товара..">


        <div v-if="search.loading" class="loader"></div>

        <div class="card__list mt-15">
            <div v-if="search.products.length > 0">
                <div class="card__listItem" v-for="product in search.products">
                    <div>
                        <a
                            class="font-roboto"
                            @click="addProduct(product)"
                            href="">
                            {{ product.name }}
                        </a>
                        <div class="font-s13">
                            <span v-if="product.is_available" class="text-success">В наличии</span>
                            <span v-if="!product.is_available" class="text-danger">Нет в наличии</span>
                            <span v-if="product.is_available" class="ml-15">{{ product.real_price }} руб.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: "orderAddProduct",
        props: [],
        data() {
            return {
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
            searchProducts: _.debounce((query, self) => {
                self.search.loading = true
                axios.post('/admin/collections/search', { query: query }).then(response => {
                    self.search.products = response.data
                    self.search.loading = false
                })
            }, 500),
            addProduct(product) {
                this.$emit('add', { product: product })
            }
        },
        mounted() {
            this.$el.querySelector('input.form-control').focus()
        }
    }
</script>

<style lang="scss" scoped>
    .card__list{
        &Item{
            font-size: 15px;
            padding: 8px 0;
            a{
                color: #333;
                line-height: 130%;
            }
        }
    }
</style>