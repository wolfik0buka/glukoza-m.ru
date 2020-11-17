<template>
    <div>

        <div v-if="!isSuccess">

            <div class="fast-checkout">
                <div class="title">
                    Оставьте только имя и телефон, мы перезвоним вам и уточним детали
                </div>
                <div class="basket__formItem">
                    <div class="basket__formLabel">Телефон</div>
                    <div class="basket__formContainer">
                        <input
                          type="text"
                          name="phone"
                          id="fastcheckout_phone"
                          @keyup="phoneNotValid=false"
                          @blur="setPhone(order.phone)"
                          class="basket__formField fullwidth"
                          v-model.trim="order.phone">
                        <div v-if="!phoneNotValid" class="basket__formHelp">Для уведомлений о статусе заказа</div>
                        <div v-if="phoneNotValid" class="font-s14 text-danger">Возможно телефон указан с ошибкой</div>
                    </div>
                </div>
                <div class="basket__formItem">
                    <div class="basket__formLabel">Фамилия, имя</div>
                    <div class="basket__formContainer">
                        <input
                          name="name"
                          autocomplete="name"
                          type="text"
                          class="basket__formField fullwidth"
                          @keyup="$store.dispatch('updateOrder', order)"
                          v-model="order.fio">
                        <div class="basket__formHelp">
                            Как к вам обратиться?
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group bottom-20">
                <div class="checkbox">
                    <label class="font-s14">
                        <input type="checkbox"
                               :value="true"
                               @change="$store.dispatch('updateOrder', order)"
                               v-model="order.privacy_agreement">
                        Согласен на <a target="_blank" href="/confirm">обработку персональных данных</a>
                    </label>
                </div>
            </div>

            <div v-if="isErrorValidation" class="alert alert-danger">
                <span v-if="!validate().phone">Укажите правильный телефон</span>
                <span v-if="!validate().agreement">Необходимо ваше согласие с условиями обработки персональных данных</span>
            </div>

            <button v-if="!isLoading"
                    @click="checkout()"
                    class="basket__checkoutButton">Оформить заказ</button>

            <button v-if="isLoading"
                    class="basket__checkoutButton disabled">Сохраняем..</button>

        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'FastCheckout',
        data(){
            return {
                isSuccess: false,
                isErrorValidation: false,
                phoneNotValid: false
            }
        },
        computed: {
            order() {
                return this.$store.getters.order
            },
            isLoading() {
                return this.$store.state.basketStore.isLoading
            }
        },
        methods: {
            validate(){
                let result = {
                    agreement: this.$store.getters.validation.indexOf('privacy_agreement') == -1,
                    phone: this.isValidPhone(this.order.phone)
                }
                this.isErrorValidation = !result.agreement || !result.phone
                return result
            },
            resetValidate(){
                this.isErrorValidation = false
            },
            checkout() {
                if (this.validate().agreement && isValidPhone(this.order.phone)) {
                    this.$store.dispatch('doneOrder')
                    _ym.goal('fastcheckout')
                } else {
                    setTimeout(this.resetValidate, 2000)
                }
            },
            setPhone(phone) {
                this.order.phone = this.formatPhone(phone)
                this.isValidPhone(this.order.phone)
                this.$store.dispatch('updateOrder', this.order)
            },
            isValidPhone(phone) {
                let isValid = App.isValidPhone(phone)
                this.phoneNotValid = !isValid
                return isValid
            },
            formatPhone(phone) {
                return App.formatPhone(phone)
            }
        }
    }
</script>

<style lang="less">

    @import url('../../less/public/variables');

    .fast-checkout {
        background: white;
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        overflow: hidden;
        .title {
            font-weight: 400;
            font-size: 16px;
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #fafafa;
        }
        p{
            padding-bottom: 10px;
        }
        .alert.alert-danger{
            padding: 5px 10px;
            text-align: center;
        }
        .success{
            text-align: center;
            .success_title{
                font-weight: 500;
                font-size: 24px;
                padding-bottom: 15px;
            }
            .success_text{
                font-size: 17px;
            }
        }

    }
</style>