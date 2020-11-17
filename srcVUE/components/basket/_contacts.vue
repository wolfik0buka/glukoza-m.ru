<template>
    <div>
        <div v-if="order" class="card basket__panel bottom-20">
            <div class="panel-body">

                <h2>Контактная информация</h2>

                <div class="basket__formItem top-30">
                    <div class="basket__formLabel">Фамилия, Имя</div>
                    <input
                        type="text"
                        name="username"
                        class="basket__formField"
                        @keyup="updateOrder"
                        v-model="order.fio">
                </div>

                <div class="basket__formItem">
                    <div class="basket__formLabel">Телефон</div>
                    <div class="basket__formContainer">
                        <input
                            type="text"
                            name="phone"
                            class="basket__formField fullwidth"
                            v-model.trim="order.phone"
                            @keyup="phoneNotValid=false"
                            @blur="setPhone(order.phone)">
                        <div v-if="!phoneNotValid" class="basket__formHelp">Для уведомлений о статусе заказа</div>
                        <div v-if="phoneNotValid" class="font-s14 text-danger">Возможно телефон указан с ошибкой</div>
                    </div>
                </div>

                <div class="basket__formItem">
                    <div class="basket__formLabel">Адрес e-mail</div>
                    <div class="basket__formContainer">
                        <input
                            type="text"
                            name="email"
                            class="basket__formField fullwidth"
                            v-model="order.email"
                            @keyup="setEmail(order.email)">
                        <div class="basket__formHelp">
                            Мы пришлем на него подтверждение заказа
                        </div>
                    </div>
                </div>

                <div class="basket__formItem">
                    <div class="basket__formLabel">Комментарий к заказу</div>
                    <textarea
                            class="basket__formField textfield"
                            @keyup="updateOrder"
                            v-model="order.comment">
                    </textarea>
                </div>
    
                <div class="form-group">
                    <div class="checkbox">
                        <label class="font-s14">
                            <input type="checkbox"
                                :value="true"
                                @change="$store.dispatch('updateOrder', order)"
                                v-model="order.privacy_agreement">
                            Согласен на <a target="_blank" href="/confirm">обработку персональных данных</a>
                        </label>
                    </div>
                    <div v-if="!order.user_id" class="checkbox">
                        <label class="font-s14">
                            <input
                                type="checkbox"
                                v-model="isCreateUserAccount">
                            Зарегистрироваться и получить бонусы за заказ по <a href="/index.php?page=stat&alias=bonus">программе лояльности</a>
                        </label>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'basketContacts',
        data(){
            return{
                phoneNotValid: false,
                isCreateUserAccount: true,
            }
        },
        computed: {
            order: {
                get() { return this.$store.getters.order },
                set(val) {
                    this.order = val
                    this.updateOrder()
                }
            },
        },
        methods: {
            updateOrder() {
                this.$store.dispatch('updateOrder', this.order)
            },
            setEmail(email) {
                this.order.email = email.replace(/\s+/g, '')
                this.updateOrder()
            },
            setPhone(phone) {
                this.order.phone = this.formatPhone(phone)
                this.isValidPhone(this.order.phone)
                this.updateOrder()
            },
            isValidPhone(phone) {
                let isValid = App.isValidPhone(phone)
                this.phoneNotValid = !isValid
                return isValid
            },
            formatPhone(phone) {
                return App.formatPhone(phone)
            }
        },
        created() {}
    }
</script>
