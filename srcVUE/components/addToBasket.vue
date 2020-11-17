<script>
    module.exports = {
        name: 'addToBasket',
        props: [ 'productId', 'price', 'pic', 'name', 'link' ],
        data() {
            return {
            }
        },
        computed: {
            product() {
                return {
                    id: this.productId,
                    name: this.name,
                    price: this.price,
                    pic: this.pic,
                    link: this.link
                }
            }
        },
        methods: {
            add() {
                this.$store.dispatch('addProductToOrder', this.product);
                this.popup();
                this.goal();
            },
            popup() {
                let addedProducts = document.querySelector('.products_row.products');
                addeded = '';
                if (addedProducts) {
                    addeded = '<div class="container-fluid white-bg"><div class="container"><div class="row"><div class="bottom-40"><div class="h2 centerText">Сопутствующие товары</div></div></div></div><div class="products products_row">';
                    addeded += addedProducts.innerHTML;
                    addeded += '</div></div>'
                }
                $.magnificPopup.open({
                    items: {
                        src: `
                        <div class="white-popup">
                            <div class="white-popup__title">Товар добавлен в корзину</div>
                            <div class="white-popup__buttons">
                                <button class="btn btn-default" onclick="$.magnificPopup.close()">Продолжить покупки</button>
                                <a class="btn btn-primary" href="/checkout">Оформить заказ</a>
                            </div>
                        </div>` + addeded,
                        type: 'inline'
                    }
                });
            },
            goal() {
                _ym.goal('addtocart')
            }
        }
    }
</script>
