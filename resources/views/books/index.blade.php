@extends('layouts.app')
@section('title', 'Przeglądaj książki - BookTracker')
@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1><i class="bi bi-book"></i> Książki</h1>
                <p class="lead text-muted">Przeglądaj kolekcję książek i dodawaj je do swojej listy</p>
            </div>
            
            @if(session('user_id'))
                @php
                    $userRoles = session('user_roles', []);
                    $hasModeratorRole = in_array('Moderator', $userRoles);
                    $hasAdminRole = in_array('Admin', $userRoles);
                    $canAddBooks = $hasModeratorRole && !$hasAdminRole;
                @endphp
                
                @if($canAddBooks)
                    <a href="{{ route('books.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Dodaj książkę
                    </a>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form id="filterForm" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Szukaj książki</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Tytuł, autor, opis..." value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="col-md-3">
                <label class="form-label">Gatunek</label>
                <select name="genre" id="genre" class="form-select">
                    <option value="all">Wszystkie gatunki</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                            {{ $genre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">Sortowanie</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Najnowsze</option>
                    <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Tytuł A-Z</option>
                    <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Tytuł Z-A</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <div class="d-grid gap-2">
                    <button type="button" id="resetBtn" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Wyczyść
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="loadingSpinner" class="text-center py-5" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Ładowanie...</span>
    </div>
    <p class="mt-2">Ładowanie książek...</p>
</div>

<div id="booksContainer">
    @include('books.partials.book_list', ['books' => $books])
</div>

<div id="paginationContainer">
    @include('books.partials.pagination', ['books' => $books])
</div>

@if(session('user_id'))
<div class="modal fade" id="addToListModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dodaj do listy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBookForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3"><strong id="bookTitle"></strong><br><small class="text-muted" id="bookAuthor"></small></p>
                    <div class="mb-3">
                        <label class="form-label">Status czytania</label>
                        <select name="id_status" class="form-select" required>
                            <option value="">Wybierz...</option>
                            @foreach(\App\Models\ReadingStatus::all() as $status)
                                <option value="{{ $status->id_status }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-success">Dodaj</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .hover-shadow { transition: transform 0.2s, box-shadow 0.2s; border: none; border-radius: 10px; }
    .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    
    .pagination {
        gap: 5px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .page-link {
        border-radius: 8px !important;
        margin: 0 2px;
        color: #5D4037;
        border: 1px solid #6D4C41;
        background-color: #FAF3E0;
        padding: 8px 16px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #CD7F32 0%, #B7410E 100%);
        border-color: #D4AF37;
        color: white;
    }
    
    .page-item.disabled .page-link {
        background-color: #e9ecef;
        color: #6c757d;
        border-color: #dee2e6;
        cursor: not-allowed;
    }
    
    .page-link:hover:not(.disabled) {
        background: linear-gradient(135deg, #D4AF37 0%, #CD7F32 100%);
        border-color: #D4AF37;
        color: white;
    }
    
    .book-item {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const genreSelect = document.getElementById('genre');
    const sortSelect = document.getElementById('sort');
    const resetBtn = document.getElementById('resetBtn');
    const booksContainer = document.getElementById('booksContainer');
    const paginationContainer = document.getElementById('paginationContainer');
    const loadingSpinner = document.getElementById('loadingSpinner');
    
    let currentPage = 1;
    let timeoutId = null;
    
    function loadBooks(url = null) {
        if (!url) {
            const params = new URLSearchParams({
                search: searchInput.value,
                genre: genreSelect.value,
                sort: sortSelect.value,
                page: currentPage
            });
            url = '/books/filter?' + params.toString();
        }
        
        const urlParams = new URLSearchParams(url.split('?')[1]);
        currentPage = parseInt(urlParams.get('page')) || 1;
        
        loadingSpinner.style.display = 'block';
        booksContainer.style.opacity = '0.5';
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            booksContainer.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            loadingSpinner.style.display = 'none';
            booksContainer.style.opacity = '1';
            
            attachPaginationEvents();
            attachAddToListEvents();
            highlightCurrentPage();
        })
        .catch(error => {
            console.error('Error:', error);
            loadingSpinner.style.display = 'none';
            booksContainer.style.opacity = '1';
        });
    }
    
    function highlightCurrentPage() {
        document.querySelectorAll('.pagination .page-item').forEach(item => {
            item.classList.remove('active');
            const link = item.querySelector('.page-link');
            if (link && link.textContent == currentPage) {
                item.classList.add('active');
            }
        });
    }
    
    function attachPaginationEvents() {
        document.querySelectorAll('.ajax-page').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                if (url) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    loadBooks(url);
                }
            });
        });
    }
    
    function attachAddToListEvents() {
        document.querySelectorAll('.add-to-list-btn').forEach(button => {
            button.removeEventListener('click', addToListHandler);
            button.addEventListener('click', addToListHandler);
        });
    }
    
    function addToListHandler(e) {
        const button = e.currentTarget;
        const bookId = button.getAttribute('data-book-id');
        const bookTitle = button.getAttribute('data-book-title');
        const bookAuthor = button.getAttribute('data-book-author');
        
        document.getElementById('bookTitle').textContent = bookTitle;
        document.getElementById('bookAuthor').textContent = bookAuthor;
        document.getElementById('addBookForm').action = '/books/' + bookId + '/track';
    }
    
    function resetFilters() {
        searchInput.value = '';
        genreSelect.value = 'all';
        sortSelect.value = 'newest';
        currentPage = 1;
        loadBooks();
    }
    
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        currentPage = 1;
        timeoutId = setTimeout(() => {
            loadBooks();
        }, 500);
    });
    
    genreSelect.addEventListener('change', function() {
        currentPage = 1;
        loadBooks();
    });
    
    sortSelect.addEventListener('change', function() {
        currentPage = 1;
        loadBooks();
    });
    
    resetBtn.addEventListener('click', resetFilters);
    
    attachPaginationEvents();
    attachAddToListEvents();
    highlightCurrentPage();
    
    const addModal = document.getElementById('addToListModal');
    if (addModal) {
        addModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            if (button) {
                document.getElementById('bookTitle').textContent = button.getAttribute('data-book-title');
                document.getElementById('bookAuthor').textContent = button.getAttribute('data-book-author');
                document.getElementById('addBookForm').action = '/books/' + button.getAttribute('data-book-id') + '/track';
            }
        });
    }
});
</script>
@endsection