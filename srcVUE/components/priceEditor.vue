<template>
    <div class="priceEditor">
        <input
            type="number"
            :disabled="!isEdit"
            :class="{active:isEdit}"
            v-model.number="newValue"
        />
        
        <button
            class="priceEditor__btn"
            type="button"
            v-show="!isEdit"
            @click="isEdit=true">
            <i class="fa fa-pencil"></i>
        </button>
    
        <button
            class="priceEditor__btn active"
            type="button"
            v-show="isEdit"
            @click="update">
            <i class="fa fa-check"></i>
        </button>
    </div>
</template>

<script>
    module.exports = {
        name: "priceEditor",
        props: {
            price: {
                default: 0,
                type: Number
            },
            disabled: {
                default: false,
                type: Boolean
            }
        },
        data() {
            return {
                newValue: 0,
                isEdit: false,
            }
        },
        computed: {},
        watch: {
            price: {
                handler: function (newVal, oldVal) {
                    this.newValue = parseFloat(String(newVal)).toFixed(2);
                }
            },
            newValue: {
                handler: function (val) {
                    if (parseFloat(val) < 0) {
                        this.newValue = 0;
                    }
                }
            }
        },
        methods: {
            update() {
                this.$emit('input', this.newValue);
                this.isEdit = false;
            }
        },
        created: function() {
            this.newValue = this.price;
        },
    }
</script>


<style lang="scss">
    $height: 30px;

    .priceEditor {
        border-radius: 5px;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        user-select: none;
        height: $height;
        background: transparent;
        border: 1px solid #eee;
        width: 100%;
        & > input {
            width: 100%;
            text-align: center;
            font-size: 15px;
            padding: 0;
            border: none;
            height: $height;
            line-height: $height;
            background: transparent;
            -moz-appearance: textfield;
            border-radius: 5px;
            &:focus, &:active{
                outline: none;
                box-shadow: none;
            }
            &::-webkit-outer-spin-button,
            &::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            &.active{
                background: #e3e3e3;
            }
        }
        &__btn{
            flex:none;
            height: $height;
            width: $height;
            background: #e3e3e3;
            border: 0;
            font-size: 16px;
            border-radius: 0 5px 5px 0;
            &.active{
                background: #0098e2;
                color: #fff;
            }
            &:focus, &:active{
                outline: none;
                box-shadow: none;
            }
        }
    }
    
    
</style>






