@if ($paginator->hasPages())



    <div class="pagination">

        <span class="pagination-results">
            {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} {!! __('of') !!} {{ $paginator->total() }}
        </span>

        @if ($paginator->onFirstPage())
            <div class="pagination-btn pagination-prev">
                @include('includes.svg', ['name' => 'left-arrow'])
            </div>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn pagination-prev">
                @include('includes.svg', ['name' => 'left-arrow'])
            </a>
        @endif

        <div class="pagination-items">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <<div class="pagination-item">...</div>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <div class="pagination-item active">{{$page}}</div>
                        @else
                            <a href="{{ $url  }}" class="pagination-item">{{$page}}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn pagination-next">
                @include('includes.svg', ['name' => 'right-arrow'])
            </a>
        @else
            <a class="pagination-btn pagination-next">
                @include('includes.svg', ['name' => 'right-arrow'])
            </a>
        @endif
    </div>
@endif
