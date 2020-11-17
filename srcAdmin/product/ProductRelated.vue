<template>
    <div class="card">
        <div class="card__content">

            <div class="card__title">Рекомендации</div>

            <div class="relatedProducts">

                <div
                  class="relatedProducts__item"
                  v-for="related in product.related_products"
                  :key="'related_'+related.id">
                    {{ related.name }}
                    <span
                      @click="removeRelated(related)"
                      class="relatedProducts__remove">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                        </svg>
                    </span>
                </div>

                <div class="mt-15">
                    <control-select
                      :borderless=false
                      :value=false
                      :multiple=false
                      :nested=false
                      placeholder="Добавить рекомендацию"
                      @selected="addRelated"
                      @searchQueryChanged="searchProducts"
                      :options=products>
                    </control-select>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    module.exports = {
        name: 'ProductRelated',
        data() {
            return {
                products: [],
                query: ''
            };
        },
        computed: {
            product() {
                return this.$store.state.product
            }
        },
        methods: {
            searchProducts(query) {
                this.query = query;
                if (this.query.length > 3) {
                    axios.post('/admin/products/search', {query: this.query}).then(response => {
                        this.filterAndSetOptions(response.data);
                    });
                }
            },
            filterAndSetOptions(options = false) {
                if (!options) options = this.products;

                this.products = _.filter(options, p => {
                    return p.id !== this.product.id && _.findIndex(this.product.related_products, {id: p.id}) === -1;
                });
            },
            addRelated(related) {
                this.product.related_products = [...this.product.related_products, related];

                axios.post('/admin/products/related/add', {
                    product_id: this.product.id,
                    related_id: related.id
                });
                this.updateParentProduct();
                this.filterAndSetOptions();
            },
            removeRelated(related) {
                axios.post('/admin/products/related/remove', {
                    product_id: this.product.id,
                    related_id: related.id
                });
                _.remove(this.product.related_products, r => r.id===related.id)
                this.product.related_products = [...this.product.related_products]
                this.updateParentProduct();
                this.filterAndSetOptions();
            },
            updateParentProduct() {
                this.$store.commit('setProduct', this.product);
            }
        }
    };
</script>

<style lang="scss">
    .relatedProducts {
        padding-top: 10px;
        &__item {
            margin-bottom: 10px;
        }
        &__remove {
            width: 16px;
            height: 16px;
            display: inline-block;
            cursor: pointer;
            opacity: .5;
            &:hover {
                opacity: 1;
            }
            svg {
                display: block;
                margin-top: 3px;
                width: 16px;
                height: 16px;
            }
        }
    }
</style>