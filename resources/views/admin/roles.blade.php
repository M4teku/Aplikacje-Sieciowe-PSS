@extends('layouts.app')

@section('title', 'Zarządzanie rolami - BookTracker')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-shield-check"></i> Zarządzanie rolami</h1>
                    <p class="lead text-muted">Dodawaj, edytuj i zarządzaj rolami systemowymi</p>
                </div>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Powrót do użytkowników
                </a>
            </div>
        </div>
    </div>

    <!-- Formularz dodawania nowej roli -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Dodaj nową rolę</h5>
            
            <form method="POST" action="{{ route('admin.addRole') }}" class="row g-3">
                @csrf
                <div class="col-md-8">
                    <input type="text" name="name" class="form-control" 
                           placeholder="Nazwa nowej roli (np. 'Recenzent', 'Wydawca')" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-circle"></i> Dodaj rolę
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista ról -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Lista ról w systemie</h5>
            
            @if($roles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa roli</th>
                                <th>Status</th>
                                <th>Liczba użytkowników</th>
                                <th>Utworzono</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id_role }}</td>
                                <td>
                                    <strong>{{ $role->name }}</strong>
                                    @if(in_array($role->name, ['Admin', 'Moderator', 'Czytelnik']))
                                        <span class="badge bg-info ms-2">Systemowa</span>
                                    @endif
                                </td>
                                <td>
                                    @if($role->is_active)
                                        <span class="badge bg-success">Aktywna</span>
                                    @else
                                        <span class="badge bg-danger">Nieaktywna</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $role->users->count() }} użytkowników
                                </td>
                                <td>{{ $role->created_at->format('d.m.Y') }}</td>
                                <td>
                                    @if(!in_array($role->name, ['Admin', 'Moderator', 'Czytelnik']))
                                        <a href="{{ route('admin.toggleRoleStatus', $role->id_role) }}" 
                                           class="btn btn-sm btn-{{ $role->is_active ? 'warning' : 'success' }}"
                                           onclick="return confirm('{{ $role->is_active ? 'Dezaktywować rolę ' . $role->name . '?' : 'Aktywować rolę ' . $role->name . '?' }}')">
                                            <i class="bi bi-{{ $role->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                            {{ $role->is_active ? 'Deaktywuj' : 'Aktywuj' }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-shield" style="font-size: 4rem; color: #dee2e6;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Brak ról w systemie</h4>
                    <p class="text-muted mb-4">Dodaj pierwszą rolę korzystając z formularza powyżej</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Informacje -->
    <div class="alert alert-info mt-4">
        <h5><i class="bi bi-info-circle"></i> Informacje o rolach</h5>
        <ul class="mb-0">
            <li><strong>Admin</strong> - pełny dostęp do systemu, zarządzanie użytkownikami, ale NIE MOŻE dodawać/edytować książek</li>
            <li><strong>Moderator</strong> - może dodawać, edytować i usuwać książki, edytować wszystkie recenzje</li>
            <li><strong>Czytelnik</strong> - podstawowa rola, dostęp do przeglądania książek i dodawania ich do swojej listy</li>
            <li>Role systemowe (Admin, Moderator, Czytelnik) nie mogą być dezaktywowane</li>
            <li>Rola "Czytelnik" jest automatycznie przypisywana każdemu nowemu użytkownikowi</li>
        </ul>
    </div>
</div>

<style>
    .badge.bg-info {
        background-color: #17a2b8 !important;
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
    
    .alert ul {
        margin-bottom: 0;
        padding-left: 20px;
    }
    
    .alert li {
        margin-bottom: 5px;
    }
</style>
@endsection