<template>
    <transition name="fade" mode="out-in">
        <div
          v-on-clickaway="closeSearch"
          class="headerSearch"
          :class="{'headerSearch-active':isActive}">

            <template v-if="$store.state.isMobile && isActive">
                <div class="headerSearch__title">Поиск товаров</div>
                <div @click="closeSearch" class="close"><i class="fa fa-times"></i></div>
            </template>

            <form
                id="header_search"
                @submit.prevent="preventSubmit"
                autocomplete="off">

                <div class="input-group" style="width:100%;">
                    
                    <input class="form-control"
                           @click="openSearch"
                           v-if="isShowSearchInput"
                           type="text"
                           id="search_word"
                           v-model="query"
                           placeholder="Поиск по сайту"
                           autocomplete="off">

                    <div
                        v-if="$store.state.isMobile && !isActive"
                        @click.self="openSearch"
                        class="headerSearchActivator-mobile">
                        Поиск
                    </div>

                    <span
                        v-if="!$store.state.isMobile"
                        class="input-group-addon">
                        Найти
                    </span>
                    
                </div>

                <div class="search_results" v-if="isActive">
                    <div class="text-muted" v-if="query.length == 0">
                        Введите запрос для поиска. Например, <a @click="setTestQuery" v-text="testQuery">глюкометр</a>
                    </div>
                    <div class="text-muted" v-if="query.length > 0 && query.length < 3">
                        Поисковый запрос слишком короткий
                    </div>
                    <div class="text-muted" v-if="isNoResults && !(query.length > 0 && query.length < 3)">
                        Ничего не найдено, попробуйте упростить запрос
                    </div>
                    <div class="isLoading" v-if="isLoading">Поиск</div>

                    <div class="title" v-if="results.query">
                        По запросу «{{ results.query }}» найдено товаров: {{ results.count_products }}
                    </div>

                    <a class="btn btn-primary" v-if="results.count_products" :href="'/search/'+results.query">Показать все</a>
                </div>

            </form>
            
            <div
                v-if="isActive && $store.state.isMobile"
                @click="closeSearch"
                class="headerSearch__overlay">
            </div>
            
        </div>
    </transition>
</template>
<script>
    module.exports = {
        name: 'headerSearch',
        props: [],
        data() {
            return {
                isActive: false,
                isLoading: false,
                isNoResults: false,
                isShort: true,
                query: '',
                results: {
                    query: false,
                    count_products: false
                },
                testQuery: 'глюкометр'
            }
        },
        computed: {
            isShowSearchInput() {
                if (this.isMobile()) {
                    return this.isActive;
                }
                return true;
            }
        },
        watch: {
            query(query, oldQuery) {
                if(!_.isEmpty(query)) {
                    if (query != oldQuery) {
                        this.results = false
                        if (query.length > 2) {
                            this.isNoResults = false
                            this.isLoading = true
                            this.doSearch(query)
                        }
                    }
                } else {
                    this.results = false
                    this.isNoResults = false
                }
            }
        },
        methods: {
            checkLenght(query) {
                return !query.length > 3
            },
            closeSearch() {
                this.isActive = false
                document.body.style.overflow = ""
                document.body.style.height = ""
            },
            preventScroll() {
                let checkTime = 0
                setTimeout(function() {
                    window.scrollTo(0,0)
                    this.$el.scrollTo(0,0)
                }, 100)
            },
            openSearch() {
                setTimeout(() => {
                    this.isActive = true
                }, 200)
                if (this.isMobile()) {
                    document.body.style.overflow = "hidden"
                    document.body.style.height = "100%"
                }
            },
            preventSubmit() {
                if (this.results.count_products) {
                    window.location.href = '/search/' + this.results.query
                }
            },
            doSearch: _.debounce(function(query) {
                return this.requestSearch(query)
            }, 500),
            requestSearch: function() {

                _ym.params({ query: this.query })
                _ym.goal('search')

                axios.post('/search', { query: this.query }).then(response => {
                        this.isLoading = false
                        if (!_.isEmpty(response.data)) {
                            this.results = response.data
                        } else {
                            this.isNoResults = true
                        }
                    })
            },
            setTestQuery() {
                this.query = this.testQuery

                let attempts = 0
                while (attempts < 10) {
                    attempts += 1
                    setTimeout(this.openSearch, 200)
                }
            },
            isMobile() {
                return window.matchMedia('all and (max-width: 767px)').matches;
            }
        },
        beforeDestroy() { }
    }
</script>

<style lang="less">
    @import "../less/public/variables";
    @import "../less/helpers";

    .headerSearch{
        position: relative;
    }

    .headerSearch-active{
        @media @xs {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            width: 80vw;
            height: 100%;
            z-index: 99;
            background: #fff;
            overflow-y: hidden;
            box-shadow: 5px 0 125px rgba(0, 0, 0, 0.3);
        }
        .headerSearch__title{
            @media @xs{
                font-size: 20px;
                font-weight: 500;
                padding: 0 15px;
                color: #424242;
                border-bottom: 1px solid #f4f4f4;
                height: 50px;
                line-height: 50px;
                margin: 0;
            }
        }
        .close{
            @media @xs{
                position: absolute;
                top: 10px;
                right: 10px;
                height: 30px;
                width: 30px;
                line-height: 30px;
                z-index: 99;
                text-align: center;
                background: transparent;
                color: #424242;
                opacity: 1;
                text-shadow: none;
                font-size: 16px;
                border-radius: 50%;
            }
        }
        form#header_search{
            @media @xs{
                padding: 15px;
                height: 100vh;
            }
            .search_results{
                @media @xs{
                    top: 70px;
                    max-height: ~"calc(100vh - 126px)";
                    box-shadow: none;
                }
            }
        }
        }

    #header_search{
        position: relative;
        span.input-group-addon{
            background: @search_color;
            border-color: @search_color;
            font-size: 16px;
            font-weight: 500;
            padding: 0 20px;
            cursor: pointer;
        }
        input[type=text]{
            border: 2px solid @search_color;
            font-size: 16px;
            border-right: none;
            box-shadow: none;
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
            height: @nav_height;
            @media @xs{
                border-right: 2px solid @search_color;
                border-top-right-radius: 3px;
                border-bottom-right-radius: 3px;
            }
        }
        .search_results{
            background: white;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
            padding: 15px;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 500px;
            overflow-y: scroll;
            .title{
                font-weight: 500;
                font-size: 16px;
            }
            a.btn{
                margin-top: 20px;
                @media @xs{
                    display: block;
                    padding: 10px 0;
                    font-size: 16px;
                }
            }
        }
        @keyframes isloading {
            0% { -webkit-transform: rotate(359deg); transform: rotate(359deg);}
            100% { -webkit-transform: rotate(0deg); transform: rotate(0deg);}
        }
        .isLoading {
            @spinSize: 30px;
            position: relative;
            opacity: .6;
            text-align: center;
            padding: 60px 0 5px;
            font-size: 16px;
            -webkit-transition: .3s all ease;
            transition: .3s all ease;
            &:after {
                position: absolute;
                top: @spinSize/2;
                left: 50%;
                margin-left: -@spinSize/2;
                width: @spinSize;
                height: @spinSize;
                background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjY0cHgiIGhlaWdodD0iNjRweCIgdmlld0JveD0iMCAwIDUxNi43MjcgNTE2LjcyNyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTE2LjcyNyA1MTYuNzI3OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTUxNi43MjcsMjY2LjY5NmMtMC42NjUtMzQuODI1LTguMjIxLTY5LjU0LTIyLjE3NS0xMDEuMjgzYy0xMy45MDgtMzEuNzcxLTM0LjA5NC02MC41NTEtNTguODc2LTg0LjMzMyAgIGMtMjQuNzY3LTIzLjgtNTQuMTM5LTQyLjYxNS04NS45MjktNTUuMDI3Yy0zMS43NzMtMTIuNDYtNjUuOTM3LTE4LjQxMi05OS42ODctMTcuNjljLTMzLjc1NSwwLjY2OC02Ny4zNiw4LjAwNy05OC4wOTEsMjEuNTM5ICAgYy0zMC43NTQsMTMuNDg4LTU4LjYxNSwzMy4wNTgtODEuNjMyLDU3LjA3MWMtMjMuMDMzLDI0LjAwMS00MS4yMjksNTIuNDUyLTUzLjIyMiw4My4yMjlDNS4wNzcsMjAwLjk2Mi0wLjY2LDIzNC4wMTMsMC4wNiwyNjYuNjk2ICAgYzAuNjcsMzIuNjg4LDcuNzkzLDY1LjE4MiwyMC45MDMsOTQuODk5YzEzLjA2NywyOS43MzgsMzIuMDE5LDU2LjY4MSw1NS4yNjYsNzguOTMxYzIzLjIzNCwyMi4yNjgsNTAuNzY2LDM5Ljg0Niw4MC41MjgsNTEuNDE3ICAgYzI5Ljc0OSwxMS42MTYsNjEuNjksMTcuMTM2LDkzLjMwMywxNi40MTljMzEuNjItMC42NzEsNjMuMDAxLTcuNTgsOTEuNzA3LTIwLjI2OGMyOC43MjQtMTIuNjQ2LDU0Ljc0Ny0zMC45NzksNzYuMjMxLTUzLjQ2MSAgIGMyMS41MDMtMjIuNDY5LDM4LjQ2MS00OS4wOCw0OS42MTEtNzcuODI3YzYuNzktMTcuNDI3LDExLjM5Ni0zNS42MjQsMTMuODI0LTU0LjA2MmMwLjY0OSwwLjAzNywxLjMwMiwwLjA2MywxLjk2LDAuMDYzICAgYzE4LjQwOSwwLDMzLjMzMy0xNC45MjMsMzMuMzMzLTMzLjMzM2MwLTAuOTM2LTAuMDQ5LTEuODYxLTAuMTI0LTIuNzc3TDUxNi43MjcsMjY2LjY5Nkw1MTYuNzI3LDI2Ni42OTZ6IE00NjMuNzYyLDM1NS4yMSAgIGMtMTIuMjI2LDI3LjcxLTI5Ljk0LDUyLjgxMi01MS42NTUsNzMuNTMyYy0yMS43MDMsMjAuNzMyLTQ3LjM5NiwzNy4wNzYtNzUuMTI3LDQ3LjgwN2MtMjcuNzI0LDEwLjc3LTU3LjQ0MywxNS44NTktODYuOTE5LDE1LjE0NiAgIGMtMjkuNDgxLTAuNjc3LTU4LjY0NC03LjE1NC04NS4zMjMtMTguOTk3Yy0yNi42OTItMTEuODA2LTUwLjg3Ny0yOC45MDEtNzAuODMtNDkuODQ5Yy0xOS45NjgtMjAuOTM4LTM1LjY5MS00NS43MTEtNDYuMDAxLTcyLjQyNyAgIGMtMTAuMzQ5LTI2LjcxMi0xNS4yMjMtNTUuMzIxLTE0LjUxMi04My43MjhjMC42NzgtMjguNDEzLDYuOTQxLTU2LjQ2NSwxOC4zNjEtODIuMTMxYzExLjM4NC0yNS42NzcsMjcuODYzLTQ4Ljk0Myw0OC4wNDUtNjguMTMgICBjMjAuMTcyLTE5LjIwMiw0NC4wMjYtMzQuMzA3LDY5LjcyNi00NC4xOTVjMjUuNjk3LTkuOTI4LDUzLjE5NS0xNC41ODcsODAuNTM0LTEzLjg3N2MyNy4zNDMsMC42OCw1NC4yODYsNi43MjgsNzguOTM5LDE3LjcyNiAgIGMyNC42NjIsMTAuOTYzLDQ3LjAwOCwyNi44MjQsNjUuNDI5LDQ2LjI0MWMxOC40MzYsMTkuNDA1LDMyLjkyMiw0Mi4zNDEsNDIuMzkxLDY3LjAyNWM5LjUwNCwyNC42ODQsMTMuOTQ4LDUxLjA3MiwxMy4yNDEsNzcuMzQyICAgaDAuMTI1Yy0wLjA3NiwwLjkxNi0wLjEyNSwxLjg0MS0wLjEyNSwyLjc3N2MwLDE3LjE5MywxMy4wMTgsMzEuMzQsMjkuNzMyLDMzLjEzN0M0NzYuNTUxLDMyMC43NDcsNDcxLjE4NCwzMzguNDUzLDQ2My43NjIsMzU1LjIxICAgeiIgZmlsbD0iIzAwMDAwMCIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo=);
                background-size: @spinSize @spinSize;
                text-align: center;
                z-index: 2;
                content: "";
                -webkit-animation: isloading 2s infinite linear;
                animation: isloading 2s infinite linear;
            }
        }
    }


    .headerSearchActivator-mobile{
        font-size: 16px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        color: #b5b4b4;
        height: @nav_height;
        border: 2px solid @search_color;
        border-radius: 3px;
        cursor: pointer;
        padding: 0 15px;
        @media (min-width:768px) {
            display: none;
        }
        svg{
            width: 20px;
            height: 20px;
        }
    }


    .headerSearch__overlay{
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        width: 20vw;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        z-index: 2;
    }


</style>