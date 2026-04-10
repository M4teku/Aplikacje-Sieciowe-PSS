@extends('layouts.app')
@section('title', 'Moje książki - BookTracker')
@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1><i class="bi bi-bookmarks"></i> Moje książki</h1>
        <p class="lead" style="color: #5D4037;">Książki które dodałeś do swojej listy</p>
    </div>
</div>

<!-- Filtry -->
<div class="card mb-4 book-card">
    <div class="card-body">
        <form class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1" style="color: #5D4037; font-weight: 500;">Status</label>
                <select name="status" class="form-select" style="border: 2px solid var(--wood-brown); padding: 10px;">
                    <option value="all">Wszystkie statusy</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id_status }}" {{ request('status') == $status->id_status ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2 d-flex" style="padding-top: 28px; gap: 12px;">
                <button type="submit" class="btn btn-primary flex-grow-1" style="padding: 10px 15px; border: 2px solid var(--wood-brown);">
                    <i class="bi bi-funnel"></i> Filtruj
                </button>
                <a href="/mybooks" class="btn btn-outline-secondary" style="padding: 10px 15px;">
                    <i class="bi bi-x-circle"></i> Wyczyść
                </a>
            </div>
        </form>
    </div>
</div>

@if($userBooks->count() > 0)
    <div class="row">
        @foreach($userBooks as $userBook)
        <div class="col-md-4 mb-4">
            <div class="card book-card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $userBook->title }}</h5> <!-- DZIAŁA TERAZ -->
                    <h6 class="card-subtitle mb-2" style="color: #5D4037;">{{ $userBook->author }}</h6>
                    
                    <div class="mb-3">
                        <span class="badge bg-warning">{{ $userBook->genre }}</span>
                        <span class="badge bg-{{ $userBook->id_status == 3 ? 'success' : ($userBook->id_status == 2 ? 'warning' : 'secondary') }} ms-1">
                            {{ $userBook->status_name ?? 'Brak statusu' }}
                        </span>
                    </div>
                    
                    <p class="card-text" style="color: #4A3728;">{{ Str::limit($userBook->description, 150) }}</p>
                    
                    <div class="mt-3">
                        <a href="/books/{{ $userBook->id_book }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Szczegóły
                        </a>
                        
                        <a href="{{ route('userbooks.edit', $userBook->id_book) }}" class="btn btn-warning btn-sm ms-1">
                            <i class="bi bi-pencil"></i> Edytuj status
                        </a>
                        
                        <form action="{{ route('userbooks.destroy', $userBook->id_book) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm ms-1" onclick="return confirm('Usunąć książkę z listy?')">
                                <i class="bi bi-trash"></i> Usuń
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top" style="border-color: var(--wood-brown) !important;">
                    <small class="text-muted">Dodano: {{ date('d.m.Y', strtotime($userBook->created_at)) }}</small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($userBooks->hasPages())
    <div class="d-flex justify-content-center mt-4">{{ $userBooks->links() }}</div>
    @endif
    
@else
    <div class="text-center py-5">
        <div class="mb-3"><i class="bi bi-book" style="font-size: 4rem; color: var(--wood-brown); opacity: 0.5;"></i></div>
        <h4 style="color: #5D4037;">Nie masz jeszcze książek w swojej liście</h4>
        <p class="text-muted mb-4">Dodaj książki do śledzenia swojego postępu w czytaniu</p>
        <a href="/books" class="btn btn-primary"><i class="bi bi-search"></i> Przeglądaj książki</a>
    </div>
@endif

<div class="mt-4">
    <a href="/profile" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> ← Powrót do profilu</a>
</div>

<style>
    .book-card { background: linear-gradient(to bottom right, #FFFBF0, #F8F4E9); border: 2px solid var(--wood-brown); border-radius: 12px; box-shadow: 5px 5px 15px rgba(0,0,0,0.1); }
    .card-title { color: #2C1810 !important; font-weight: bold; }
    .badge.bg-warning { background: linear-gradient(to right, var(--bronze), var(--rust)) !important; color: var(--parchment) !important; }
</style>
@endsection