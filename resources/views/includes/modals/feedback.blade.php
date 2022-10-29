<div class="modal_wrapper" id="feedback">
    <div class="modal" style="background-image: url('{{ asset('assets/img/feedback.png') }}')">
        <div class="modal_logo">
            @include('includes.svg', ['name' => 'logo'])
        </div>
        <hr>
        <div class="modal_title">{{__('main.feedback')}}</div>
        <div class="flex-center">
            <div class="webz_underline">
                <span class="pink"></span>
                <span class="grey"></span>
            </div>
        </div>
        <div class="modal_text">{{__('modals.feedback.answer')}}</div>
        <form action="{{route('forms.feedback')}}" method="post" class="modal_form">
            @csrf
            <input value="{{auth()->check() ? auth()->user()->email : request()->old('email')}}" type="email" name="email" placeholder="E-Mail:" class="modal_field">
            <input value="{{request()->old('theme')}}" type="text" name="theme" placeholder="{{__('modals.feedback.theme')}}:" class="modal_field">
            <textarea name="content" placeholder="{{__('modals.feedback.message_text')}}" id="content" cols="30" rows="10" class="modal_field">{{request()->old('content')}}</textarea>
            <button class="submit" type="submit"></button>
        </form>
        <div class="modal_footer">
            <button class="webz_btn confirm_btn">{{__('main.send')}}</button>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.cancel')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
