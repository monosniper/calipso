<div class="white_block white_block_no_x_padding sidebar">
    <div class="sidebar-title">{{__('main.filters.tags')}}</div>
    <form action="{{ $link }}" class="sidebar-form">
        <select class="sidebar-field" name="tags[]" multiple id="tags">
            @foreach($tags as $tag)
            <option
                value="{{$tag->name}}" {{in_array($tag->name, is_array(request()->tags) ? request()->tags : explode(',', request()->tags)) ? 'selected' : ''}}>{{$tag->name}}</option>
            @endforeach
        </select>
        @include('includes.form-request-fields', ['except' => ['page', 'price_from', 'price_to', 'tags']])
        <button class="webz_btn sidebar_btn">{{__('main.ready')}}</button>
    </form>
</div>
