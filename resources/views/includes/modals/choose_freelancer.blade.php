<div class="modal_wrapper" id="choose-freelancer-{{$offer->id}}">
    <div class="modal">
        <div class="modal_logo">
            @include('includes.svg', ['name' => 'logo'])
        </div>
        <hr>
        <div class="modal_title">{{__('main.choose_freelancer')}}</div>
        <div class="modal_text">
            <p>{{__('modals.choose_freelancer.you_sure')}}</p>
            <br>
            @include('includes.offer', ['offer' => $offer, 'no_modal' => true, 'no_choose' => true])
        </div>
        <div class="modal_footer">
            <a href="{{route('freelance.orders.choose_offer', ['order' => $offer->order_id, 'offer' => $offer->id])}}" class="webz_btn confirm_btn">{{__('main.yes')}}</a>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.no')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
