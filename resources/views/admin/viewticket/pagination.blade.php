@if ($paginator->hasPages())
    <nav aria-label="navigation" class="mt-3" style="position: absolute; right: 23px; bottom: 9px;">
        <ul class="pagination custom-ul">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">{{lang('Previous')}}
                    <span class="sr-only">{{lang('Previous')}}</span></span></li>
            @else
                <li class="page-item" ><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="{{ $paginator->currentPage() - 1 }}" rel="prev">{{lang('Previous')}}
                    <span class="sr-only">{{lang('Previous')}}</span></a></li>
            @endif



            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class=" page-item disabled"><span>{{ $element }}</span></li>
                @endif



                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class=" page-item active "><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="{{ $page }}">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="{{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach



            @if ($paginator->hasMorePages())
                <li class="page-item" ><a class="page-link paginationDatafetch" href="javascript:(0);" data-id="{{ $paginator->currentPage() + 1 }}" rel="next">{{lang('Next')}}
                    <span class="sr-only">{{lang('Next')}}</span></a></li>
            @else
                <li class=" page-item disabled"><span class="page-link">{{lang('Next')}}
                    <span class="sr-only">{{lang('Next')}}</span></span></li>
            @endif
        </ul>
    </nav>
@endif
