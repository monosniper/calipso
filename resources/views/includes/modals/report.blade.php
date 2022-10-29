<div class="modal_wrapper" id="report">
    <div class="modal">
        <div class="modal_title">{{__('main.report')}}</div>
        <form action="{{route('forms.report')}}" method="post" class="modal_form">
            @csrf
            <textarea name="reason" placeholder="{{__('modals.feedback.message_text')}}" id="reason" cols="30" rows="10" class="modal_field">{{request()->old('reason')}}</textarea>
            <button class="submit" type="submit"></button>
        </form>
        <div class="modal_footer">
            <button class="webz_btn confirm_btn">{{__('main.send')}}</button>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.cancel')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
