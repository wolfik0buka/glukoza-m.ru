<template>
    <div class="mainSlider">
        <div class="mainSlider__wrapper">

            <swipe ref="bigswiper" class="mainSlider__content" :options="options" :key="'bigswiper'">
                <swipe-item
                    v-for="slide in slides"
                    :key="'big-slider-slide_'+slide.id" >
                    <template v-if="slide.href">
                        <a
                            class="mainSlider__slidePic" 
                            :href="slide.href">
                            <img
                                :src="slide.pic"
                                :alt="slide.name">
                        </a>
                     </template>
                    <template v-else>
                        <div class="mainSlider__slidePic">
                            <img
                                :src="slide.pic"
                                :alt="slide.name">
                        </div>
                    </template>
                </swipe-item>
                <div class="swiper-pagination mainSlider__indicators" slot="pagination"></div>
            </swipe>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "mainSlider",
        data() {
            return {
                swiperLoaded: false,
                slides: [
                    {
                        id: 0,
                        name: "Доставка в день заказа",
                        pic: "/img/main_page/slider/1.jpg",
                    },
                    {
                        id: 1,
                        name: "Продукты на стевии",
                        pic: "/img/main_page/slider/sevia.jpg",
                        href: "/category/produkty-pitaniya",
                    },
                    {
                         id: 2,
                        name: "Пункты выдачи по всей стране",
                        pic: "/img/main_page/slider/3.jpg",
                    },
                    {
                        id: 3,
                        name: "Проверка глюкометров",
                        pic: "https://cdn.glukoza-med.ru/img/home-slider/free-check-glukosemeter_1274x640.jpg", 
                    },
                    {
                        id: 4,
                        name: "Ингаляторы и небулайзеры",
                        pic: "/img/main_page/slider/nebulaizer.jpg",
                        href: "/category/ingalyatory",
                    },
                    
                ],
                options: {
                    slidesPerView: 1,
                    loop: true,
                    loopedSlides: 4,
                    speed: 400,
                    autoplay: {
                        delay: 5000,
                    },
                    autoHeight: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                        bulletClass: 'mint-swipe-indicator',
                        bulletActiveClass: 'mint-swipe-indicator-active',
                        renderBullet: (index, className) => {
                            let slideName = _.find(this.slides, { id: index }).name
                            return '<div class="mint-swipe-indicator '+className+'">' + slideName + '</div>';
                        }
                    },

                }
            }
        },
        computed: {
        },
        methods: {
            checkSwiperStatus() {
                if (this.$refs.bigswiper){
                    this.swiperLoaded = true
                } else {
                    setTimeout(this.checkSwiperStatus, 200)
                }
            }
        },
        mounted() {
            this.checkSwiperStatus()
        }
    }
</script>

<style lang="less">

    @import "../../less/public/variables";

    .mainSliders__wrapper{
        background: #ececec;
        padding: 20px 0;
    }

    .mainSlider{
        @media @xs{
            margin: -19px -15px 0;
        }
        &__wrapper{
            width: 100%;
            background: #fafafa;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        &__content {
            height: 380px;
            color: #fff;
            font-size: 30px;
            text-align: center;
            @media @xs{
                height: 50vw;
            }
        }
        &__slide {
            display: flex;
            width: 100%;
            height: 320px;
            align-items: center;
            justify-content: center;
            background-color: #2d9fe1;
            position: relative;
            @media @xs{
                height: 50vw;
            }
            &Pic{
                position: absolute;
                width: 100%;
                height: 100%;
                background-position: center center;
                display:block;
            }
        }
        &__indicators{
            position: relative;
            height: 60px;
            bottom: 60px !important;
            display: flex;
            flex-direction: row;
            transform: none;
            left: 0;
            @media @xs{
                display: none;
            }
            .mint-swipe-indicator{
                width: 100%;
                height: 60px;
                display: flex;
                border-radius: 0;
                background: rgb(242, 242, 242);
                text-align: center;
                margin: 0;
                padding: 0 10px;
                line-height: 120%;
                font-size: 14px;
                align-items: center;
                justify-content: center;
                font-family: @arial;
                cursor: pointer;
                opacity: .8;
                color: @color-text-default;
                border-left: 1px solid rgb(231, 230, 230);
                &:first-of-type{
                    border-left:0;
                }
                &:hover{
                    background: rgb(255, 255, 255);
                    opacity: 1;
                }
                &-active{
                    background: rgb(255, 255, 255);
                    opacity: 1;
                }
            }
            .mint-swipe-indicators{


            }
        }
    }

</style>