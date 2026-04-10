<div class="row">
    @foreach($books as $book)
    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 book-item">
        <div class="card h-100 shadow-sm hover-shadow">
            <div class="card-body">
                <h5 class="card-title text-truncate">{{ $book->title }}</h5>
                <h6 class="card-subtitle mb-3 text-muted"><i class="bi bi-person"></i> {{ $book->author }}</h6>
                
                <div class="mb-3">
                    <span class="badge bg-primary"><i class="bi bi-tag"></i> {{ $book->genre }}</span>
                    @if($book->reviews_count > 0)
                        <span class="badge bg-success ms-1"><i class="bi bi-star"></i> {{ $book->reviews_count }}</span>
                    @endif
                </div>
                
                <p class="card-text text-secondary small">{{ Str::limit($book->description, 100) }}</p>
            </div>
            <div class="card-footer bg-transparent border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="/books/{{ $book->id_book }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Szczegóły
                    </a>
                    
                    @if(session('user_id'))
                        <button class="btn btn-sm btn-success add-to-list-btn"
                                data-book-id="{{ $book->id_book }}"
                                data-book-title="{{ $book->title }}"
                                data-book-author="{{ $book->author }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#addToListModal">
                            <i class="bi bi-plus"></i> Do listy
                        </button>
                    @endif
                </div>
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-calendar"></i> {{ $book->created_at->format('d.m.Y') }}
                </small>
            </div>
        </div>
    </div>
    @endforeach
</div>