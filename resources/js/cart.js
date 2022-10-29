const $ = require('jquery');
const axios = require('axios');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default class Cart {
    constructor(options) {
        this.default_options = {
            name: 'basket',
            id_data_attribute: `data-item-id`,
            timeout: 500,
            animation: 'rubberBand',
        }

        this.options = $.extend({}, this.default_options, options);

        this.options.selector = this.options.name;
        this.options.triggers_selector = `.${this.options.name}_add`

        this.cart = $(this.options.selector);

        this.urls = {
            'add': window.location.origin + '/cart',
            'remove': window.location.origin + '/cart/' + this.options.name,
            'update': window.location.origin + '/cart/' + this.options.name,
        }

        this.init();
    }

    init() {
        $(this.options.triggers_selector).each((i, trigger) => {
            console.log(trigger)
            $(trigger).click(() => {
                const item_id = $(trigger).attr(this.options.id_data_attribute);

                $(trigger).hasClass('added') ? this.removeItem(item_id) :  this.addItem(item_id);
            })
        })
    }

    playAnimation() {
        const cart = this.cart;
        const animation = this.options.animation;
        console.log(cart)
        $(cart)
            .addClass('active')
            .addClass('animate__animated')
            .addClass(`animate__${animation}`);

        setTimeout(function () {
            $(cart)
                .removeClass('animate__animated')
                .removeClass(`animate__${animation}`);
        }, this.options.timeout);
    }

    addItem(item_id) {
        axios.post(this.urls.add, {id: item_id, cart_name: this.options.name}).then((rs) => {
            console.log(rs)
            console.log(this)
            this.playAnimation();
        })
    }

    removeItem(item_id) {
        axios.delete(this.urls.remove + item_id).then((rs) => {
            console.log(rs)
            console.log(this)
            this.playAnimation();
        })
    }

    updateItem(item_id) {
        axios.put(this.urls.remove + item_id).then((rs) => {
            console.log('updated');
        })
    }
}
