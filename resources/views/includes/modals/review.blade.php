<div class="modal_wrapper" id="add-review">
    <div class="modal">
        <div class="modal_title">{{__('main.make_review')}}</div>
        <div class="flex-center">
            <div class="webz_underline">
                <span class="pink"></span>
                <span class="grey"></span>
            </div>
        </div>
        <form action="{{ route('reviews.store') }}" method="post" class="modal_form">
            @csrf
            <input id="rating" value="1" name="rating" type="hidden">
            <div id="rater"></div>
            <input name="reviewable_id" type="hidden" value="{{$reviewable_id}}">
            <input name="reviewable_type" type="hidden" value="{{$reviewable_type}}">
            <input id="title" name="title" type="text" placeholder="{{__('modals.review.title')}}" class="modal_field">
            <textarea name="content" placeholder="{{__('modals.review.text')}}" id="review-content" cols="30" rows="10" class="modal_field"></textarea>
            <button class="submit" type="submit"></button>
        </form>
        <div class="modal_footer">
            <button class="webz_btn confirm_btn">{{__('main.ready')}}</button>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.cancel')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
