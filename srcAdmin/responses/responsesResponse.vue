<template>
     <div 
        class="responses__row"
        :class="{
            responses__row_deleted: response.deleted
        }">
        <a :href="this.link" class="responses__col responses__col_id">
            {{ response.id }}
        </a>
        <a :href="this.link" class="responses__col responses__col_tovar">
            <template v-if="response.linked_tovar">
                {{ response.linked_tovar.name }}
            </template>
            <template v-else>
                Нет товара.
            </template>
        </a>
        <a :href="this.link" class="responses__col responses__col_fio">
            {{ response.fio }}
        </a>
        <a :href="this.link" class="responses__col responses__col_date">
            {{response.created_at}}
        </a>
        <div class="responses__col responses__col_accept">
            <input 
                :value="response.confirmed" 
                type="checkbox" 
                @change="onConfirmClick" 
                :checked="!!response.confirmed"
                :id="'confirmed'+response.id" >
        </div>
        <div class="responses__col responses__col_remove">
            <input 
                :value="response.deleted" 
                type="checkbox" 
                :checked="!!response.deleted"
                :id="'deleted'+response.id" 
                @change="onDeleteClick">
        </div>
    </div>
</template>

<script>
    module.exports = {
        name: "ordersOrder",
        props: {
            response: Object,
            confirm: Function,
            delete: Function,
        },
        data() {},
        computed: {
            link() {
                return '/admin_new/index.php?page=response&id=' + this.response.id;
            },
        },
        methods: {
            onDeleteClick() {
                this.delete(this.response.id, !this.response.deleted);
            },
            onConfirmClick() {
                this.confirm(this.response.id, !this.response.confirmed);
            }
        },
        beforeMount() {
        }
    };
</script>

<style lang="scss" scoped>

</style>