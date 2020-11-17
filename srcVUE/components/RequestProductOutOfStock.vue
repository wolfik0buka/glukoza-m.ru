<template>
    <div class="requestOutOfStock arial" v-cloak>
        
        <div
            class="loading"
            v-if="isLoading"
            v-cloak>
        </div>
        
        <div
            v-cloak
            v-if="isDone">
            <div class="requestOutOfStock__title">Спасибо!</div>
            <p>Мы уточним детали по данному товару и свяжемся с вами в ближайшее время.</p>
        </div>
        
        <div v-if="!isLoading && !isDone">
            <div class="requestOutOfStock__title bottom-10">Предзаказ</div>
            <div class="form-text bottom-15 font-lh130">
                Оставьте заявку и мы сообщим вам сроки поступления товара и точную цену.
            </div>
            
            <div class="requestOutOfStock__form">
    
    
                <div class="requestOutOfStock__form__row">
                    <div class="requestOutOfStock__form__label">Телефон</div>
                    <input
                        type="tel"
                        name="phone"
                        id="requestOutOfStock__tel"
                        class="form-control"
                        autocomplete="tel-national"
                        v-model="order.phone">
                </div>
                
                <div class="requestOutOfStock__form__row">
                    <div class="requestOutOfStock__form__label">Имя</div>
                    <input
                        type="text"
                        name="name"
                        id="requestOutOfStock__name"
                        class="form-control"
                        placeholder="Не обязательно"
                        autocomplete="name"
                        v-model="order.fio">
                </div>
                
                <div
                    class="text-danger font-s13"
                    v-if="isError">
                    {{ errorMessage }}
                </div>
                
                <!--<div class="requestOutOfStock__form__row">-->
                <!--<div class="requestOutOfStock__form__label">Email</div>-->
                <!--<input-->
                <!--type="email"-->
                <!--name="email"-->
                <!--id="requestOutOfStock__email"-->
                <!--autocomplete="email"-->
                <!--class="form-control"-->
                <!--v-model="order.email">-->
                <!--</div>-->
                
                <button
                    @click=validate
                    class="btn btn-primary btn-block btn-lg top-5"
                    type="button">
                    Отправить
                </button>
            
            </div>
        </div>
    
    </div>
</template>

<script>
    export default {
        name: "RequestProductOutOfStock",
        props: [
            "id", // id товара
        ],
        data() {
            return {
                isLoading: false,
                isDone: false,
                isError: false,
                errorMessage: false,
                order: {
                    fio: "",
                    //email: '',
                    phone: "+7",
                    product_id: this.id,
                },

            }
        },
        computed: {
            isPhoneValid() {
                return App.isValidPhone(this.order.phone);
            }
        },
        methods: {
            validate() {
                if (this.isPhoneValid) {
                    this.sendPreorder();
                } else {
                    this.isError = true;
                    this.errorMessage = "Проверьте правильность введенного телефона";
                }
            },
            sendPreorder() {
                this.order.phone = App.formatPhone(this.order.phone);
                this.isLoading = true;
                
                axios.post("/checkout/preorder", this.order).then(() => {
                    this.isLoading = false;
                    this.isDone = true;
                });
            }
        }
    };
</script>

<style lang="scss">
    .requestOutOfStock {
        height: 100%;
        margin: 0;
        padding-top: 5px;
        border: 0;
        
        &__title {
            font-style: normal;
            font-weight: 600;
            font-size: 22px;
            color: #333;
            line-height: 120%;
            margin-bottom: 10px;
        }
        &__form {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            margin-bottom: 2px;
            align-items: flex-start;
            &__row {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 10px;
            }
            &__label {
                flex: none;
                width: 90px;
            }
            .form-control {
                width: 100%;
                margin-right: 10px;
                padding-left: 10px;
            }
            button{
                margin-bottom: 0;
            }
        }
    }
</style>