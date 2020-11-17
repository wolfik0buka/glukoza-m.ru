<template>
    <div class="minusplusnumber">

        <div class="mpbtn minus" v-on:click="mpminus()">
            <i class="fa fa-minus" aria-hidden="true"></i>
        </div>

        <input type="number" v-model.number="newValue" @keyup="update"/>

        <div class="mpbtn plus" v-on:click="mpplus()">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: "numberSpin",
        props: {
            value: {
                default: 0,
                type: Number
            },
            min: {
                default: 0,
                type: Number
            },
            max: {
                default: undefined,
                type: Number
            }
        },
        data() {
            return {
                newValue: 0
            }
        },
        computed: {},
        watch: {
            value: {
                handler: function (newVal, oldVal) {
                    this.newValue = +(String(newVal).replace(/\D.\D/g, '').trim());
                }
            },
            newValue: {
                handler: function (val) {
                    this.newValue = +(String(val).replace(/\D.\D/g, '').trim())
                }
            }
        },
        methods: {
            mpplus: function() {
                if(this.max == undefined || (this.newValue < this.max)) {
                    this.newValue = this.newValue + 1;
                    this.$emit('input', this.newValue);
                }
            },
            mpminus: function() {
                if((this.newValue) > this.min) {
                    this.newValue = this.newValue - 1;
                    this.$emit('input', this.newValue);
                }
            },
            update(e) {
                this.newValue = parseInt(e.target.value)
                this.$emit('input', this.newValue)
            }
        },
        created: function() {
            this.newValue = this.value;
        },
    }
</script>


<style lang="scss">
    $height: 30px;

    .minusplusnumber {
        border-radius: 5px;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        user-select: none;
        height: $height;
        background: #e3e3e3;
        width: 140px;
    }
    .minusplusnumber > input {
        width: 80px;
        text-align: center;
        font-size: 15px;
        padding: 0;
        border: none;
        height: $height;
        line-height: $height;
        background: #ececec;
        -moz-appearance: textfield;
        &:focus, &:active{
            outline: none;
            box-shadow: none;
        }
        &::-webkit-outer-spin-button,
        &::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    }
    .minusplusnumber .mpbtn {
        font-family: Arial, Helvetica, sans-serif;
        cursor: pointer;
        border-radius:5px;
        font-weight: normal;
        font-size: 14px;
        height: $height;
        width: $height;
        line-height: $height;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        flex:none;
        &:hover {
            background-color:#DDD;
        }
        &:active {
            background-color:#c5c5c5;
        }
    }
</style>






