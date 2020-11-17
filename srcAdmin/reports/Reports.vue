<template>
    <div class="container-fluid">
        <div class="">
            <h1>Аналитика</h1>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <div class="card">
                    <div class="card__content">
                        <div class="card__title">Отчеты</div>
                    </div>
                    <div class="card__list">
                        <div @click="setActiveReport('old-and-new-customers')" class="card__listItem card__listItem-active card__listItem-with-hover">
                            Соотношение постоянных клиентов и покупающих впервые
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="card">
                    <div class="card__content">
                        
                        <div v-if="isLoading" class="loader"></div>
                        
                        <div v-if="!isLoading" >
                            
                            <div
                                class="report__title"
                                v-if="html && activeReport === 'old-and-new-customers'">
                                Соотношение постоянных клиентов и покупающих впервые
                            </div>
                            
                            <div v-if="html" v-html="html"></div>
                            <div v-if="!html">Выберите отчет</div>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    
    </div>
</template>

<script>
    module.exports = {
        name: "Reports",
        props: [],
        data() {
            return {
                html: false,
                isLoading: true,
                activeReport: false
            };
        },
        computed: {},
        methods: {
            loadReport() {
                axios.post('/admin/reports/'+this.activeReport).then(response => {
                    this.html = response.data;
                    this.isLoading = false;
                })
            },
            setActiveReport(slug) {
                this.isLoading = true;
                this.activeReport = slug;
                this.loadReport();
            }
        },
        mounted() {
            this.setActiveReport('old-and-new-customers')
        }
    };
</script>

<style lang="scss">
    .report{
        &__title{
            font-weight: bold;
            font-size: 22px;
            padding-bottom: 20px;
        }
    }
</style>