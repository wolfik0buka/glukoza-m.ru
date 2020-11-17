<script>
module.exports = {
    name: 'bestOffers',
    data(){
        return {
            isShowAll: false,
            products: false
        }
    },
    computed: {
        productsLength() {
            return this.products.length
        },
        showProductsCount() {
            let count = this.productsLength

            if (count >= 6) {
                return 6
            } else if (count < 6 && count > 3 || count === 3) {
                return 3
            } else if (count < 3) {
                return count
            }
        },
        hiddenProductsCount() {
            let hiddenProducts = this.productsLength - this.showProductsCount

            return hiddenProducts > 0 ? hiddenProducts : 0
        }
    },
    mounted() {
        this.products = this.$el.querySelectorAll('.bestOffers__item');
        this.hide();
    },
    methods: {
        filter() {
            _.each(this.products, (product, i) => {
                if (i >= this.showProductsCount) {
                    this.isShowAll ? $(product).show() : $(product).hide()
                }
            })
        },
        show() {
            this.isShowAll = true;
            this.filter();
        },
        hide() {
            this.isShowAll = false;
            this.filter();
        }
    }
}
</script>

<style lang="less">
    span.more_link {
        cursor: pointer;
        font-size: 18px;
        font-weight: 500;
        color: #3195d0;
        border-bottom: 1px dashed #83c0e4;
        padding-bottom: 0;
        display: inline-block;
        vertical-align: top;
    }
</style>