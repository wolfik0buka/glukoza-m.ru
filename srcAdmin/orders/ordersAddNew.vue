<template>
    <div class="ordersAdd">
        <button @click="addOrder()" class="btn btn-mobile font-roboto">
            <span>+</span> Добавить
        </button>
    </div>
</template>

<script>
    module.exports = {
        name: "ordersAddNew",
        props: [],
        data() {
            return {}
        },
        computed: {},
        methods: {
            addOrder() {
                axios.post('/admin/orders/add').then(response => {
                    window.location.href = `/admin_new/index.php?page=order&id=${response.data.id}`;
                })
            }
        },
        beforeMount() {
        }
    }
</script>

<style lang="scss">
    .ordersAdd{
        display: inline-block;
        margin-left: 10px;
        .btn{
            font-weight: 500;
            display: flex;
            align-items: center;
            span{
                display: inline-block;
                font-size: 16px;
                font-family: 'Verdana';
                font-weight: bold;
                line-height: 100%;
                padding-right: 5px;
            }
            &:hover{
                box-shadow: 0 1px 3px rgba(0,0,0,.15);
            }
        }
    }
</style>