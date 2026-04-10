@extends('layouts.app')

@section('title', 'Statystyki systemu - BookTracker')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-graph-up"></i> Statystyki systemu</h1>
                    <p class="lead text-muted">Przegląd statystyk i aktywności w systemie</p>
                </div>
                <div>
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-people"></i> Użytkownicy
                    </a>
                    <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-list-check"></i> Logi systemowe
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statystyki ogólne -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-people" style="font-size: 2rem; color: #4A90E2;"></i>
                    </div>
                    <h6 class="text-muted">Użytkownicy</h6>
                    <h2>{{ $stats['total_users'] }}</h2>
                    <small class="text-muted">+{{ $stats['active_today'] }} dzisiaj</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-book" style="font-size: 2rem; color: #50E3C2;"></i>
                    </div>
                    <h6 class="text-muted">Książki</h6>
                    <h2>{{ $stats['total_books'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-chat-text" style="font-size: 2rem; color: #F5A623;"></i>
                    </div>
                    <h6 class="text-muted">Recenzje</h6>
                    <h2>{{ $stats['total_reviews'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bi bi-bookmark-check" style="font-size: 2rem; color: #9013FE;"></i>
                    </div>
                    <h6 class="text-muted">Śr. książek/użytk.</h6>
                    <h2>{{ number_format($stats['avg_books_per_user'], 1) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Dwie kolumny -->
    <div class="row">
        <!-- Ostatni użytkownicy -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-plus"></i> Ostatnio zarejestrowani
                    </h5>
                    
                    <div class="list-group">
                        @foreach($recentUsers as $user)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $user->login }}</h6>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">{{ $user->created_at->format('d.m.Y H:i') }}</small>
                                    <div class="mt-1">
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-{{ $role->name == 'Admin' ? 'danger' : ($role->name == 'Moderator' ? 'warning' : 'secondary') }} me-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ostatnie książki -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-book-half"></i> Ostatnio dodane książki
                    </h5>
                    
                    <div class="list-group">
                        @foreach($recentBooks as $book)
                        <a href="/books/{{ $book->id_book }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $book->title }}</h6>
                                    <small class="text-muted">{{ $book->author }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">{{ $book->created_at->format('d.m.Y') }}</small>
                                    <div class="mt-1">
                                        <span class="badge bg-primary">{{ $book->genre }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Najpopularniejsze książki -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-trophy"></i> Najpopularniejsze książki
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tytuł</th>
                                    <th>Autor</th>
                                    <th>Gatunek</th>
                                    <th>Liczba użytkowników</th>
                                    <th>Data dodania</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topBooks as $book)
                                <tr>
                                    <td>
                                        <a href="/books/{{ $book->id_book }}">{{ $book->title }}</a>
                                    </td>
                                    <td>{{ $book->author }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $book->genre }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $book->tracking_count ?? 0 }}</strong>
                                    </td>
                                    <td>{{ $book->created_at->format('d.m.Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border: none;
    }
    
    .card-title {
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 15px;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    h2 {
        color: #2c3e50;
        font-weight: 700;
    }
    
    .badge {
        font-weight: 500;
        padding: 4px 8px;
        font-size: 0.8rem;
    }
</style>
@endsection