<template>
    <div class="noticeWorktime" :class="{expanded:isActive}">
        
        <div
            @click="isActive=!isActive"
            class="noticeWorktime__activator"
            :class="{pointer:!isOneString}">
            
            <div
                v-if="false"
                class="noticeWorktime__snow">
            </div>
            
            <i class="fa fa-calendar-check-o"></i> <span v-html="data.title"></span>
            
        </div>
        
        <div v-if="isActive && !isOneString" class="noticeWorktime__expand">
            <div class="container">
                
                <div class="noticeWorktime__content" v-if="data.content">
                    <div v-html="data.content"></div>
                </div>
                
                <div class="text-center">
                    <div
                      @click="isActive=false"
                      class="noticeWorktime__btn">
                        Свернуть
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</template>

<script>
    module.exports = {
        name: "noticeWorktime",
        props: [
            'data'
        ],
        data() {
            return {
                isActive: false
            }
        },
        computed: {
            isOneString() {
                return +this.data.isOneStringMode === 1;
            }
        }
    }
</script>

<style lang="less" scoped>

    @import "../../less/public/variables";

    .noticeWorktime{
        font-family: "Arial", "Helvetica", sans-serif;
        text-align: center;
        background: @blue; // синий зимний вариант
        //background: #269d7d; // зеленый весенний вариант
        color: #fff;
        line-height: 46px;
        max-height: 46px;
        font-size: 15px;
        overflow: hidden;
        position: relative;
        transition: .6s all ease-in-out;
        font-weight: bold;
        &.expanded{
            max-height: 1500px;
        }
        &__activator{
            white-space: nowrap;
            &.pointer{
                cursor: pointer;
            }
            i{
                margin-right: 5px;
            }
        }
        &__expand{
            color: #333;
            padding-bottom: 15px;
            background: #fff;
        }
        &__content{
            margin: 0 auto;
            max-width: 600px;
            padding-top: 15px;
            line-height: 120%;
            font-size: 16px;
            font-weight: normal;
            p{
                margin-bottom: 10px;
            }
        }
        &__btn{
            background: @blue;
            border-radius: 2px;
            display: inline-block;
            vertical-align: top;
            line-height: 180%;
            font-size: 15px;
            padding: 5px 12px;
            margin-top: 10px;
            font-weight: 400;
            color: #fff;
        }

    }

    .noticeWorktime__snow{
        background-image:
          url('https://glukoza-med.ru/img/notice_ny_snowflakes/s1.png'),
          url('https://glukoza-med.ru/img/notice_ny_snowflakes/s2.png'),
          url('https://glukoza-med.ru/img/notice_ny_snowflakes/s3.png');
        height: 100%;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
        z-index:1;
        -webkit-animation: snow 10s linear infinite;
        -moz-animation: snow 10s linear infinite;
        -ms-animation: snow 10s linear infinite;
        animation: snow 10s linear infinite;
        pointer-events: none;
    }
    @keyframes snow {
        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
        100% {background-position: 500px 1000px, 200px 400px, -100px 300px;}
    }
    @-moz-keyframes snow {
        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
        100% {background-position: 400px 1000px, 200px 400px, 100px 300px;}
    }
    @-webkit-keyframes snow {
        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
        100% {background-position: 500px 1000px, 200px 400px, -100px 300px;}
    }
    @-ms-keyframes snow {
        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
        100% {background-position: 500px 1000px, 200px 400px, -100px 300px;}
    }



</style>