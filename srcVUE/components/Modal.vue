<template>
    <div class="ui_modal_overlay" :class=position @click.self=close>
        <div class="ui_modal" :style="styles" :class=addclass>
            <div class="ui_modal_close" @click=close>
                <svg xmlns="http://www.w3.org/2000/svg" fill="#3c474c" height="24" viewBox="0 0 24 24" width="24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                </svg>
            </div>
            <div class="h2 ui_modal_title">{{ title }}</div>
            <slot></slot>
        </div>
    </div>
</template>


<script>
    module.exports = {
        name: 'Modal',
        props: {
            title: {
                type: String,
                default: 'Заголовок'
            },
            addclass: {
                type: String,
                default: ''
            },
            position: {
                type: String,
                default: 'center'
            },
            width: {
                type: Number,
                default: 540
            }
        },
        data(){
            return { }
        },
        computed: {
            styles() {
                return {
                    width: this.width + 'px'
                }
            }
        },
        methods: {
            close() {
                this.$emit('close')
            }
        },
        beforeMount() {
            $('body').addClass("ovh")
            document.body.addEventListener("touchmove", this.freezeVp, false)
        },
        beforeDestroy() {
            $('body').removeClass("ovh")
            document.body.removeEventListener("touchmove", this.freezeVp, false)
        },
        freezeVp: function(e) { e.preventDefault() }
    }
</script>


<style lang="less">

    @import "../less/public/variables";

    .ui_modal_title{
        font-family: 'Roboto', Arial;
        font-size: 18px;
        font-weight: 500;
        margin-top: 0;
    }
    .ui_modal_overlay{
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        background: rgba(0, 0, 0, 0.5);
        position: fixed;
        z-index: 999;
        bottom: 0;
        left: 0;
        right: 0;
        top: -70px;
        padding-bottom: 70px;
        transform: translateY(70px);
        outline: 0;
        &.center{

        }
        &.right{
            align-items: flex-end;
        }
    }
    .ui_modal_close{
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
    }
    .ui_modal{
        position: relative;
        background: #fff;
        padding: 15px;
        overflow: hidden;
        overflow-y: scroll;
        min-height: 100vh;
        max-height: 100vh;
        height: auto;
        width: 500px;
        max-width: 100%;
        z-index: 9999;
        box-shadow: 0 0 100px rgba(0, 0, 0, 0.35);
        -webkit-overflow-scrolling: touch;
        @extend .noScrollbar;
        @include break(sm) {
            padding: 20px;
        }
    }
</style>