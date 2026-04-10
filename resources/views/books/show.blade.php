@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4 book-card">
            <div class="card-body">
                <div class="mb-4">
                    <h1>{{ $book->title }}</h1>
                    <p class="lead" style="color: #5D4037;">{{ $book->author }}</p>
                </div>
                
                <div class="mb-4">
                    <span class="badge bg-warning" style="font-size: 1rem; padding: 8px 15px;">
                        {{ $book->genre }}
                    </span>
                    
                    @php
                        $avgRating = $book->reviews->avg('rating');
                        $reviewCount = $book->reviews->count();
                    @endphp
                    
                    @if($reviewCount > 0)
                        <span class="badge bg-success ms-2" style="font-size: 1rem; padding: 8px 15px;">
                            ⭐ {{ number_format($avgRating, 1) }}/5 ({{ $reviewCount }} recenzji)
                        </span>
                    @endif
                </div>
                
                <div class="mb-4">
                    <h4 style="color: var(--gold);">Opis</h4>
                    <div style="background: rgba(245, 230, 202, 0.5); padding: 20px; border-radius: 8px; border: 1px solid var(--wood-brown);">
                        <p style="font-size: 1.1rem; line-height: 1.6; color: #4A3728;">{{ $book->description }}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">
                        <i class="bi bi-calendar"></i> Dodano: {{ $book->created_at->format('d.m.Y') }} | 
                        <i class="bi bi-person"></i> Przez: {{ $book->creator->login ?? 'Nieznany użytkownik' }}
                    </small>
                </div>
                
                <div class="mt-4">
                    <a href="/books" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Powrót do listy
                    </a>
                    
                    @if(session('user_id'))
                        @php
                            $userTracking = \App\Models\UserBook::where('id_user', session('user_id'))
                                ->where('id_book', $book->id_book)
                                ->first();
                        @endphp
                        
                        @if(!$userTracking)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addToListModal">
                                <i class="bi bi-plus-circle"></i> Dodaj do mojej listy
                            </button>
                        @else
                            <a href="{{ route('userbooks.mybooks') }}" class="btn btn-info">
                                <i class="bi bi-bookmark-check"></i> Masz już w swojej liście
                            </a>
                        @endif
                        
                        @php
                            $userReview = $book->reviews->where('id_user', session('user_id'))->first();
                        @endphp
                        
                        @if(!$userReview)
                            <a href="{{ route('reviews.create', $book->id_book) }}" class="btn btn-primary">
                                <i class="bi bi-star"></i> Oceń książkę
                            </a>
                        @endif
                        
                        @php
                            $userRoles = session('user_roles', []);
                            $isModerator = in_array('Moderator', $userRoles);
                            $isAdmin = in_array('Admin', $userRoles);
                            // TYLKO Moderator (Admin NIE może!)
                            $canEditBook = $isModerator && !$isAdmin;
                        @endphp
                        
                        @if($canEditBook)
                            <div class="btn-group ms-2">
                                <a href="{{ route('books.edit', $book->id_book) }}" class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Edytuj książkę
                                </a>
                                <form action="{{ route('books.destroy', $book->id_book) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Na pewno usunąć tę książkę?')">
                                        <i class="bi bi-trash"></i> Usuń książkę
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Recenzje -->
        <div class="card book-card">
            <div class="card-body">
                <h3 style="color: var(--gold); border-bottom: 2px solid var(--gold); padding-bottom: 10px;">
                    <i class="bi bi-chat-text"></i> Recenzje ({{ $reviewCount }})
                </h3>
                
                @if($reviewCount > 0)
                    @foreach($book->reviews as $review)
                        <div class="border-bottom pb-3 mb-3" style="border-color: var(--wood-brown) !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                        <strong style="color: #3C2F2F;">{{ $review->user->login }}</strong>
                                    </h5>
                                </div>
                                <small class="text-muted">{{ $review->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                            
                            <div style="background: rgba(245, 230, 202, 0.3); padding: 15px; border-radius: 6px; border-left: 4px solid var(--bronze);">
                                <p class="mb-0" style="color: #4A3728;">{{ $review->content }}</p>
                            </div>
                            
                            @if(session('user_id'))
                                @php
                                    $userRoles = session('user_roles', []);
                                    $isOwner = session('user_id') == $review->id_user;
                                    $isModerator = in_array('Moderator', $userRoles);
                                    $isAdmin = in_array('Admin', $userRoles);
                                    // TYLKO Moderator (Admin NIE może!)
                                    $canEditAllReviews = $isModerator && !$isAdmin;
                                @endphp
                                
                                @if($isOwner || $canEditAllReviews)
                                    <div class="mt-3">
                                        @if($isOwner || $canEditAllReviews)
                                            <a href="{{ route('reviews.edit', $review->id_review) }}" class="btn btn-outline-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Edytuj
                                            </a>
                                        @endif
                                        
                                        @if($isOwner || $canEditAllReviews)
                                            <form action="{{ route('reviews.destroy', $review->id_review) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Usunąć recenzję?')">
                                                    <i class="bi bi-trash"></i> Usuń
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4" style="background: rgba(245, 230, 202, 0.3); border-radius: 8px; border: 1px dashed var(--wood-brown);">
                        <div style="font-size: 3rem; color: var(--wood-brown); margin-bottom: 15px;">📝</div>
                        <h5 style="color: #5D4037;">Brak recenzji</h5>
                        <p class="text-muted">Bądź pierwszym, który oceni tę książkę!</p>
                        
                        @if(session('user_id') && !$userReview)
                            <a href="{{ route('reviews.create', $book->id_book) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Dodaj pierwszą recenzję
                            </a>
                        @elseif(!session('user_id'))
                            <a href="/login" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right"></i> Zaloguj się, aby ocenić
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Prawa kolumna -->
    <div class="col-md-4">
        <!-- Statystyki -->
        <div class="card mb-4 book-card">
            <div class="card-body">
                <h4 style="color: var(--gold); border-bottom: 2px solid var(--gold); padding-bottom: 10px;">
                    <i class="bi bi-graph-up"></i> Statystyki książki
                </h4>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: #5D4037;">
                                <i class="bi bi-star text-warning"></i> Średnia ocena:
                            </span>
                            <strong style="color: #3C2F2F; font-size: 1.1rem;">
                                {{ $avgRating ? number_format($avgRating, 1) : 'Brak' }}/5
                            </strong>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: #5D4037;">
                                <i class="bi bi-chat-text text-primary"></i> Liczba recenzji:
                            </span>
                            <strong style="color: #3C2F2F; font-size: 1.1rem;">{{ $reviewCount }}</strong>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: #5D4037;">
                                <i class="bi bi-people text-success"></i> Książka w listach:
                            </span>
                            <strong style="color: #3C2F2F; font-size: 1.1rem;">
                                @php
                                    $trackingCount = \App\Models\UserBook::where('id_book', $book->id_book)->count();
                                @endphp
                                {{ $trackingCount }} użytkowników
                            </strong>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Twoja recenzja -->
        @if(session('user_id') && isset($userReview) && $userReview)
            <div class="card mb-4 book-card" style="border: 2px solid var(--gold);">
                <div class="card-body">
                    <h4 style="color: var(--gold);">
                        <i class="bi bi-person-circle"></i> Twoja recenzja
                    </h4>
                    
                    <div class="mb-3">
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $userReview->rating)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                            <strong style="color: #3C2F2F; font-size: 1.1rem;" class="ms-2">{{ $userReview->rating }}/5</strong>
                        </div>
                        
                        <div style="background: rgba(212, 175, 55, 0.1); padding: 15px; border-radius: 6px; border-left: 4px solid var(--gold);">
                            <p style="color: #4A3728; margin-bottom: 0;">{{ $userReview->content }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('reviews.edit', $userReview->id_review) }}" class="btn btn-warning btn-sm flex-grow-1">
                            <i class="bi bi-pencil"></i> Edytuj recenzję
                        </a>
                        <form action="{{ route('reviews.destroy', $userReview->id_review) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Usunąć swoją recenzję?')">
                                <i class="bi bi-trash"></i> Usuń
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Twoja lista -->
        @if(session('user_id') && isset($userTracking) && $userTracking)
            <div class="card book-card" style="border: 2px solid var(--bronze);">
                <div class="card-body">
                    <h4 style="color: var(--gold);">
                        <i class="bi bi-bookmark-check"></i> Twoja lista
                    </h4>
                    
                    <div class="mb-3">
                        <p style="color: #5D4037; margin-bottom: 5px;">
                            <strong>Status:</strong>
                        </p>
                        @php
                            $status = \App\Models\ReadingStatus::find($userTracking->id_status);
                        @endphp
                        <span class="badge bg-{{ 
                            $userTracking->id_status == 3 ? 'success' : 
                            ($userTracking->id_status == 2 ? 'warning' : 'secondary')
                        }}" style="font-size: 1rem; padding: 8px 15px;">
                            {{ $status->name ?? 'Nieznany' }}
                        </span>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('userbooks.mybooks') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul"></i> Zobacz wszystkie książki w liście
                        </a>
                        <a href="{{ route('userbooks.edit', $book->id_book) }}" class="btn btn-outline-warning">
                            <i class="bi bi-pencil"></i> Zmień status
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal dodawania do listy -->
@if(session('user_id'))
<div class="modal fade" id="addToListModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #FAF3E0; border: 2px solid var(--wood-brown);">
            <div class="modal-header" style="background: linear-gradient(to right, var(--dark-brown), var(--medium-brown)); color: var(--parchment);">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle"></i> Dodaj "{{ $book->title }}" do swojej listy
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('userbooks.store', $book->id_book) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4 text-center">
                        <div style="font-size: 4rem; color: var(--bronze);">📚</div>
                        <p class="mb-0" style="color: #5D4037; font-weight: 500;">{{ $book->title }}</p>
                        <small class="text-muted">{{ $book->author }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: #5D4037; font-weight: 500;">Status czytania</label>
                        <select name="id_status" class="form-select" style="border: 2px solid var(--wood-brown); padding: 10px;" required>
                            <option value="">Wybierz status...</option>
                            @php
                                $statuses = \App\Models\ReadingStatus::all();
                            @endphp
                            @foreach($statuses as $status)
                                <option value="{{ $status->id_status }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--wood-brown);">
                    <button type="button" class="btn btn-secondary" style="border: 2px solid var(--wood-brown);" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-success" style="border: 2px solid var(--gold);">Dodaj do listy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Dodaj ikony Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
    .book-card {
        background: linear-gradient(to bottom right, #FFFBF0, #F8F4E9);
        border: 2px solid var(--wood-brown);
        border-radius: 12px;
        box-shadow: 5px 5px 15px rgba(0,0,0,0.1);
        color: #3C2F2F;
    }
    
    .book-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(to right, var(--gold), var(--bronze), var(--gold));
        border-radius: 12px 12px 0 0;
    }
    
    .btn-primary {
        background: linear-gradient(to bottom, var(--bronze), var(--rust));
        border: 2px solid var(--gold);
        color: var(--parchment);
        font-weight: bold;
    }
    
    .btn-primary:hover {
        background: linear-gradient(to bottom, var(--rust), var(--bronze));
        border-color: #FFD700;
    }
    
    .btn-success {
        background: linear-gradient(to bottom, #2E7D32, #388E3C);
        border: 2px solid #4CAF50;
        color: white;
    }
    
    .btn-warning {
        background: linear-gradient(to bottom, var(--bronze), var(--rust));
        border: 2px solid var(--gold);
        color: var(--parchment);
    }
    
    .btn-outline-warning {
        border-color: var(--gold);
        color: var(--gold);
    }
    
    .btn-outline-warning:hover {
        background-color: var(--gold);
        color: var(--dark-brown);
    }
    
    .text-muted {
        color: #8D6E63 !important;
    }
    
    h1, h2, h3, h4, h5 {
        color: var(--gold);
        font-family: 'Georgia', serif;
    }
    
    .lead {
        color: #5D4037;
    }
    
    .badge.bg-warning {
        background: linear-gradient(to right, var(--bronze), var(--rust)) !important;
        color: var(--parchment) !important;
    }
</style>
@endsection