<div class="count-item">Tổng cộng: {{$paginator->total()}} items</div>
<div class="list-page">
    <a href="{{$paginator->previousPageUrl()}}" class="next">&lsaquo;&lsaquo;</a>
    @if($paginator->currentPage() == 1)
        <a href="{{$paginator->url(1)}}" class="current">1</a>
    @else
        <a href="{{$paginator->url(1)}}">1</a>
    @endif

    @if($paginator->hasPages(2))
        @if($paginator->currentPage() == 2)
            <a href="{{$paginator->url(2)}}" class="current">2</a>
        @else
            <a href="{{$paginator->url(2)}}">2</a>
        @endif
    @endif
    @if($paginator->lastPage() == 3)
        @if($paginator->currentPage() == 3)
            <a href="{{$paginator->url(3)}}" class="current">3</a>
        @else
            <a href="{{$paginator->url(3)}}">3</a>
        @endif
    @endif
    @if($paginator->lastPage() > 3)
        @if($paginator->currentPage() - 2 >= 1)
            @if($paginator->lastPage() - $paginator->currentPage() == 0)
                <a>...</a>
                <a href="{{$paginator->url( $paginator->currentPage())}}" class="current">{{$paginator->currentPage()}}</a>
            @endif
            @if($paginator->lastPage() - $paginator->currentPage() == 1)
                <a>...</a>
                <a href="{{$paginator->url( $paginator->currentPage())}}" class="current">{{$paginator->currentPage()}}</a>
                <a href="{{$paginator->url($paginator->lastPage())}}" class="current">{{$paginator->lastPage()}}</a>
            @endif
            @if($paginator->lastPage() - $paginator->currentPage() > 1)
                @if($paginator->currentPage() == 3)
                    <a href="{{$paginator->url( $paginator->currentPage())}}"class="current">{{$paginator->currentPage()}}</a>
                    <a>...</a>
                    <a href="{{$paginator->url($paginator->lastPage())}}" >{{$paginator->lastPage()}}</a>
                @else
                    <a>...</a>
                    <a href="{{$paginator->url( $paginator->currentPage())}}"class="current">{{$paginator->currentPage()}}</a>
                    <a>...</a>
                    <a href="{{$paginator->url($paginator->lastPage())}}" >{{$paginator->lastPage()}}</a>
                @endif
            @endif
        @else
            <a>...</a>
            <a href="{{$paginator->url($paginator->lastPage())}}" >{{$paginator->lastPage()}}</a>
        @endif

    @endif
    <a href="{{$paginator->nextPageUrl()}}" class="next">&rsaquo;&rsaquo;</a>
</div>
