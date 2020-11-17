<template>
    <div class="card basket__panel">
        <div class="list-group">
            <basketProductItem
                v-if="order.products"
                v-for="product in order.products"
                :product="product"
                :key="product.id">
            </basketProductItem>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: 'basketProducts',
        data() {
            return {
                msg:'hello vue'
            }
        },
        computed: {
            order() {
                return this.$store.getters.order
            }
        },
        components: {
            basketProductItem: require('./basketProductItem.vue').default,
        }
    }
</script>

<style lang="less">

    @import "../../less/public/variables.less";

    .basket__panel{
        .list-group-item{
            border:0;
        }
    }
    .basket__product{

        &__remove{
            display: block;
            position: absolute;
            z-index: 3;
            top: 15px;
            right: 15px;
            margin: 0;
            background: #e3e3e3;
            color: #777;
            font-size: 13px;
            line-height: 25px;
            text-align: center;
            border-radius: 50%;
            height: 25px;
            width: 25px;
            cursor: pointer;
            overflow: hidden;
            transition: .1s all ease;
            &:hover{
                background: darken(#e3e3e3, 10%);
                color: #333;
            }
        }
        &__pic{
            height: 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            @media @xs{
                align-items: flex-start;
            }
            img{
                max-width: 100%;
                max-height:100%;
            }
        }
        &__amount{
            border: 2px solid #e3e3e3;
            display: inline-block;
            margin-top: 10px;
            border-radius: 3px;
            font-size: 14px;
            input{
                border: 0;
                text-align: center;
                width: 60px;
                height: 36px;
                font-weight: 500;
                &, &:focus, &:active{
                    box-shadow: none;
                    outline: none;
                }
            }
            i{
                padding:0 10px;
                cursor: pointer;
                color: #c1c1c1;
                &:hover{
                    color: @color-text-default;
                }
            }
        }
    }

</style>