<div class="modal_wrapper" id="sign">
    <div class="modal">
        <div class="modal_header">
            <div class="modal_logo">
                @include('includes.svg', ['name' => 'logo'])
            </div>
        </div>
        <hr>
        <div class="sign_change_type">
            <div class="sign_change_type_item active" data-form-name="sign_in">{{__('modals.sign.login')}}</div>
            <div class="sign_change_type_item" data-form-name="sign_up">{{__('modals.sign.register')}}</div>
        </div>
        <div class="flex-center">
            <div class="webz_underline">
                <span class="pink"></span>
                <span class="grey"></span>
            </div>
        </div>
        @if ($errors->any())
            <div class="font-medium text-red-600">
                {{ __('Whoops! Something went wrong.') }}
            </div>

            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        @endif
        <div class="sign_in_form form active" data-form-name="sign_in">
            <form action="{{ route('login') }}" method="post" class="modal_form">
                @csrf
                <input type="text" required name="email" value="{{ request()->old('email') }}"
                       placeholder="{{__('modals.sign.email')}}:" class="modal_field">
                <input type="password" required name="password" placeholder="{{__('modals.sign.password')}}:" class="modal_field">
                <button class="submit" type="submit"></button>
            </form>
        </div>

        <div class="sign_up_form form" data-form-name="sign_up">
            <form action="{{ route('register') }}" method="post" class="modal_form">
                @csrf
                <input type="email" name="email" value="{{ request()->old('email') }}" required placeholder="E-mail:"
                       class="modal_field">

                <input type="password" name="password" required placeholder="{{__('modals.sign.password')}}:" class="modal_field">
                <input type="password" name="password_confirmation" required placeholder="{{__('modals.sign.password_again')}}:"
                       class="modal_field">
                <button class="submit" type="submit"></button>
            </form>
        </div>

        <div class="modal_footer">
            <button class="webz_btn confirm_btn">{{__('main.ready')}}</button>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.cancel')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
