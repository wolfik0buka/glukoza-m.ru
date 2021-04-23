module.exports = {
    state: {
        orderDone: false,
        bonusBalance: 0,
        isLoading: false,
        isCreateUserAccount: true,
        order: {
            products: [],
            user_id: false,
            payment_type_id: 1,
            bonus: 0,
            fio: '',
            phone: '',
            email: '',
            comment: '',
            adr: '',
            dop_fld: null,
            date_of_delivery: null,
            time_intervals: null,
            delivery: null,
            delivery_is_free: null,
            delivery_price: 0,
            delivery_days: null,
            deliveryPoint: false,
            city_id: null,
            city_name: null,
            post_index: null,
            activeCity: null,
            privacy_agreement: true,
        },
        deliveries: [
            {
                id: 11,
                name: 'Самовывоз из пункта выдачи',
                shop_address: false,
                initial_price: 0,
                is_free: false
            },
            {
                id: 2,
                name: 'Доставка курьером по РФ',
                shop_address: false,
                initial_price: 0,
                is_free: false
            },
            {
                id: 4,
                name: 'Доставка почтой по РФ',
                shop_address: false,
                initial_price: 450,
                is_free: false
            },
            {
                id: 5,
                name: 'Самовывоз из магазина',
                shop_address: 'пр. Сизова дом 2, «Простор».',
                initial_price: 0,
                is_free: true
            },
            {
                id: 1,
                name: 'Самовывоз из офиса',
                shop_address: 'м. Комендантский пр-т, Стародеревенская 36, ТК «Крокус», 2 этаж, секция 37',
                initial_price: 0,
                is_free: true
            },
            {
                id: 3,
                name: 'Доставка курьером по Санкт-Петербургу',
                shop_address: false,
                initial_price: 300,
                is_free: false
            },
            {
                id: 14,
                name: 'Экспресс доставка курьером (день в день)',
                shop_address: 'При оформлении заказа до 16 часов',
                initial_price: 450,
                is_free: false
            },
        ],
        validationNames: {
            activeCity: 'город',
            delivery:'способ получения',
            deliveryPoint: 'точку самовывоза',
            city_id: 'город доставки',
            adr: 'адрес доставки',
            date_of_delivery: 'желаемую дату получения',
            post_index: 'почтовый индекс',
            fio: 'фамилию и имя',
            phone: 'телефон',
            email: 'email',
            privacy_agreement: 'согласиться с правилами обработки персональных данных'
        }
    },
    mutations: {
        initOrder(state, order) {
            state.order = order
        },
        addProduct(state, product) {
            let localProduct = _.find(state.order.products, item => item.id == product.id)

            if (localProduct) {
                localProduct.amount += 1
            } else {
                product.amount = 1
                state.order.products.push(product)
            }
        },
        removeProduct(state, product) {
            state.order.products.splice(state.order.products.indexOf(product), 1)
            if (state.order.products.length == 0) {
                state.order.delivery = false
                state.order.delivery_price = 0
                state.order.bonus = 0
            }
        },
        updateProduct(state, product) {
            state.order.products[state.order.products.indexOf(product)] = product
        },
        setOrderDone(state, val) {
            state.orderDone = val
        },
        setBonusBalance(state, val) {
            state.bonusBalance = val
        },
        setUser(state, val) {
            state.order.user_id = val
        },
        setLoading(state, val) {
            state.isLoading = val
        },
        clearBasket(state) {
            state.order.products = []
            state.order.delivery = null
            state.order.delivery_price = 0
            state.order.bonus = 0
        }
    },
    actions: {
        addProductToOrder(store, product) {
            store.commit('addProduct', product)
            store.dispatch('updateOrder')
            store.dispatch('updateDelivery')
        },
        removeProductFromOrder(store, product) {
            store.commit('removeProduct', product)
            store.dispatch('updateOrder')
        },
        updateOrder(store, order = false) {
            if (order) store.commit('initOrder', order);
            store.dispatch('saveOrder');
        },
        saveOrder: _.debounce( function(store) {
            axios.post("/checkout/update", {
                order: store.state.order,
                isCreateUserAccount: store.state.isCreateUserAccount || false,
            }).then(response => {
                console.log('order updated');
            });
        }, 500),
        doneOrder(store) {
            store.commit('setLoading', true);
            axios.post("/checkout/done", {
                order: store.state.order,
                isCreateUserAccount: store.state.isCreateUserAccount || false,
            }).then( response => {
                    store.commit('setOrderDone', true);
                    store.commit('setLoading', false);
                    store.commit('clearBasket');
                    store.dispatch('saveOrder');
                });
            _ym.goal('sendorder');
        },
        setUser(store, val) {
            if (!store.state.order.user_id) {
                store.commit('setUser', val);
            }
        },
        updateDelivery(store) {
            let order = store.state.order;
            let isSpb = order.activeCity === 1;

            // пункты самовывоза спб
            if (isSpb && order.delivery === 11) {
                if (order.payment_type_id === 2) {
                    // при оплате картой
                    order.delivery_price = 0;
                    order.delivery_is_free = true;
                } else {
                    order.delivery_price = 150;
                    order.delivery_is_free = false;
                }
                order.city_id = 137;
                order.city_name = 'Санкт-Петербург';
            }
            // ПВЗ РФ
            else if (!isSpb && order.delivery === 11) {

            }
            // другие виды доставки
            else {
                order.delivery_price = order.delivery ? store.getters.deliveryModel(order.delivery).initial_price : null;
                order.delivery_is_free = order.delivery ? store.getters.deliveryModel(order.delivery).is_free : false;
            }

            store.dispatch('updateOrder', order);
        }
    },
    getters: {
        order(state) {
            return state.order;
        },
        orderDone(state) {
            return state.orderDone;
        },
        deliveryModel: (state) => (delivery_id) => {
            return _.find(state.deliveries, {id: delivery_id})
        },
        orderProductsSum(state) {
            if (!_.isEmpty(state.order.products)) {
                return _.reduce(state.order.products, function(sum, item) {
                    return sum + (item.price * item.amount)
                }, 0)
            } else {
                return 0
            }
        },
        orderProductsCount(state) {
            if (!_.isEmpty(state.order.products)) {
                return state.order.products.length;
            }
        },
        orderFullSum(state, getters) {
            let sum = +getters.orderProductsSum

            if (parseInt(state.order.delivery_price) > 0) {
                sum += +state.order.delivery_price
            }
            if (parseInt(state.order.bonus) > 0) {
                sum -= +state.order.bonus
            }
            return sum
        },
        validation(state) {
            let errors = []

            if (!state.order.activeCity) { errors.push('activeCity') }
            else if (state.order.activeCity === 1) {
                if (!state.order.delivery) { errors.push('delivery') }
                else if (state.order.delivery === 3) {
                    if (!state.order.date_of_delivery) { errors.push('date_of_delivery') }
                    if (!state.order.adr || state.order.adr.length < 5) { errors.push('adr') }
                }
            } else if (state.order.activeCity === 2) {
                if (!state.order.delivery) { errors.push('delivery') }
            } else if (state.order.activeCity === 3) {
                if (!state.order.delivery) { errors.push('delivery') }
                else if (state.order.delivery === 2) {
                    if (!state.order.city_id || !state.order.delivery_price) { errors.push('city_id') }
                    if (!state.order.adr || state.order.adr.length < 5) { errors.push('adr') }
                }
                else if (state.order.delivery === 4) {
                    if (!state.order.post_index) { errors.push('post_index') }
                    if (!state.order.adr || state.order.adr.length < 5) { errors.push('adr') }
                }
                else if (state.order.delivery === 11) {
                    if (!state.order.city_id) { errors.push('city_id') }
                    if (!state.order.deliveryPoint) { errors.push('deliveryPoint') }

                }
            }
            if (!state.order.fio || state.order.fio.length < 3) { errors.push('fio') }
            if (!state.order.phone || !App.isValidPhone(state.order.phone)) { errors.push('phone') }
//            if (!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(state.order.email))) { errors.push('email') }
            if (!state.order.privacy_agreement) { errors.push('privacy_agreement') }

            return errors
        },
        validationResultString(state, getters) {
            let errorsString = _.map(getters.validation, error => {
                return state.validationNames[error]
            }).join(', ')

            return errorsString.charAt(0).toUpperCase() + errorsString.slice(1);
        }
    }
};