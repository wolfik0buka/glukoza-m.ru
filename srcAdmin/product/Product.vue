<template>
    <div class="font-roboto">
        
        <div class="mb-10 mt-10">
            <a :href="'/admin_new/index.php?page=nom'" class="btn btn-default">К списку товаров</a>
            <a
                :href="'/admin/products/'+product_id+'/clone'"
                class="btn btn-default">
                Дублировать товар
            </a>
        </div>

        <div v-if="product_id < 1">
            <div class="card">
                <div class="card__content">
                    <div class="card__title">Новый товар</div>
                    <div>
                        <label>Название</label>
                        <input type="text" class="form-control font-s15" v-model="newProduct.name">
                    </div>
                    <div class="pt-15">
                        <button @click="addProduct()" class="btn btn-primary">
                            Добавить товар
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div v-if="product_id > 0" class="product__wrapper">
            <div class="product__editor">
                <div class="card">
                    <div v-if="product" class="card__content">
                        <div class="card__title">Редактирование</div>
                        <div>
                            <label>Название</label>
                            <input type="text" class="form-control font-s15" v-model="product.name">
                        </div>
                        <div v-if="cats">
                            <control-select
                              label="Категории"
                              :borderless=false
                              :value=selectedCatIds
                              :multiple=true
                              :nested=true
                              placeholder="Привязать раздел"
                              @selected="linkCats"
                              :options=cats >
                            </control-select>
                        </div>
                        <div>
                            <label>Цена</label>
                            <input
                                type="number"
                                class="form-control font-s15"
                                v-model.number="price">
                        </div>
                            <label>По предзаказу</label>
                            <div>
                                <div class="btn-group">
                                    <button
                                      class="btn"
                                      @click="product.podzakaz===0 ? product.podzakaz=1 : null"
                                      :class="[product.podzakaz===1 ? 'btn-primary' : 'btn-default']">
                                        Да
                                    </button>
                                    <button
                                      class="btn"
                                      @click="product.podzakaz===1 ? product.podzakaz=0 : null"
                                      :class="[product.podzakaz===0 ? 'btn-primary' : 'btn-default']">
                                        Нет
                                    </button>
                                </div>
                            </div>
                        <div>
                            <label>Старая цена</label>
                            <input
                                type="number"
                                class="form-control font-s15"
                                v-model.number="oldPrice">
                        </div>
                        <div>
                            <label>Краткое описание</label>
                            <wysiwyg
                              placeholder="Введите текст.."
                              @change="updateDescription"
                              :html="product.description"/>
                        </div>
                        <div>
                            <label>Полное описание</label>
                            <wysiwyg
                              placeholder="Введите текст.."
                              @change="updateDescFull"
                              :html="product.desc_full" />
                        </div>
                        <div class="pt-15">
                            <button
                                @click="saveProduct()"
                                class="btn btn-lg btn-primary">
                                Сохранить
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="product__photos">

                <div v-if="product" class="card mb-15">
                    <div class="card__content">
                        <div class="card__title">Сводка</div>
                    </div>
                    <div class="card__list font-s15">
                        <div class="card__listItem">
                            <span class="text-muted">Статус</span>
                            <div class="font-w500">
                                <span class="text-success" v-if="product.is_available">Доступен</span>
                                <span class="text-danger" v-if="!product.is_available">Не доступен</span>
                            </div>
                        </div>
                        <div class="card__listItem">
                            <span class="text-muted">Артикул</span>
                            <span>{{ product.articul }}</span>
                        </div>
                        <div v-if="product.canonical" class="card__listItem">
                            <a target="_blank" :href="'https://glukoza-med.ru'+product.canonical">Открыть в новой вкладке</a>
                        </div>
                    </div>
                </div>


                <div v-if="product" class="card mb-15">
                    <div class="card__content">
                        <div class="card__title">Фото товара</div>
                        <div class="pt-15">
                            <single-upload
                              :remove="false"
                              name="Загрузить"
                              :path="'/admin/products/'+product.id+'/upload'"
                              :file="'https://cdn.glukoza-med.ru/products/'+product.id+'/md.jpg'">
                            </single-upload>
                        </div>
                    </div>
                </div>


                <div v-if="product">
                    <ProductRelated
                      :product="product" @updateProduct="">
                    </ProductRelated>
                </div>

            </div>






        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "Product",
        props: [
            'product_id'
        ],
        data() {
            return {
                newProduct: {
                    name: ''
                }
            }
        },
        
        computed: {
            product() {
                return this.$store.state.product
            },
            cats() {
                return this.$store.state.cats
            },
            selectedCatIds() {
                if (this.product){
                    if (this.product.links_to_cats.length > 0) {
                        return this.product.links_to_cats.map(link => { return link.id_cat })
                    }
                }

                return []
            },
            price: {
                get() {
                    return this.product.price;
                },
                set(val) {
                    this.product.price = parseFloat(val);
                },
            },
            oldPrice: {
                get() {
                    return this.product.price_old;
                },
                set(val) {
                    this.product.price_old = parseFloat(val);
                },
            },
        },
        methods: {
            updateDescription(payload) {
                this.product.description = payload;
            },
            updateDescFull(payload) {
                this.product.desc_full = payload;
            },
            linkCats(cats) {
                let payload = _.map(cats, cat => {
                    return {
                        id_tovar: this.product.id,
                        id_cat: cat.id
                    }
                })
                this.$store.dispatch('updateLinkProductCats', payload);
            },
            saveProduct() {
                this.$store.dispatch('updateProduct', this.product).then(() => {
                    Mess.show("Товар успешно сохранен");
                })
            },
            addProduct() {
                this.$store.dispatch('addProduct', this.newProduct);
            }
        },
        beforeMount() {
            if (this.product_id > 0) {
                this.$store.dispatch('getProduct', this.product_id);
                this.$store.dispatch('getCats');
            }
        },
        components: {
            ProductRelated: require('./ProductRelated.vue').default
        }
    }
</script>

<style lang="scss">
    .product{
        &__wrapper{
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: nowrap;
        }
        &__editor{
            width: 100%;
        }
        &__photos{
            flex:none;
            width: 300px;
            margin-left: 30px;
        }
    }
</style>