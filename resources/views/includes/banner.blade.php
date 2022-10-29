<div class="banner" style="background-image: url({{ asset('assets/img/bg.webp') }})">
    <div class="banner_center">
        <div class="banner_title">
            {{__('home.banner.title')}}
        </div>
        <div class="banner_text">{{__('home.banner.description')}}</div>
        <div class="banner_icon">
            @include('includes.svg', ['name' => 'scroll'])
            @include('includes.svg', ['name' => 'down'])
        </div>
    </div>
    <div class="banner_overflow"></div>
</div>
