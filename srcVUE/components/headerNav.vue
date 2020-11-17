<template>
    <div class="header_nav" :class="{'fixed':isFixed}">
        <div class="container">
            <div class="row">
                <div class="col-xs-3 relative_reset">
                    <ExpandCat :fixed="isFixed"></ExpandCat>
                </div>
                <div class="col-xs-6">
                    <headerSearch :isMobile="isMobile"></headerSearch>
                </div>
                <div class="col-xs-3">
                    <headerCart></headerCart>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    module.exports = {
        name: 'headerNav',
        data() {
            return {
                isFixed: false,
                isMobile: false,
                fixedPoint: false
            }
        },
        mounted() {
            this.fixedPoint = document.querySelector('.header').offsetHeight;
            document.addEventListener('scroll', this.checkInView)
            window.addEventListener('resize', this.checkMobile)
        },
        computed: {
        },
        methods: {
            checkInView() {
                this.isFixed = __.scrollTopCurrent() > this.fixedPoint
            },
            checkMobile() {
                this.isMobile = __.isMediaXS()
            }
        },
        watch: {  },
        beforeMount() {
            this.checkMobile()
        },
        beforeDestroy() { },
        components: {
            'ExpandCat': require('./ExpandCat.vue').default,
            'headerSearch': require('./headerSearch.vue').default,
            'headerCart': require('./headerCart.vue').default
        }
    }
</script>


<style lang="less" scoped >
    @import "../less/public/variables";
    .header_nav{
        padding: 10px 0;
        position: relative;
        z-index: 999;
        background: white;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.070);
        &.fixed{
            padding: 10px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
        }
    }
</style>