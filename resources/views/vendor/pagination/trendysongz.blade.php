@if ($paginator->hasPages())
    <nav class="ts-pagination" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="ts-pagination-control is-disabled" aria-disabled="true">
                Previous
            </span>
        @else
            <a
                class="ts-pagination-control"
                href="{{ $paginator->previousPageUrl() }}"
                rel="prev"
            >
                Previous
            </a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="ts-pagination-ellipsis" aria-hidden="true">
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span
                            class="ts-pagination-page is-current"
                            aria-current="page"
                        >
                            {{ $page }}
                        </span>
                    @else
                        <a class="ts-pagination-page" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a
                class="ts-pagination-control"
                href="{{ $paginator->nextPageUrl() }}"
                rel="next"
            >
                Next
            </a>
        @else
            <span class="ts-pagination-control is-disabled" aria-disabled="true">
                Next
            </span>
        @endif
    </nav>
@endif