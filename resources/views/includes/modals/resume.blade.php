<div class="modal_wrapper" id="resume">
    <div class="modal">
        <div class="modal_title">{{__($user->isFreelancer ? 'main.update_resume' : 'main.update_about_self')}}</div>
        <div class="flex-center">
            <div class="webz_underline">
                <span class="pink"></span>
                <span class="grey"></span>
            </div>
        </div>
        <form action="{{ route('forms.resume') }}" method="post" class="modal_form">
            @csrf
            <textarea name="content" placeholder="{{__($user->isFreelancer ? 'main.resume' : 'main.about_self')}}..." id="content" class="modal_field">{!! $user->resume !!}</textarea>
            <button class="submit" type="submit"></button>
        </form>
        <div class="modal_footer">
            <button class="webz_btn confirm_btn">{{__('main.ready')}}</button>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.cancel')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
