<template>
    <div class="header_cart" :class="{ active : isActive, static : position }">
        <div class="cart_wrap">
            <a class="header_cart__icon" href="/checkout">
                <i class="fa fa-shopping-basket"></i>
                <div v-if="isActive" class="header_cart__counter">
                    {{ orderProductsCount }}
                </div>
            </a>
            <div class="header_cart__content">
                <span class="title hidden-xs">Корзина</span>
                <span class="state hidden-xs">
                    <a href="/checkout" v-if="isActive">{{ orderFullSum }} руб.</a>
                    <span class="text-muted" v-else="">В корзине пусто</span>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "headerCart",
        data() {
            return {
                isHover: false
            };
        },
        props: {
            position: {
                default: false
            }
        },
        computed: {
            orderFullSum() {
                return this.$store.getters.orderFullSum;
            },
            orderProductsCount() {
                return this.$store.getters.orderProductsCount;
            },
            isActive() {
                return this.orderFullSum > 0;
            }
        }
    };
</script>

<style lang="less">
    
    @import "../less/public/variables";
    
    .header_cart {
        height: 56px;
        
        .cart_wrap {
            margin: -10px -15px;
            padding: 10px 15px 0;
            background: white;
            display: flex;
            a {
                text-decoration: none;
                
                i {
                    display: block;
                    float: left;
                    margin: 0 10px 0 0;
                    font-size: 22px;
                    color: #fff;
                    background: @blue;
                    height: 46px;
                    width: 46px;
                    line-height: 46px;
                    text-align: center;
                    border-radius: 50%;
                    transition: .2s all ease;
                }
            }
            
            .state {
                display: block;
                font-size: 14px;
            }
            
            .title {
                font-weight: 500;
                font-size: 16px;
                color: #444;
                padding-top: 2px;
            }
            
            .cart_full {
                width: 100%;
                height: 1px;
                overflow: hidden;
                opacity: 0;
                padding-top: 0;
                font-size: 0;
            }
        }
        &__counter{
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 10px;
            color: #fff;
            top: -2px;
            right: -3px;
            background: @red;
            line-height: 100%;
            font-size: 13px;
            font-weight: 500;
            padding-left: 2px;
            padding-bottom: 1px;
        }
        &__icon {
            display: block;
            position: relative;
            height: 46px;
            width: 46px;
            flex: none;
            margin-right: 10px;
        }
        &.active {}
        &.static {
            min-width: 200px;
        }
    }
</style>
