<form action="{{ route($page)  }}" class="search">
    <input value="{{ request('q')  }}" type="text" placeholder="{{__('main.search')}}" name="q">
    @include('includes.form-request-fields', ['except' => ['page', 'q']])
    <button type="submit" class="search-btn">
        @include('includes.svg', ['name' => 'search'])
    </button>
</form>
