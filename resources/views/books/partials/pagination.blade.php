@if($books->hasPages())
<nav>
    <ul class="pagination">
        @if($books->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&laquo; Poprzednia</span></li>
        @else
            <li class="page-item"><a class="page-link ajax-page" href="#" data-url="{{ $books->previousPageUrl() }}">&laquo; Poprzednia</a></li>
        @endif
        
        @foreach($books->getUrlRange(1, $books->lastPage()) as $page => $url)
            @if($page == $books->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
            @else
                <li class="page-item"><a class="page-link ajax-page" href="#" data-url="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach
        
        @if($books->hasMorePages())
            <li class="page-item"><a class="page-link ajax-page" href="#" data-url="{{ $books->nextPageUrl() }}">Następna &raquo;</a></li>
        @else
            <li class="page-item disabled"><span class="page-link">Następna &raquo;</span></li>
        @endif
    </ul>
</nav>
@endif

<div class="text-center text-muted mt-3">
    <small>Wyświetlono {{ $books->firstItem() }} - {{ $books->lastItem() }} z {{ $books->total() }} książek</small>
</div>