@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4 book-card">
            <div class="card-body">
                <h1 class="mb-4">
                    <i class="bi bi-person-circle"></i> Profil użytkownika: {{ $user->login }}
                </h1>
                
                <!-- Statystyki w wierszu -->
                <div class="row mt-4 mb-4">
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card text-center h-100 stat-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.1));">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="bi bi-bookmark" style="font-size: 1.5rem; color: var(--bronze);"></i>
                                </div>
                                <h6 style="color: #5D4037;">Do przeczytania</h6>
                                <h3 style="color: var(--rust);">{{ $stats['want_to_read'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card text-center h-100 stat-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.1));">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="bi bi-book-half" style="font-size: 1.5rem; color: var(--bronze);"></i>
                                </div>
                                <h6 style="color: #5D4037;">Czytam</h6>
                                <h3 style="color: var(--rust);">{{ $stats['reading_now'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card text-center h-100 stat-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.1));">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="bi bi-check-circle" style="font-size: 1.5rem; color: var(--bronze);"></i>
                                </div>
                                <h6 style="color: #5D4037;">Przeczytane</h6>
                                <h3 style="color: var(--rust);">{{ $stats['completed'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card text-center h-100 stat-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.1));">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="bi bi-x-circle" style="font-size: 1.5rem; color: var(--bronze);"></i>
                                </div>
                                <h6 style="color: #5D4037;">Porzucone</h6>
                                <h3 style="color: var(--rust);">{{ $stats['abandoned'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card text-center h-100 stat-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.1));">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="bi bi-star" style="font-size: 1.5rem; color: var(--bronze);"></i>
                                </div>
                                <h6 style="color: #5D4037;">Średnia ocena</h6>
                                <h3 style="color: var(--rust);">{{ $stats['average_rating'] ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-6 mb-3">
                        <div class="card text-center h-100 stat-card" style="background: linear-gradient(135deg, rgba(212, 175, 55, 0.1), rgba(205, 127, 50, 0.1));">
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="bi bi-chat-text" style="font-size: 1.5rem; color: var(--bronze);"></i>
                                </div>
                                <h6 style="color: #5D4037;">Recenzje</h6>
                                <h3 style="color: var(--rust);">{{ $user->reviews->count() ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dwie kolumny: książki i recenzje -->
                <div class="row">
                    <!-- Moje książki -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 book-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 style="color: var(--gold);">
                                        <i class="bi bi-bookmarks"></i> Moje książki ({{ $totalBooks }})
                                    </h4>
                                    @if($totalBooks > 0)
                                        <a href="/mybooks" class="btn btn-sm btn-primary">
                                            Zobacz wszystkie
                                        </a>
                                    @endif
                                </div>
                                
                                @if($recentBooks->count() > 0)
                                    <div class="list-group">
                                        @foreach($recentBooks->take(3) as $book)
                                            <a href="/books/{{ $book->id_book }}" class="list-group-item list-group-item-action" 
                                               style="background: rgba(245, 230, 202, 0.5); border: 1px solid var(--wood-brown); margin-bottom: 8px; border-radius: 6px;">
                                                <div class="d-flex w-100 justify-content-between align-items-center">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1" style="color: #3C2F2F;">{{ $book->title }}</h6>
                                                        <small class="text-muted">{{ $book->author }}</small>
                                                    </div>
                                                    <div class="ms-3">
                                                        <span class="badge bg-{{ 
                                                            $book->id_status == 3 ? 'success' : 
                                                            ($book->id_status == 2 ? 'warning' : 
                                                            ($book->id_status == 4 ? 'danger' : 'secondary'))
                                                        }}">
                                                            {{ $book->status_name ?? 'Brak statusu' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    
                                    @if($totalBooks > 3)
                                        <div class="text-center mt-3">
                                            <a href="/mybooks" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-arrow-right"></i> Zobacz wszystkie książki ({{ $totalBooks }})
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <div class="mb-3">
                                            <i class="bi bi-book" style="font-size: 3rem; color: var(--wood-brown); opacity: 0.5;"></i>
                                        </div>
                                        <p class="text-muted mb-3">Nie masz jeszcze książek w swojej liście.</p>
                                        <a href="/books" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Dodaj pierwszą książkę
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Moje recenzje -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 book-card">
                            <div class="card-body">
                                <h4 style="color: var(--gold);">
                                    <i class="bi bi-chat-text"></i> Moje recenzje ({{ $user->reviews->count() }})
                                </h4>
                                
                                @if($user->reviews->count() > 0)
                                    <div class="list-group">
                                        @foreach($user->reviews->take(3) as $review)
                                            <a href="/books/{{ $review->book->id_book ?? '#' }}" class="list-group-item list-group-item-action"
                                               style="background: rgba(245, 230, 202, 0.5); border: 1px solid var(--wood-brown); margin-bottom: 8px; border-radius: 6px;">
                                                <div class="d-flex w-100 justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1" style="color: #3C2F2F;">
                                                            {{ $review->book->title ?? 'Książka usunięta' }}
                                                        </h6>
                                                        <p class="mb-1 small" style="color: #5D4037;">
                                                            {{ Str::limit($review->content, 80) }}
                                                        </p>
                                                    </div>
                                                    <div class="ms-3 text-end">
                                                        <div class="text-warning">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $review->rating)
                                                                    ⭐
                                                                @else
                                                                    ☆
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <small class="text-muted">{{ $review->created_at->format('d.m.Y') }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    
                                    @if($user->reviews->count() > 3)
                                        <div class="text-center mt-3">
                                            <a href="#" class="btn btn-outline-secondary btn-sm">
                                                <i class="bi bi-arrow-right"></i> Zobacz wszystkie recenzje
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <div class="mb-3">
                                            <i class="bi bi-chat" style="font-size: 3rem; color: var(--wood-brown); opacity: 0.5;"></i>
                                        </div>
                                        <p class="text-muted">Nie napisałeś jeszcze żadnej recenzji.</p>
                                        <a href="/books" class="btn btn-outline-primary">
                                            <i class="bi bi-search"></i> Znajdź książkę do oceny
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informacje o koncie -->
                <div class="card mt-4 book-card">
                    <div class="card-body">
                        <h4 style="color: var(--gold); border-bottom: 2px solid var(--gold); padding-bottom: 10px;">
                            <i class="bi bi-info-circle"></i> Informacje o koncie
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="p-3" style="background: rgba(245, 230, 202, 0.3); border-radius: 8px;">
                                    <p class="mb-2">
                                        <strong style="color: #5D4037;">Login:</strong><br>
                                        <span style="color: #3C2F2F; font-size: 1.1rem;">{{ $user->login }}</span>
                                    </p>
                                    <p class="mb-2">
                                        <strong style="color: #5D4037;">Email:</strong><br>
                                        <span style="color: #3C2F2F;">{{ $user->email }}</span>
                                    </p>
                                    <p class="mb-0">
                                        <strong style="color: #5D4037;">Moje książki:</strong><br>
                                        <a href="/mybooks" class="text-decoration-none" style="color: var(--rust);">
                                            <i class="bi bi-book"></i> {{ $totalBooks }} książek w liście
                                        </a>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="p-3" style="background: rgba(245, 230, 202, 0.3); border-radius: 8px;">
                                    <p class="mb-2">
                                        <strong style="color: #5D4037;">Data rejestracji:</strong><br>
                                        <span style="color: #3C2F2F;">{{ $user->created_at->format('d.m.Y H:i') }}</span>
                                    </p>
                                    <p class="mb-2">
                                        <strong style="color: #5D4037;">Role:</strong><br>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-{{ 
                                                $role->name == 'Admin' ? 'danger' : 
                                                ($role->name == 'Moderator' ? 'warning' : 'secondary')
                                            }} me-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="p-3" style="background: rgba(245, 230, 202, 0.3); border-radius: 8px;">
                                    @if(in_array('Admin', $user->roles->pluck('name')->toArray()) || in_array('Moderator', $user->roles->pluck('name')->toArray()))
                                        <p class="mb-2">
                                            <strong style="color: #5D4037;">Dodane książki:</strong><br>
                                            <span style="color: #3C2F2F;">
                                                {{ $user->booksAdded->count() }}
                                                <small class="text-muted">(jako moderator/admin)</small>
                                            </span>
                                        </p>
                                        <p class="mb-0">
                                            <strong style="color: #5D4037;">Edytowane książki:</strong><br>
                                            <span style="color: #3C2F2F;">
                                                {{ $user->booksUpdated->count() }}
                                                <small class="text-muted">(jako moderator/admin)</small>
                                            </span>
                                        </p>
                                    @else
                                        <p class="text-muted mb-0 text-center py-3">
                                            <i class="bi bi-person" style="font-size: 2rem;"></i><br>
                                            Konto czytelnika
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Przycisk edycji profilu -->
                        @if(session('user_id') && session('user_id') == $user->id_user)
                            <div class="text-center mt-4">
                                <a href="/profile/edit" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Edytuj swój profil
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .book-card {
        background: linear-gradient(to bottom right, #FFFBF0, #F8F4E9);
        border: 2px solid var(--wood-brown);
        border-radius: 12px;
        box-shadow: 5px 5px 15px rgba(0,0,0,0.1);
        color: #3C2F2F;
    }
    
    .stat-card {
        border: 1px solid var(--wood-brown);
        border-radius: 10px;
        transition: transform 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .list-group-item {
        transition: all 0.2s;
    }
    
    .list-group-item:hover {
        background: rgba(212, 175, 55, 0.2) !important;
        border-color: var(--gold) !important;
    }
    
    h1, h2, h3, h4, h5 {
        color: var(--gold);
        font-family: 'Georgia', serif;
    }
    
    .btn-primary {
        background: linear-gradient(to bottom, var(--bronze), var(--rust));
        border: 2px solid var(--gold);
        color: var(--parchment);
        font-weight: bold;
    }
    
    .btn-warning {
        background: linear-gradient(to bottom, var(--bronze), var(--rust));
        border: 2px solid var(--gold);
        color: var(--parchment);
    }
    
    .badge.bg-warning {
        background: linear-gradient(to right, var(--bronze), var(--rust)) !important;
        color: var(--parchment) !important;
    }
    
    .badge.bg-danger {
        background: linear-gradient(to right, #dc3545, #c82333) !important;
    }
    
    .badge.bg-success {
        background: linear-gradient(to right, #28a745, #218838) !important;
    }
    
    .text-muted {
        color: #8D6E63 !important;
    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection