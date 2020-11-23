<template>
    <div class="expandCat" @mouseover=showMenu @mouseleave=closeMenu >

        <div
          class="expandCat__button"
          @click=toggleMenu
          @mouseover=toggleMenu
          :class="{opened:isOpened}">

            <template v-if=!isMobile >
                <template v-if=!isOpened >Каталог <i class="fa fa-chevron-down"></i></template>
                <template v-if=isOpened >Каталог <i class="fa fa-chevron-up"></i></template>
            </template>

            <template v-if="isMobile">
                <svg class="expandCat__burger" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
                </svg>
            </template>
        </div>

        <transition name="fade" mode="out-in">
            <div
              v-if=isOpened
              class="expandCat__menu"
              :class="{fixed:fixed}"
              v-on-clickaway="closeMenu">
                <div class="expandCat__level1">
                    <div v-if=isMobile class="title">Каталог</div>
                    <div v-if=isMobile @click=closeMenu class="close"><i class="fa fa-times"></i></div>
                    <expand-cat-item
                        v-for="cat in catsFirstLevel"
                        :isMobile=isMobile
                        :cat=cat
                        :key="cat.id">
                    </expand-cat-item>
                </div>
            </div>
        </transition>

    </div>
</template>

<script>
    // import { mixin as clickaway } from 'vue-clickaway';
    module.exports = {
        name: 'ExpandCat',
        props: [
            'fixed'
        ],
        data(){
            return {
                isMobile: false
            }
        },
        // mixins: [ clickaway ],
        computed: {
            catsFirstLevel() {
                return _.filter(this.$store.state.catalog, cat => {
                    return cat.parent === 1
                })
            },
            isOpened: {
                get() {
                    return this.$store.state.isExpandMenuOpened
                },
                set(val) {
                    this.$store.commit('setExpandCatState', val)
                }

            }
        },
        methods: {
            toggleMenu() {
                this.isOpened = !this.isOpened
                _ym.goal('expandcat')
            },
            showMenu(){
                if (!this.isOpened) { this.isOpened = true }
            },
            closeMenu() {
                if (this.isOpened) { this.isOpened = false }
            },
            checkMobile() {
                this.isMobile = window.matchMedia('all and (max-width: 767px)').matches;
            },
        },
        mounted() {
            this.checkMobile()
            window.addEventListener("resize", this.checkMobile, false)
        },
        components: {
            'ExpandCatItem': require('./ExpandCatItem.vue').default,
        },
    }
</script>

<style lang="less">
    
    @import "../less/public/variables";
    
    .expandCat {
        height: @nav_height;
        width: 100%;
        position: relative;
        font-family: @arial;
    }

    .expandCat__button {
        font-family: @arial;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        font-weight: 600;
        font-style: normal;
        height: @nav_height;
        border-radius: 3px;
        font-size: 18px;
        line-height: @nav_height - 2;
        padding: 0 15px;
        border: 0;
        position: relative;
        transition: all .2s ease;
        text-align: center;
        cursor: pointer;
        background: transparent;
        @media (min-width: 767px) {
            background: @blue;
            color: #fff;
            flex-direction: row;
        }
        &.opened {
            box-shadow: 0 3px 3px rgba(0, 0, 0, .2);
            background: @red;
            @media (min-width: 767px) {
                box-shadow: none;
                flex-direction: row;
            }
        }
        i.fa-chevron-down, i.fa-chevron-up {
            font-size: 80%;
            padding: 3px 0 1px 5px;
        }
    }

    svg.expandCat__burger{
        fill: #333;
        width: 33px;
    }

    .expandCat__menu {
        display: block;
        position: absolute;
        z-index: 88;
        width: 200%;
        top: 100%;
        left: 0;
        right: 0;
        height: 100vh;
        box-sizing: border-box;
        padding: 0;
        border: 0;
        border-radius: 0;
        transition: all .3s ease;
        max-height: calc(100vh - 194px);
        overflow: hidden;
        overflow-y: scroll;
        -ms-overflow-style: none;
        overflow: -moz-scrollbars-none;
        -webkit-overflow-scrolling: touch;
        &::-webkit-scrollbar {
            width: 0px;  /* remove scrollbar space */
            background: transparent;  /* optional: just make scrollbar invisible */
            display: none;
        }
        @media @xs{
            height: 100vh;
            max-height: 100vh;
            width: 80vw;
            position: fixed;
            overflow-x: visible;
            overflow-y: scroll;
            top: 0;
            left: 0;
            right: 15px;
            box-shadow: 5px 0 125px rgba(0, 0, 0, 0.3);
        }
        &.fixed{
            max-height: calc(100vh - 66px);
            @media @xs{
                height: 100vh;
                max-height: 100vh;
            }
        }

    }

    .expandCat__level1{
        width: 50%;
        position: absolute;
        background: white;
        text-align: left;
        padding: 10px 0 20px;
        margin: 0;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
        @media @xs{
            width: 100%;
            min-height: 100vh;
        }
        .title{
            font-size: 20px;
            font-weight: 500;
            padding: 0 15px;
            color: #424242;
            border-bottom: 1px solid #f4f4f4;
            height: 50px;
            line-height: 50px;
            margin: -10px 0 0;
        }
        .close{
            position: fixed;
            top: 10px;
            left: ~"calc(80vw - 40px)";
            height: 30px;
            width: 30px;
            line-height: 30px;
            z-index: 99;
            text-align: center;
            background: #fff;
            color: #424242;
            opacity: .9;
            text-shadow: none;
            font-size: 16px;
            border-radius: 50%;
        }
        a{
            font-family: "Tahoma", Arial, Helvetica, sans-serif;
            display: block;
            font-size: 14px;
            padding: 5px 15px;
            color: #333;
            line-height: 130%;
            position: relative;
            text-decoration: none;
            white-space: normal;
            @media @lg{
                &.active {
                    color: @red;
                }
                &:hover {
                    color: @red;
                }
            }
            @media @xs{
                padding: 9px 15px;
                font-size: 16px;
                line-height: 130%;
            }
            &:active {
                color: @red;
            }
            & > i {
                position: absolute;
                display: block;
                top: 5px;
                right: 15px;
            }
        }
    }

    .expandCat__level2{
        display: block;
        width: 98%;
        position: absolute;
        top: 0;
        left: 100%;
        bottom: 0;
        background: white;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
        text-align: left;
        height: 100%;
        padding: 12px 0;
        .cat_title {
            font-weight: 500;
            font-size: 18px;
            padding: 0 15px 5px;
            color: #333;
        }
        a {
            font-family: "Tahoma", Arial, Helvetica, sans-serif;
            &:hover {
                color: @red;
            }
        }
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .3s ease-in-out;
    }
    .fade-enter, .fade-leave-active {
        opacity: 0
    }

</style>