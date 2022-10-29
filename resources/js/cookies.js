const $ = require('jquery');
const Cookies = require('js-cookie');

function cookiesNotice() {
    $('.cookies_btn').click(() => {
        Cookies.set('accept_cookies', true)

        $('.cookies_popup')
            .addClass('animate__animated')
            .addClass('animate__bounceOutUp');

        setTimeout(() => {
            $('.cookies_popup').attr('class', 'cookies_popup');
            $('.cookies').removeClass('active');
        }, 500)
    })

    setTimeout(() => {
        $('.cookies').addClass('active');

        $('.cookies_image')
            .addClass('animate__animated')
            .addClass('animate__bounceInUp');

        setTimeout(() => {
            $('.cookies_image').attr('class', 'cookies_image');

            $('.cookies_text')
                .addClass('active')
                .addClass('animate__animated')
                .addClass('animate__fadeInUp');

            $('.cookies_footer')
                .addClass('active')
                .addClass('animate__animated')
                .addClass('animate__fadeInUp');

            setTimeout(() => {
                $('.cookies_footer').attr('class', 'cookies_footer active');
                $('.cookies_text').attr('class', 'cookies_text active');
            }, 300)
        }, 500)
    }, 4000)
}

Cookies.get('accept_cookies') === undefined && cookiesNotice();
