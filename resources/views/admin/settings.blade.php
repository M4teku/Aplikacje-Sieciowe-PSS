@extends('layouts.app')

@section('title', 'Ustawienia systemu - BookTracker')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-gear"></i> Ustawienia systemu</h1>
                    <p class="lead text-muted">Konfiguracja parametrów systemu BookTracker</p>
                </div>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Powrót do panelu
                </a>
            </div>
        </div>
    </div>

    <!-- Formularz ustawień -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.updateSettings') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Ogólne ustawienia</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Nazwa strony</label>
                                <input type="text" name="site_name" class="form-control" 
                                       value="BookTracker" placeholder="Nazwa Twojej strony">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Domyślna rola nowego użytkownika</label>
                                <select name="default_user_role" class="form-select">
                                    <option value="">Wybierz rolę...</option>
                                    @foreach(\App\Models\Role::where('is_active', true)->get() as $role)
                                        <option value="{{ $role->id_role }}" {{ $role->name == 'Czytelnik' ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Rola przypisywana automatycznie nowym użytkownikom</small>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Ograniczenia</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Maksymalna liczba książek na użytkownika</label>
                                <input type="number" name="max_books_per_user" class="form-control" 
                                       min="1" value="500" placeholder="np. 500">
                                <small class="form-text text-muted">0 = brak limitu</small>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="card-title mb-3">Bezpieczeństwo</h5>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="allow_registration" 
                                       id="allow_registration" value="1" checked>
                                <label class="form-check-label" for="allow_registration">
                                    Zezwalaj na rejestrację nowych użytkowników
                                </label>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="email_verification" 
                                       id="email_verification" value="1">
                                <label class="form-check-label" for="email_verification">
                                    Wymagaj weryfikacji email
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-secondary">Anuluj zmiany</button>
                            <button type="submit" class="btn btn-primary">Zapisz ustawienia</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Informacje o systemie -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-info-circle"></i> Informacje o systemie
                    </h5>
                    
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Wersja BookTracker:</strong>
                            <span class="float-end">1.0.0</span>
                        </li>
                        <li class="mb-2">
                            <strong>Laravel:</strong>
                            <span class="float-end">{{ app()->version() }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>PHP:</strong>
                            <span class="float-end">{{ PHP_VERSION }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Środowisko:</strong>
                            <span class="float-end">{{ app()->environment() }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Debug:</strong>
                            <span class="float-end">{{ config('app.debug') ? 'Włączony' : 'Wyłączony' }}</span>
                        </li>
                        <li>
                            <strong>Ostatni backup:</strong>
                            <span class="float-end text-muted">Nigdy</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Szybkie akcje -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-lightning"></i> Szybkie akcje
                    </h5>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.logs') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list-check"></i> Zobacz logi systemowe
                        </a>
                        
                        <a href="{{ route('admin.statistics') }}" class="btn btn-outline-primary">
                            <i class="bi bi-graph-up"></i> Statystyki systemu
                        </a>
                        
                        <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
                            <i class="bi bi-trash"></i> Wyczyść cache
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger" onclick="runBackup()">
                            <i class="bi bi-download"></i> Utwórz backup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function clearCache() {
    if (confirm('Czy na pewno chcesz wyczyścić cache systemu?')) {
        fetch('/admin/clear-cache', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Cache wyczyszczony pomyślnie!');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Wystąpił błąd podczas czyszczenia cache');
        });
    }
}

function runBackup() {
    if (confirm('Utworzyć backup bazy danych?')) {
        alert('Funkcja backupu w trakcie implementacji...');
        // W rzeczywistej aplikacji tutaj byłby kod tworzący backup
    }
}
</script>

<style>
    .form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
    }
    
    .card-title {
        color: #333;
        font-weight: 600;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }
    
    .list-unstyled li {
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .list-unstyled li:last-child {
        border-bottom: none;
    }
</style>
@endsection