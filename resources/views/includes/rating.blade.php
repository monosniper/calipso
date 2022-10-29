@if($rating)
    <?php $s = 0; ?>
    @for($i=0;$i<$rating;$i++)
        <i class="fas fa-star"></i>
        <?php $s++; ?>
    @endfor
    @if($s <= 4)
        @for($i=0;$i<=4-$s;$i++)
            <i class="far fa-star"></i>
        @endfor
    @endif
@else
    @for($i=0;$i<=4;$i++)
        <i class="far fa-star"></i>
    @endfor
@endif
