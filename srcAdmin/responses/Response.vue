<template>
    <div class="font-roboto">
        <div class="mb-10 mt-10">
            <a :href="'/admin_new/index.php?page=responses'" class="btn btn-default">К списку отзывов</a>
        </div>
        <div class="response__wrapper">
            <div class="response__editor">
                <div class="card mt-15">
                    <div class="card__content">
                        <h1 v-if="response.tovar_id">
                            Отзыв к товару 
                            <a :href="productLink">{{response.linked_tovar.name}}</a>
                        </h1>
                        <h1 v-else>
                            Отзыв на сайте от {{ response.created_at }}
                        </h1>
                        <div>
                            <label>ФИО</label>
                            <input
                                type="text"
                                class="form-control font-s15"
                                v-model.number="fio"
                                >
                        </div>
                        <div>
                            <label>Рейтинг</label>
                            <input
                                type="number"
                                class="form-control font-s15"
                                v-model.number="rating"
                                >
                        </div>
                        <div>
                            <label>Текст отзыва</label>
                            <wysiwyg
                                placeholder="Введите текст.."
                                :html="response.response"
                                @change="updateResponseText"
                                />
                        </div>
                        <div>
                            <label>Текст ответа</label>
                            <wysiwyg
                                placeholder="Введите текст.."
                                :html="response.answer"
                                @change="updateAnswer"
                                 />
                        </div>
                    </div>
                </div>
                <div class="card mt-15">
                    <div class="card__content">
                        <button class="btn btn-lg btn-primary"  @click="saveResponse()"> Сохранить </button>
                    </div>
                </div>
            </div>
            <div class="response__status">
                <div class="card mb-15">
                    <div class="card__content">
                        <div class="card__title mb-15">Сводка</div>
                        <div class="card__listItem">
                            <span class="text-muted">Создан</span>
                            <span>{{ response.created_at }}</span>
                        </div>
                        <label>Опубликован</label>
                        <div class="mb-15">
                            <div class="btn-group">
                                <button
                                    class="btn"
                                     @click="response.confirmed===0 ? response.confirmed=1 : null"
                                    :class="[response.confirmed===1 ? 'btn-primary' : 'btn-default']"
                                    >
                                    Да
                                </button>
                                <button
                                    class="btn"
                                    @click="response.confirmed===1 ? response.confirmed=0 : null"
                                    :class="[response.confirmed===0 ? 'btn-primary' : 'btn-default']"
                                   >
                                    Нет
                                </button>
                            </div>
                        </div>
                        <label>Удален</label>
                        <div class="mb-15">
                            <div class="btn-group">
                                <button
                                    class="btn"
                                    @click="response.deleted===0 ? response.deleted=1 : null"
                                    :class="[response.deleted===1 ? 'btn-primary' : 'btn-default']"
                                    >
                                    Да
                                </button>
                                <button
                                    class="btn"
                                    @click="response.deleted===1 ? response.deleted=0 : null"
                                    :class="[response.deleted===0 ? 'btn-primary' : 'btn-default']"
                                    >
                                    Нет
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</template>

<script>
    module.exports = {
        name: "Response",
        props: [
            'response_id'
        ],
        data() {},
        computed: {
            response() {
                console.log(this.$store.state.response)
                return this.$store.state.response
            },
            productLink () {
                return '/admin_new/index.php?page=nom&id=' + this.response.tovar_id
            },
            fio: {
                get() {
                    return this.response.fio;
                },
                set(val) {
                    this.response.fio = val.trim();
                },
            },
            rating: {
                 get() {
                    return this.response.rating;
                },
                set(val) {
                    if ( parseInt(val) > 5) {
                        this.response.rating = 5;
                    } else if (parseInt(val) < 0) {
                        this.response.rating = 1;
                    } else {
                        this.response.rating = parseInt(val);
                    }
                },
            }
        },
        methods: {
            updateResponseText(payload) {
                this.response.response = payload;
            },
            updateAnswer(payload) {
                this.response.answer = payload;
            },
            saveResponse() {
                this.$store.dispatch('updateResponse', this.response).then(() => {
                    Mess.show("Отзыв успешно сохранен");
                })
            },

        },
        beforeMount() {
            if (this.response_id > 0) {
                console.log(this.response_id)
                this.$store.dispatch('getResponse', this.response_id);
            }
        },
        components: {}
    }
</script>

<style lang="scss">
    .response{
        &__wrapper{
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: nowrap;
        }
        &__editor{
            width: 100%;
        }
        &__status{
            flex:none;
            width: 300px;
            margin-left: 30px;
        }
    }
</style>