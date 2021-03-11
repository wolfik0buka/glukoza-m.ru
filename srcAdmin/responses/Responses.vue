<template>
    <div class="mt-15">
        <h2>Отзывы</h2>

        <div class="row">
            <div class="col-sm-3">
                <div class="card card__content cardKpi">
                    <div class="cardKpi__label">Отзывов</div>
                    <div class="cardKpi__number">{{ responsesCount }}</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card card__content cardKpi">
                    <div class="cardKpi__label">Ожидает</div>
                    <div class="cardKpi__number">{{ responsesNew }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-15">
            <template v-if="responsesCount > 0">
                <div class="responses">
                    <div class="responses__row responses__head">
                        <div class="responses__col responses__col_id">
                            ID
                        </div>
                        <div class="responses__col responses__col_tovar">
                            Товар
                        </div>
                        <div class="responses__col responses__col_fio">
                            ФИО
                        </div>
                        <div class="responses__col responses__col_date">
                            Дата
                        </div>
                        <div class="responses__col responses__col_accept">
                            Опублик.
                        </div>
                        <div class="responses__col responses__col_remove">
                            Удален
                        </div>
                    </div>
                     <responsesResponse
                        v-for="(response, index) in responses"
                        :response="response"
                        :confirm="confirmResponse"
                        :delete="deleteResponse"
                        :key="index">
                    </responsesResponse>
                </div>
               
            </template>
            <template v-else>
                <br>
                <div  class="pb15 text-center">
                    Здесь будут отзывы
                </div>
                <br>
            </template>

            
        </div>

        <div v-if="visibleResponsesCount < responsesCount" class="text-center mt-15">
            <button class="btn btn-default">Показать еще 30</button>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: "Responses",
        props: [],

        data() {
            return {
                visibleResponsesCount: 50,
            };
        },
        computed: {
            responses() {
                return this.$store.state.responses;
            },
            responsesCount() {
                return this.responses.length;
            },
            responsesNew() {
                return _.filter(this.responses, (item) => !item.confirmed && !item.deleted).length;
            },
            
        },
        methods: {
            confirmResponse(id,value){
                const response = _.find(this.responses, (item) => item.id === id);
                if (response) {
                    response.confirmed = value;
                    this.$store.dispatch("updateResponse", response);
                } else {
                    console.warn(`Отзыв с id=${id} не найден`);
                }
            },
            deleteResponse(id,value){
                const response = _.find(this.responses, (item) => item.id === id);
                if (response) {
                    response.deleted = value;
                    this.$store.dispatch("updateResponse", response);
                } else {
                    console.warn(`Отзыв с id=${id} не найден`);
                }
            },
        
        },
        components: {
            responsesResponse: require("./responsesResponse").default,
        },
        beforeMount() {
            this.$store.dispatch("getResponses");
        }
    };
</script>

<style lang="scss">
    .responses{
        &__row{
            display: flex;
            justify-content: flex-start;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e3e3e3;
            &_deleted{
                color: #999;
                a{
                    color: #999;
                }
            }
        }
        &__head{
            font-weight: 700;
        }
        &__col{
            text-align: center;
            &_id{
                flex: none;
                width: 80px;
            }
            &_tovar{
                text-align: left;
                flex:1;
            }
            &_fio{
                text-align: left;
                flex:1;
            }
            &_date{
                flex:none;
                width: 170px;
            }
            &_accept{
                flex:none;
                width: 100px;
            }
            &_remove{
                flex:none;
                width: 100px;
            }
        }
    }
</style>