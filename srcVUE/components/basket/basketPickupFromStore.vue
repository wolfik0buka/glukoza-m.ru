<template>
    <div class="top--20">
        <p>{{ model.shop_address }}</p>
    </div>
</template>

<script>
    module.exports = {
        name: 'basketPickupFromStore',
        computed: {
            model() {
                return this.$store.getters.deliveryModel(this.$store.getters.order.delivery)
            }
        }
    }
</script>
