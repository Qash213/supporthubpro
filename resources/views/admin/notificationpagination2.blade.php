@if ($paginator->hasPages())
    <nav aria-label="navigation" class="mt-5">
        <ul class="pagination custom-ul">

            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link"><i class="fa fa-angle-left"></i>
                    <span class="sr-only">{{lang('Previous')}}</span></span></li>
            @else
                <li class="page-item" ><a class="page-link notifypaginationfetch" href="javascript:(0);" data-url="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fa fa-angle-left"></i>
                    <span class="sr-only">{{lang('Previous')}}</span></a></li>
            @endif



            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class=" page-item disabled"><span>{{ $element }}</span></li>
                @endif



                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class=" page-item active "><a class="page-link notifypaginationfetch" href="javascript:(0);" data-url="{{ $url }}">{{ $page }}</a></li>
                        @else
                            <li class="page-item"><a class="page-link notifypaginationfetch" href="javascript:(0);" data-url="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach



            @if ($paginator->hasMorePages())
                <li class="page-item" ><a class="page-link notifypaginationfetch" href="javascript:(0);" data-url="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fa fa-angle-right"></i>
                    <span class="sr-only">{{lang('Next')}}</span></a></li>
            @else
                <li class=" page-item disabled"><span class="page-link"><i class="fa fa-angle-right"></i>
                    <span class="sr-only">{{lang('Next')}}</span></span></li>
            @endif
        </ul>
    </nav>
@endif

