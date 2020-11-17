<template>
    <div>
        <h2>Подборки товаров</h2>

        <div v-if="collections">
            <CollectionItem
              v-for="collection in collections"
              :collection_raw="collection"
              :key="collection.id">
            </CollectionItem>
        </div>

        <div v-if="!collections" class="card">
            <div class="card__content">
                <div class="loader"></div>
            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: "Collections",
        data() {
            return {
                collections: false,
                activeCollection: false
            }
        },
        created() {
            axios.post('/admin/collections').then(response => {
                this.collections = response.data
            })
        },
        components: {
            CollectionItem: require('./CollectionItem.vue').default
        }
    }
</script>

<style scoped>

</style>