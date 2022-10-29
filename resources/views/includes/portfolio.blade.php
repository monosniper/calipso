<div class="portfolio open_modal" modal-wrapper="#portfolio-{{$portfolio->id}}">
    <div class="portfolio_image" style="background-image: url('{{ $portfolio->getPreview() }}')"></div>
    <div class="portfolio_overlay">
        <div class="portfolio_overlay_header">
{{--            <div class="portfolio_btn">--}}
{{--                @include('includes.svg', ['name' => 'eye'])--}}
{{--                <small>{{$portfolio->views}}</small>--}}
{{--            </div>--}}
{{--            <div class="portfolio_btn portfolio_btn_min_img">--}}
{{--                @include('includes.svg', ['name' => 'like'])--}}
{{--                <small>{{$portfolio->likes}}</small>--}}
{{--            </div>--}}
        </div>
        <div class="portfolio_overlay_footer">
            <div class="portfolio_title">{{$portfolio->getShortTitle()}}</div>
        </div>
    </div>
</div>
@include('includes.modals.portfolio', ['portfolio' => $portfolio])