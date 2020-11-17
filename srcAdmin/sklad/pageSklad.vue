<template>
    <div class="row">
        <div class="col-xs-6">
            <div class="card">
                <div class="card__content">
                    <div class="card__title">Обновление цен и остатков</div>
                    <div v-if="isForceUpdating">
                        <div class="loader mt-30 mb-15"></div>
                    </div>
                    <div class="mt-10" v-if="!isForceUpdating">
                        <p>Обновлено {{ isForceUpdated ? 'только что' : lastupdate }}</p>
                        <button
                          @click="forceUpdateSklad()"
                          class="btn btn-default">
                            Обновить принудительно
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "pageSklad",
        props: [
            'lastupdate'
        ],
        data() {
            return {
                isForceUpdating: false,
                isForceUpdated: false
            }
        },
        computed: {},
        methods: {
            forceUpdateSklad() {
                this.isForceUpdating = true
                axios.get('/share_1c/import.php').then(response => {
                    console.log(response.data)
                    this.isForceUpdating = false
                    this.isForceUpdated = true
                    window.location.reload()
                }).catch(response => {
                    this.isForceUpdating = false
                    alert('При обновлении произошла ошибка')
                })
            }
        },
        beforeMount() {
        }
    }
</script>

<style lang="scss">

</style>