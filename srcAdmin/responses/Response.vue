<template>
    <div class="font-roboto">
        <div class="mb-10 mt-10">
            <a :href="'/admin_new/index.php?page=responses'" class="btn btn-default">К списку отзывов</a>
        </div>
        <div class="response__wrapper">
            <div class="response__editor">
                <div class="card mt-15">
                    <div class="card__content">
                        <h1>
                            Отзыв к товару <a href="">Нзвание товара</a>
                        </h1>
                        <div>
                            <label>ФИО</label>
                            <input
                                type="text"
                                class="form-control font-s15"
                                >
                        </div>
                        <div>
                            <label>Рейтинг</label>
                            <input
                                type="number"
                                class="form-control font-s15"
                                >
                        </div>
                        <div>
                            <label>Текст отзыва</label>
                            <wysiwyg
                                placeholder="Введите текст.."
                                />
                        </div>
                        <div>
                            <label>Текст ответа</label>
                            <wysiwyg
                                placeholder="Введите текст.."
                                 />
                        </div>
                    </div>
                </div>
                <div class="card mt-15">
                    <div class="card__content">
                        <button class="btn btn-lg btn-primary"> Сохранить </button>
                    </div>
                </div>
            </div>
            <div class="response__status">
                <div class="card mb-15">
                    <div class="card__content">
                        <div class="card__title mb-15">Сводка</div>
                        <div class="card__listItem">
                            <span class="text-muted">Создан</span>
                            <span>дата</span>
                        </div>
                        <label>Опубликован</label>
                        <div class="mb-15">
                            <div class="btn-group">
                                <button
                                    class="btn"
                                    :class="[1 ? 'btn-primary' : 'btn-default']">
                                    Да
                                </button>
                                <button
                                    class="btn"
                                    :class="[0 ? 'btn-primary' : 'btn-default']">
                                    Нет
                                </button>
                            </div>
                        </div>
                        <label>Удален</label>
                        <div class="mb-15">
                            <div class="btn-group">
                                <button
                                    class="btn"
                                    :class="[0 ? 'btn-primary' : 'btn-default']">
                                    Да
                                </button>
                                <button
                                    class="btn"
                                    :class="[1 ? 'btn-primary' : 'btn-default']">
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
        computed: {},
        methods: {},
        beforeMount() {
            if (this.response_id > 0) {
                console.log(this.response_id)
                this.$store.dispatch('getProduct', this.response_id);
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