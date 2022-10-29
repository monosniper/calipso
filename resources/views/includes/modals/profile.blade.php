<div class="modal_wrapper" id="profile">
    <div class="modal">
        <div class="modal_title">{{__('main.edit_password')}}</div>
        <div class="flex-center">
            <div class="webz_underline">
                <span class="pink"></span>
                <span class="grey"></span>
            </div>
        </div>
        <form action="{{ route('forms.profile') }}" method="post" class="modal_form">
            @csrf
            <input value="{{ $user->first_name }}" type="text" name="first_name" placeholder="{{__('modals.profile.first_name')}}:" class="modal_field">
            <input value="{{$user->last_name}}" type="text" name="last_name" placeholder="{{__('modals.profile.last_name')}}:" class="modal_field">
            <input value="{{$user->username}}" type="text" name="username" placeholder="{{__('modals.profile.username')}}:" class="modal_field">
            <input value="{{$user->email}}" type="email" name="email" placeholder="{{__('modals.profile.email')}}:" class="modal_field">
            <input value="{{$user->location}}" type="text" name="location" id="location" placeholder="{{__('modals.profile.location')}}" class="modal_field">
            @if(auth()->user()->isFreelancer)
                <select multiple name="categories[]" id="categories" class="modal_field">
                    @foreach($categories as $category)
                        <option value="{{$category->id}}" {{in_array($category->id, $user->categories->pluck('id')->toArray()) ? 'selected' : ''}}>{{$category->name}}</option>
                    @endforeach
                </select>
            @endif
            <button class="submit" type="submit"></button>
        </form>
        <div class="modal_footer">
            <button class="webz_btn confirm_btn">{{__('main.ready')}}</button>
            <button class="webz_btn webz_btn_outline dismiss_btn">{{__('main.cancel')}}</button>
        </div>
    </div>

    <div class="modal_overflow"></div>
</div>
