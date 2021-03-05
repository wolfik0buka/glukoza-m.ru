<template>
    <div class="mt-15">
        <h2>Отзывы</h2>

        <div class="row">
            <div class="col-sm-3">
                <div class="card card__content cardKpi">
                    <div class="cardKpi__label">Отзывов</div>
                    <div class="cardKpi__number">{{ 0 }}</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card card__content cardKpi">
                    <div class="cardKpi__label">Ожидает</div>
                    <div class="cardKpi__number">{{ 0 }}</div>
                </div>
            </div>
        </div>

        <div class="card mt-15">
            Здесь будут отзывы
        </div>

        <div v-if="1" class="text-center">
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
            
        },
        methods: {
        
        },
        beforeMount() {
            this.$store.dispatch("getResponses");
        }
    };
</script>

<style lang="scss">
    
</style>