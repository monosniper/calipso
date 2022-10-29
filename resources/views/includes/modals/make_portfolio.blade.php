<div class="modal_wrapper" id="make_portfolio">
    <div class="modal">
        <div class="modal_title">{{__('main.add_portfolio')}}</div>
        <div class="flex-center">
            <div class="webz_underline">
                <span class="pink"></span>
                <span class="grey"></span>
            </div>
        </div>
        <form action="{{ route('portfolios.store') }}" method="post" class="modal_form" enctype="multipart/form-data">
            @csrf
            <input required value="{{request()->old('title')}}" type="text" minlength="3" maxlength="200" name="title" placeholder="{{__('modals.add_portfolio.title')}}:" class="modal_field">
            <input value="{{request()->old('link')}}" type="url" name="link" placeholder="{{__('modals.add_portfolio.link')}}:" class="modal_field">
            <input value="{{request()->old('tag')}}" type="text" name="tag" placeholder="{{__('modals.add_portfolio.tag')}}:" class="modal_field">
            <textarea required minlength="10" maxlength="500" name="description" placeholder="{{__('modals.add_portfolio.description')}}" id="description" cols="30" rows="5" class="modal_field">{{request()->old('description')}}</textarea>
            <input required value="{{request()->old('preview')}}" type="file" name="preview" id="preview">
            <button class="submit" type="submit"></button>
        </form>
        <div class="modal_footer">
            <button class="webz_btn confirm_btn">{{__('main.ready')}}</button>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.cancel')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
