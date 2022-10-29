const $ = require('jquery');
import { trans } from "matice";

export default class Modal {
    constructor(options) {
        this.default_options = {
            modal_selector: '.modal',
            timeout: 1000,
            animation: 'bounceInUp',
            dismiss_animation: 'zoomOutUp',
            form: true,

            resolve_btn_class: 'resolve_btn',
            delete_btn_class: 'delete_btn',
            dismiss_btn_class: 'dismiss_btn',
            confirm_btn_class: 'confirm_btn',

            delete_btn_selector: '.delete_btn',
            dismiss_btn_selector: '.dismiss_btn',
            confirm_btn_selector: '.confirm_btn',

            modal_wrapper_class: 'modal_wrapper',
            modal_class: 'modal',
            modal_title_class: 'modal_title',
            modal_text_class: 'modal_text',
            modal_footer_class: 'modal_footer',
            modal_button_class: 'webz_btn webz_btn_outline',

            custom: {
                id: null,
                title: null,
                buttons: null,
                text: null,
            },
        }

        this.RESOLVE_ROLE = 'resolve';
        this.DISMISS_ROLE = 'dismiss';
        this.DELETE_ROLE = 'delete';

        this.options = $.extend({}, this.default_options, options);

        this.roles = {
            [this.RESOLVE_ROLE]: this.options.resolve_btn_class,
            [this.DISMISS_ROLE]: this.options.dismiss_btn_class,
            [this.DELETE_ROLE]: this.options.delete_btn_class,
        };

        if(this.options.modal_wrapper) {
            this.modal = $(`${this.options.modal_wrapper} ${this.options.modal_selector}`);
            this.modal_wrapper = $(this.options.modal_wrapper);
        } else {
            this.custom = this.options.custom;

            let modal_wrapper = document.createElement('div');
            $(modal_wrapper).addClass(this.options.modal_wrapper_class);

            if(this.custom.id) {
                $(modal_wrapper).attr('id', this.custom.id);
            }

            let modal = document.createElement('div');
            $(modal).addClass(this.options.modal_class);

            $(modal_wrapper).append(modal);

            let modal_title = document.createElement('div');

            $(modal_title).addClass(this.options.modal_title_class);
            $(modal_title).text(this.custom.title);

            $(modal).append(modal_title);

            if(this.custom.text) {
                let modal_text = document.createElement('div');

                $(modal_text).addClass(this.options.modal_text_class);
                $(modal_text).text(this.custom.text);

                $(modal).append(modal_text);
            }

            let modal_footer = document.createElement('div');
            $(modal_footer).addClass(this.options.modal_footer_class);

            $(modal).append(modal_footer);

            if(this.custom.buttons) {
                this.custom.buttons.forEach(btn => {
                    let button;

                    if(btn.form) {
                        let form = document.createElement('form');

                        $(form).attr('action', btn.form_action);
                        $(form).attr('method', 'post');

                        let form_csrf = document.createElement('input');
                        $(form_csrf).attr('name', '_token');
                        $(form_csrf).attr('type', 'hidden');
                        $(form_csrf).val($('meta[name="csrf-token"]').attr('content'));

                        let form_method = document.createElement('input');
                        $(form_method).attr('name', '_method');
                        $(form_method).attr('type', 'hidden');
                        $(form_method).val(btn.method);

                        let form_button = document.createElement('button');
                        $(form_button).addClass(this.options.modal_button_class);
                        $(form_button).text(btn.text);

                        $(form).append(form_csrf);
                        $(form).append(form_method);
                        $(form).append(form_button);

                        button = form;
                    } else {
                        button = document.createElement(btn.tag ? btn.tag : 'button');
                        $(button).addClass(this.roles[btn.role]);
                        $(button).addClass(this.options.modal_button_class);
                        $(button).text(btn.text);
                    }

                    $(modal_footer).append(button);
                })
            } else {
                let dismiss_button = document.createElement('button');
                $(dismiss_button).addClass(this.options.dismiss_btn_class);
                $(dismiss_button).text(trans('modals.ok'));

                $(modal_footer).append(dismiss_button);
            }

            this.modal = modal;
            this.modal_wrapper = modal_wrapper;

            $(document.body).append(modal_wrapper);
        }

        if (this.options.callback) {
            this.modal = this.options.callback(this.modal, this);
        }

        window.addEventListener('modal_called_'+this.options.modal_wrapper, () => {
            this.show();
        })

        this.init();
    }

    init() {
        $(this.modal).find(this.options.dismiss_btn_selector).click(() => {
            this.hide();
        })

        if (this.options.form) {
            $(this.modal).find(this.options.confirm_btn_selector).click(() => {
                $(this.modal).find('form .submit').click();
            });
        }

        $(this.modal).find(this.options.delete_btn_selector).click(() => {
            this.openQuestion(this.getButton(this.DELETE_ROLE).attr('data-link'));
        })
    }

    getButton(role) {
        return $(this.modal).find('.' + this.roles[role]);
    }

    openQuestion(link) {
        return new Promise((resolve, reject) => {
            const questionModal = new Modal({
                form: false,
                modal_wrapper: null,
                custom: {
                    title: trans('modals.sure'),
                    buttons: [
                        {
                            text: trans('modals.yes'),
                            role: this.RESOLVE_ROLE,
                            form: true,
                            form_action: link,
                            method: 'delete',
                        },
                        {
                            text: trans('modals.no'),
                            role: this.DISMISS_ROLE,
                        },
                    ],
                },
                callback: (modal, instance) => {
                    instance.getButton(this.RESOLVE_ROLE).attr('href', link);
                    instance.getButton(this.DISMISS_ROLE).click(reject);

                    return modal;
                }
            });

            return questionModal.show();
        })
    }

    show() {
        let modal = this.modal;

        $(this.modal_wrapper).addClass('active');

        $(modal).addClass('animate__animated')
            .addClass(`animate__${this.options.animation}`);

        setTimeout(function () {
            $(modal).attr('class', 'modal');
        }, this.options.timeout);
    }

    hide() {
        let modal = this.modal;
        let modal_wrapper = this.modal_wrapper;

        $(modal).addClass('animate__animated')
            .addClass(`animate__${this.options.dismiss_animation}`);

        setTimeout(function () {
            $(modal).attr('class', 'modal');
            $(modal_wrapper).removeClass('active');
        }, this.options.timeout);
    }

}
