
export default function orders(state) {
    if (!state.orders) {
        return false;
    }

    let orders = _.cloneDeep(state.orders);

    let isFinded = (string) => {
        if (string !== null && typeof string === "string") {
            return  string.toLowerCase().indexOf(state.filters.query) >= 0;
        }
        return false;
    };

    let isOrderPass = (order) => {
        let pass = true;

        if (state.filters.showDoneOrder === 0 && order.is_done === 1 ) {
            pass = false;
        }

        if (pass && state.filters.doer !== false) {
            let filterDoer = _.find(state.doers, { id: state.filters.doer });
            pass = filterDoer.is(order.manager_id);
        }

        if (pass && state.filters.leadType !== false) {
            let filterLeadType = _.find(state.leadTypes, { id: state.filters.leadType });

            // заказы
            if (filterLeadType.id === 0) {
                pass = order.is_preorder === 0;
            }

            // заявки
            if (filterLeadType.id === 1) {
                pass = order.is_preorder === 1;
            }

            // заказы с Онмеди
            if (filterLeadType.id === 2) {
                pass = order.onmedi_order_id > 0;
            }

        }

        if (pass && state.filters.delivery !== false) {
            if (order.delivery !== state.filters.delivery) {
                pass = false ;
            }
        }

        if (state.filters.query && pass === true) {
            if (state.filters.query.length >= 3) {
                pass = isFinded(order.fio) ||
                    isFinded(order.email) ||
                    (order.onmedi_order_id > 0 ? isFinded(order.onmedi_order_id+'') : false) ||
                    isFinded(order.number_formatted) ||
                    isFinded(order.phone) ||
                    (typeof order.comment_my !== "object" ? isFinded(order.comment_my) : false);
            }
        }

        return pass;
    };

    return _.map(orders, month => {
        month.days = _.map(month.days, day => {
            day.orders = _.filter(day.orders, order => {
                return isOrderPass(order)
            })
            return day
        }).reverse()
        return month
    }).reverse()
}
