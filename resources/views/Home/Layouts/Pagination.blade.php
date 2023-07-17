@php
    $link_limit = 6; // maximum number of links (a little bit inaccurate, but will be ok for now)
    $query_string = \Request::except(['page']);
    $query_string = http_build_query($query_string,'','&');
    $is_plus = false;
    $is_diff = false;
@endphp

@if ($paginator->hasPages())
    <div class="paged">
            {{-- Previous Page Link --}}
            @if ($paginator->currentPage() != 2 && !$paginator->onFirstPage())
                <a class="page-link" href="{{ \Request::url() .'?'. $query_string }}" rel="prev"><<</a>
            @endif
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)

                @php
                $half_total_links = floor($link_limit / 2);
                $from = $paginator->currentPage() - $half_total_links;
                $to = $paginator->currentPage() + $half_total_links;
                if ($paginator->currentPage() < $half_total_links) {
                    $to += $half_total_links - $paginator->currentPage();
                }
                if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                }
                @endphp

                @if ($from < $i && $i < $to)
                    @if($paginator->currentPage() == $i)
                        <span class="current">{{ $i }}</span>
                    @else
                        <a href="{{ $paginator->url($i) .'&'. $query_string }}">{{ $i }}</a>
                    @endif
                <!-- + 10 page -->
                @elseif($i > $to)
                    @if (!$is_plus && $paginator->hasMorePages() && $paginator->currentPage() + 10 < $paginator->lastPage())
                        @php
                            $is_plus = true;
                        @endphp
                        <a href="javascript:{};" style="cursor: not-allowed">...</a>
                        <a href="{{ $paginator->url($to + 10 - 1) .'&'. $query_string }}" >{{ $to + 10 - 1 }}</a>
                    @endif
                <!-- - 10 page -->
                @elseif($i < $from)
                    @if (!$is_diff && !$paginator->onFirstPage() && ($paginator->currentPage() - 10 > 1))
                        @php
                            $is_diff = true;
                        @endphp
                        <a href="{{ $paginator->url($from - 10 - 1) .'&'. $query_string }}" >{{ $from - 10 - 1 > 0 ? $from - 10 - 1 : 1  }}</a>
                        <a href="javascript:{};" style="cursor: not-allowed">...</a>
                    @endif
                @endif

            @endfor

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages() && $paginator->currentPage() + 1 != $paginator->lastPage())
                <a href="{{ \Request::url().'?'.$query_string.'&page='.$paginator->lastPage() }}" rel="last">>></a>
            @endif
        </div>
@endif
