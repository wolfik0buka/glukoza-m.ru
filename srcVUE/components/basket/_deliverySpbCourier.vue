<template>
    <div>

        <div class="basket__formItem">
            <div class="basket__formLabel">
                Адрес доставки
            </div>
            <div class="basket__formContainer">
                <textarea rows="8"
                          class="basket__formField textfield fullwidth"
                          @keyup="$store.dispatch('updateOrder', order)"
                          v-model="order.adr">
                </textarea>
                <div class="basket__formHelp">
                    Улица, дом, корпус, квартира/офис
                </div>
            </div>
        </div>

        <div v-show="order.delivery===3" class="basket__formItem">
            <div class="basket__formLabel">Желаемая дата получения</div>
            <input type="text" id="date_of_delivery" class="basket__formField expanded" v-model="orderDate">
        </div>

    </div>
</template>

<script>
    module.exports = {
        name: 'basketDeliverySpbCourier',
        computed: {
            order: {
                get() { return this.$store.getters.order },
                set() { this.$store.dispatch('updateOrder', this.order) }
            },
            orderDate: {
                get() {
                    if (this.order.date_of_delivery) {
                        return moment(this.order.date_of_delivery).format("DD.MM.YYYY")
                    } else {
                        return moment(this.getFirstAvailableDate.toDate()).format("DD.MM.YYYY")
                    }
                },
                set(val) {
                    this.order.date_of_delivery = moment(val).format("YYYY-MM-DD HH:mm:ss")
                    this.$store.dispatch('updateOrder', this.order)
                }
            },
            now() {
                return moment(new Date).clone()
            },
            getFirstAvailableDate() {
                let response
                switch (this.now.day()) {
                    case 0: // воскресенье
                        response = this.now.hour() > 17 ? this.now.add(1, 'd') : this.now.add(1, 'd')
                        break;
                    case 1: // понедельник
                        response = this.now.hour() > 17 ? this.now.add(1, 'd') : this.now.add(1, 'd')
                        break;
                    case 2: // вторник
                        response = this.now.hour() > 17 ? this.now.add(1, 'd') : this.now.add(1, 'd')
                        break;
                    case 3: // среда
                        response = this.now.hour() > 17 ? this.now.add(1, 'd') : this.now.add(1, 'd')
                        break;
                    case 4: // четверг
                        response = this.now.hour() > 17 ? this.now.add(1, 'd') : this.now.add(1, 'd')
                        break;
                    case 5: // пятница
                        response = this.now.hour() > 17 ? this.now.add(1, 'd') : this.now.add(1, 'd')
                        break;
                    case 6: // суббота
                        response = this.now.hour() > 17 ? this.now.add(1, 'd') : this.now.add(1, 'd')
                        break;
                }
                return response
            },
        },
        methods: {
            isNotAvailable(date) {
                return false
                //return [6, 0].indexOf(moment(date).day()) !== -1
            },
            initDatepicker() {

                    let deliveryPicker = $('input#date_of_delivery').datepicker({
                        minDate: this.getFirstAvailableDate.toDate(),
                        dateFormat: "dd.mm.yyyy",
                        autoClose: true,
                        position: "bottom center",
                        toggleSelected: false,
                        onRenderCell: (date, cellType) => {
                            if (cellType === 'day') {
                                return {
                                    disabled: this.isNotAvailable(date)
                                }
                            }
                        },
                        onSelect: (formattedDate, date) => { this.orderDate = date }
                    })
                    .data('datepicker')
                    .selectDate(this.orderDate);
                }

        },
        mounted() {
            if (!this.order.time_intervals) {
                this.order.time_intervals = "Доставка до 16:00"
            }
            this.initDatepicker()
        }
    }
</script>
