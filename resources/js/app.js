import $ from "jquery";

require('./bootstrap');
require('./cookies');

import Modal from './modal';
import Dropdown from './dropdown';
import Cart from './cart';
import Alpine from 'alpinejs';
import raterJs from 'rater-js';

Alpine.start();

$(document).ready(() => {

    $('.open_modal').each((i, open_modal_btn) => {
        let options = {modal_wrapper: $(open_modal_btn).attr('modal-wrapper')}
        const template = $($(open_modal_btn).attr('modal-wrapper'));

        if($(template).is('[modal-animation]')) options.animation = $(template).attr('modal-animation');
        if($(template).is('[modal-dismiss-animation]')) options.dismiss_animation = $(template).attr('modal-dismiss-animation');
        if($(template).is('[modal-animation-timeout]')) options.timeout = $(template).attr('modal-animation-timeout');

        let modal = new Modal(options);

        $(open_modal_btn).click(() => {
            modal.show();
        })
    })

    if($('#rater').length) {
        let rewiew_modal = new Modal({
            modal_wrapper: '#add-review',
            form: false,
            callback: (modal) => {
                const raterEl = modal.get()[0].querySelector("#rater");
                const rater = raterJs( {
                    element: raterEl,
                    rateCallback:function rateCallback(rating, done) {
                        this.setRating(rating);
                        $(modal).find('#rating').val(rating);
                        done();
                    }
                });

                let ajax_add_review_form = ajaxable('#add-review .modal_form')
                    .onStart(function (params) {
                        $(modal).find('.confirm_btn').html('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>');
                    })
                    .onEnd(function (params) {
                        $(modal).find('.confirm_btn').html('Готово');
                    })
                    .onResponse(function (res, params) {
                        if (params.req.status === 403 || params.req.status === 401) {
                            $(modal).find('form .modal_errors').remove();

                            let errors_block = document.createElement('ul');
                            $(errors_block).addClass('modal_errors');

                            let error = document.createElement('li');
                            $(error).addClass('modal_errors_item');
                            $(error).text(res.message);

                            $(errors_block).append(error);

                            $(modal).find('form').prepend(errors_block);
                        }
                    })
                    .onError((err, params) => {
                        window.location.reload();
                    });

                $(modal).find('.confirm_btn').click(() => ajax_add_review_form.submit());

                return modal;
            }
        })
        $('.add_review').each((i, btn) => $(btn).click(() => rewiew_modal.show()));
    }

    if ($('.sign_btn').length) {
        let sign_modal = new Modal({
            modal_wrapper: '#sign',
            form: false,
            callback: (modal) => {
                let sign_change_form = modal.find('.sign_change_type');

                $(sign_change_form).find('.sign_change_type_item').each((i, item) => {
                    $(item).click(() => {
                        if (!$(item).hasClass('active')) {
                            $(sign_change_form).find('.sign_change_type_item.active').removeClass('active');
                            $(item).addClass('active');

                            let form_name = $(item).attr('data-form-name');

                            $(modal).find('.form.active').removeClass('active');
                            $(modal).find(`.form[data-form-name=${form_name}]`).addClass('active');
                        }
                    })
                })

                let ajax_sign_in_form = ajaxable('#sign .sign_in_form form')
                    .onStart(function (params) {
                        $(modal).find('.confirm_btn').html('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>');
                    })
                    .onEnd(function (params) {
                        $(modal).find('.confirm_btn').html('Готово');
                    })
                    .onResponse(function (res, params) {
                        if (res.errors) {
                            $(modal).find('.sign_up_form form modal_errors').remove();

                            let errors_block = document.createElement('ul');
                            $(errors_block).addClass('modal_errors');

                            Object.entries(res.errors).forEach(([error_name, errors]) => {
                                errors.forEach(error_text => {
                                    let error = document.createElement('li');
                                    $(error).addClass('modal_errors_item');
                                    $(error).text(error_text);

                                    $(errors_block).append(error);
                                })
                            })

                            $(modal).find('.sign_in_form form').prepend(errors_block);
                        }

                    })
                    .onError((err, params) => {
                        window.location.reload();
                    });

                let ajax_sign_up_form = ajaxable('#sign .sign_up_form form')
                    .onStart(function (params) {
                        $(modal).find('.confirm_btn').html('<div class="lds-ring"><div></div><div></div><div></div><div></div></div>');
                    })
                    .onEnd(function (params) {
                        $(modal).find('.confirm_btn').html('Готово');
                    })
                    .onResponse(function (res, params) {

                        if (res.errors) {

                            $(modal).find('.sign_up_form form .modal_errors').remove();

                            let errors_block = document.createElement('ul');
                            $(errors_block).addClass('modal_errors');

                            Object.entries(res.errors).forEach(([error_name, errors]) => {

                                errors.forEach(error_text => {
                                    let error = document.createElement('li');
                                    $(error).addClass('modal_errors_item');
                                    $(error).text(error_text);

                                    $(errors_block).append(error);
                                })

                            })

                            $(modal).find('.sign_up_form form').prepend(errors_block);
                        }
                    })
                    .onError((err, params) => {
                        window.location.reload();
                    });

                $(modal).find('.confirm_btn').click(() => {
                    if ($(modal).find('.sign_in_form.active').length) {
                        ajax_sign_in_form.submit();
                    } else {
                        ajax_sign_up_form.submit();
                    }
                })

                return modal;
            }
        });

        // Sign modal
        $('.sign_btn').each((i, btn) => {
            $(btn).click(() => {
                sign_modal.show();
            })
        })
    }

    let dropdowns = [];
    $('.dropdown').each((i, dropdown) => {
        dropdowns.push(new Dropdown({dropdown}));
    });

    dropdowns.forEach(dropdown => {
        dropdown.menu.get()[0].addEventListener('show', () => {
            dropdowns.forEach(_dropdown => {
                if(dropdown !== _dropdown)
                    _dropdown.hide();
            })
        })
    })

    if ($('.header').hasClass('scroll')) {
        window.addEventListener('scroll', function (e) {
            if (window.scrollY > 200) {
                $('.header').addClass('header_dark');
            } else {
                $('.header').removeClass('header_dark');
            }
        })
    }

    // Header collapse
    $('.header_collapse').click(() => {
        $('.header_collapse').toggleClass('active');
        $('.header_collapse_content').toggle(200);
    })
})
