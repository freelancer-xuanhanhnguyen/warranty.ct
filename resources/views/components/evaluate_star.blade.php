@if($star > 0)
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $star)
            <i class="text-warning fa fa-fw fa-star"></i>
        @else
            <i class="fa fa-fw fa-star"></i>
        @endif
    @endfor
@endif
