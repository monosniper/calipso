const $ = require('jquery');

export default class Dropdown {
    constructor(options) {
        this.default_options = {
            trigger: '.dropdown_toggler',
            menu: '.dropdown_menu',
            timeout: 500,
            animation: 'flipInX',
            animation_dismiss: 'flipOutX',
        }

        this.options = $.extend({}, this.default_options, options);

        this.trigger = $(this.options.dropdown).find(this.options.trigger);
        this.menu = $(this.options.dropdown).find(this.options.menu);
        self.canHide = false;

        this.init();
    }

    init() {
        $(this.trigger).click(() => {
            if ($(this.menu).hasClass('active')) {
                this.hide();
            } else {
                this.show();
            }
        })

        $(window).click((event) => {
            if(self.canHide) {
                $(this.menu).hasClass('active') && this.hide();
            }
        })
    }

    setCanHide(bool = true) {
        self.canHide = bool;
    }

    dispatchEvent(name) {
        const event = new Event(name);
        this.menu.get()[0].dispatchEvent(event);
    }

    show() {
        let menu = this.menu;
        let options = this.options;
        const setCanHide = this.setCanHide;

        this.dispatchEvent('show');

        setCanHide(false);

        $(this.menu)
            .addClass('active')
            .addClass('animate__animated')
            .addClass(`animate__${this.options.animation}`);

        setTimeout(function () {
            $(menu)
                .removeClass('animate__animated')
                .removeClass(`animate__${options.animation}`);

            setCanHide();
        }, this.options.timeout);
    }

    hide() {
        let menu = this.menu;
        let options = this.options;

        this.dispatchEvent('hide');

        $(this.menu)
            .addClass('animate__animated')
            .addClass(`animate__${this.options.animation_dismiss}`);

        setTimeout(function () {
            $(menu)
                .removeClass('active')
                .removeClass('animate__animated')
                .removeClass(`animate__${options.animation_dismiss}`);
        }, this.options.timeout);
    }
}
