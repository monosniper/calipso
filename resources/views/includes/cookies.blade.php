<div class="cookies">
    <div class="cookies_popup">
        <div class="cookies_image">
            @include('includes.svg', ['name' => 'cookie'])
        </div>
        <div class="cookies_text">
            {{__('main.cookies')}}
        </div>
        <div class="cookies_footer">
            <button class="cookies_btn">{{__('main.ok')}}</button>
        </div>
    </div>
    <div class="cookies_overflow"></div>
</div>
