<template>
    <div class="mainSliderSmall">
        <div v-if="slides" class="mainSliderSmall__wrapper">

            <div class="mainSliderSmall__arrows">
                <i @click="$refs.mainswipersmall.swiper.slidePrev()" class="fa fa-angle-left"></i>
                <i @click="$refs.mainswipersmall.swiper.slideNext()" class="fa fa-angle-right"></i>
            </div>

            <swipe
                ref="mainswipersmall"
                :options="options"
                :showIndicators="false"
                :speed="300"
                class="mainSliderSmall__content">

                <swipe-item v-for="(slide, index) in slides" :key="index">
                    <div class="slideProduct">
                        <a :href="slide.link">
                            <div class="slideProduct__wrapper">
                                <div class="_title">{{ slide.name }}</div>
                                <div class="_pic">
                                    <img :src="'https://cdn.glukoza-med.ru/products/'+slide.id+'/md.jpg'">
                                </div>
                                <div class="_prices">
                                    <div>
                                        <span class="_old">{{ slide.price_old }}</span>
                                        <span class="_new">{{ slide.real_price }}<i class="fa fa-rub"></i></span>
                                    </div>
                                    <div v-if="slide.discount > 0" class="slideProduct__sale-size">
                                        Ваша скидка - {{ slide.discount }}%
                                    </div>
                                </div>
                            </div>
                        </a>

                    </div>
                </swipe-item>

            </swipe>

        </div>
    </div>
</template>


<script>
    module.exports = {
        name: "mainSliderSmall",
        props: ['products'],
        data() {
            return {
                options: {
                    slidesPerView: 1,
                    loop: true,
                    loopedSlides: false,
                    speed: 400,
                    autoplay: {
                        delay: 5000,
                    },
                    autoHeight: true,
                },
                slides: false
            }
        },
        methods: {},
        components: {},
        beforeMount() {
            this.slides = _.map(this.products, product => {
                return product
            })
        }
    }
</script>


<style lang="less">

    @import "../../less/public/variables.less";

    .mainSliderSmall{
        margin-left: -10px;
        @media @xs{
            margin-left: 0;
            margin-top: 15px;
        }
        &__wrapper{
            height: 380px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        &__arrows{
            position: absolute;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            top: 50%;
            height: 40px;
            margin-top: -20px;
            width: 100%;
            i{
                display: block;
                width: 40px;
                height: 40px;
                text-align: center;
                line-height: 40px;
                cursor: pointer;
                font-size: 32px;
                position: relative;
                z-index: 30;
                opacity: .5;
                &:hover{
                    opacity: 1;
                }
            }
        }
        &__content{
            width: 100%;
            height: 100%;
            display:flex;
        }
        .mint-swipe-indicators{
            bottom: -25px;
            .mint-swipe-indicator{
                &.is-active{
                    background: @blue;
                    opacity: 1;
                }
            }
        }
    }


    .slideProduct{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        position: relative;
        height: 100%;
        a{
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            color: @color-text-default;
            text-decoration: none;
            &:hover{
                color: @color-text-default;
                text-decoration: none;
            }
        }
        &__wrapper{
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            padding-bottom: 10px;
        }
        ._title{
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: @arial;
            font-weight: bold;
            font-size: 18px;
            height: 80px;
            flex: none;
            padding: 15px 30px 0;
            line-height: 120%;
            text-align: center;
            @media @xs {
                padding: 15px 10px 0;
            }
        }
        ._pic{
            position: relative;
            text-align: center;
            height: 190px;
            margin: 15px 0;
            img{
                height: 190px;
                max-width: 100%;
            }
        }
        ._prices{
            flex:none;
            width: 100%;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            font-family: @arial;
            ._old{
                color: #888;
                text-decoration: line-through;
                font-weight: normal;
                font-size: 80%;
            }
            ._new{
                margin-left: 5px;
                color: rgb(237, 75, 73);
            }
            i{
                font-weight: normal;
                font-size: 75%;
                margin-left: 3px;
            }
        }
        &__sale-size{
            font-weight: bold;
            line-height: 100%;
            font-size: 14px;
            margin: 5px 0 0;
            padding: 8px 15px;
            background: @blue;
            display: inline-block;
            color: #fff;
            border-radius: 15px;
        }
        ._buy{
            font-weight: bold;
            font-size: 24px;
            font-family: @arial;
        }
    }
</style>