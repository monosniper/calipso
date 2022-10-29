<div class="breadcrumbs">
    @if(isset($back_btn) && $back_btn)
        <a href="{{ $items[count($items)-2]['link'] }}" class="webz_btn">{{__('main.back')}}</a>
    @endif
    @forelse($items as $item)
        <a href="{{ $item['link'] }}" class="breadcrumbs_item {{ $loop->last ? 'active' : '' }}">{{ $item['title']  }}</a>
        @unless($loop->last)
            <div class="breadcrumbs_separator">/</div>
        @endunless
    @empty

    @endforelse
</div>
