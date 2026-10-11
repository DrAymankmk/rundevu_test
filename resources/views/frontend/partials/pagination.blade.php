@if ($paginator->hasPages())
    @php
        $isRtl = in_array(app()->getLocale(), ['ar', 'fa', 'he', 'ur'], true);
        $prevIcon = $isRtl ? 'fa-arrow-right' : 'fa-arrow-left';
        $nextIcon = $isRtl ? 'fa-arrow-left' : 'fa-arrow-right';
    @endphp
    <nav class="frontend-pagination" aria-label="Pagination">
        <div class="frontend-pagination__meta">
            {{ __('doctors.pagination_showing', [
                'from' => $paginator->firstItem() ?? 0,
                'to' => $paginator->lastItem() ?? 0,
                'total' => $paginator->total(),
            ]) }}
        </div>

        <div class="th-pagination">
            <ul>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="disabled" aria-disabled="true">
                        <span><i class="fa-regular {{ $prevIcon }}"></i></span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                            <i class="fa-regular {{ $prevIcon }}"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span class="active" aria-current="page">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                            <i class="fa-regular {{ $nextIcon }}"></i>
                        </a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true">
                        <span><i class="fa-regular {{ $nextIcon }}"></i></span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
