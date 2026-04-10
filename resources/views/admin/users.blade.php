@extends('layouts.app')

@section('title', 'Zarządzanie użytkownikami - BookTracker')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-people"></i> Zarządzanie użytkownikami</h1>
                    <p class="lead text-muted">Zarządzaj użytkownikami i ich rolami</p>
                </div>
                <div>
                    <a href="/admin/fix-roles" class="btn btn-warning me-2" onclick="return confirm('Naprawić role systemowe?')">
                        <i class="bi bi-tools"></i> Napraw role
                    </a>
                    <a href="{{ route('admin.statistics') }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-graph-up"></i> Statystyki
                    </a>
                    <a href="{{ route('admin.roles') }}" class="btn btn-outline-warning">
                        <i class="bi bi-shield-check"></i> Role
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formularz wyszukiwania -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Szukaj użytkownika (login lub email)..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Szukaj
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Wyczyść
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela użytkowników -->
    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Login</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Data rejestracji</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id_user }}</td>
                                <td>{{ $user->login }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-{{ $role->name == 'Admin' ? 'danger' : ($role->name == 'Moderator' ? 'warning' : 'secondary') }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <!-- Dodawanie/usuwanie ról -->
                                        <button type="button" class="btn btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#roleModal{{ $user->id_user }}">
                                            <i class="bi bi-person-badge"></i> Role
                                        </button>
                                        
                                        <!-- Reset hasła -->
                                        <a href="{{ route('admin.resetPassword', $user->id_user) }}" 
                                           class="btn btn-outline-warning"
                                           onclick="return confirm('Zresetować hasło dla {{ $user->login }}? Nowe hasło zostanie wyświetlone.')">
                                            <i class="bi bi-key"></i> Reset
                                        </a>
                                        
                                        <!-- Aktywacja/deaktywacja -->
                                        <a href="{{ route('admin.toggleUserStatus', $user->id_user) }}" 
                                           class="btn btn-outline-{{ $user->roles->count() > 0 ? 'danger' : 'success' }}"
                                           onclick="return confirm('{{ $user->roles->count() > 0 ? 'Dezaktywować konto ' . $user->login . '?' : 'Aktywować konto ' . $user->login . '?' }}')">
                                            <i class="bi bi-{{ $user->roles->count() > 0 ? 'x-circle' : 'check-circle' }}"></i>
                                            {{ $user->roles->count() > 0 ? 'Deaktywuj' : 'Aktywuj' }}
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal dla ról -->
                            <div class="modal fade" id="roleModal{{ $user->id_user }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Zarządzanie rolami: {{ $user->login }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <strong>Aktualne role:</strong>
                                                @if($user->roles->count() > 0)
                                                    @foreach($user->roles as $role)
                                                        <span class="badge bg-{{ $role->name == 'Admin' ? 'danger' : ($role->name == 'Moderator' ? 'warning' : 'secondary') }} me-1">
                                                            {{ $role->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Brak przypisanych ról</span>
                                                @endif
                                            </div>
                                            
                                            <hr>
                                            
                                            <div class="mb-3">
                                                <strong>Dodaj rolę:</strong>
                                                @foreach($roles->where('is_active', true) as $role)
                                                    @if(!$user->roles->contains('id_role', $role->id_role))
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span>{{ $role->name }}</span>
                                                        <button type="button" class="btn btn-sm btn-success add-role-btn"
                                                                data-user-id="{{ $user->id_user }}"
                                                                data-role-id="{{ $role->id_role }}"
                                                                data-role-name="{{ $role->name }}">
                                                            <i class="bi bi-plus"></i> Dodaj
                                                        </button>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            
                                            @if($user->roles->count() > 0)
                                            <div class="mb-3">
                                                <strong>Usuń rolę:</strong>
                                                @foreach($user->roles as $role)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span>{{ $role->name }}</span>
                                                        <button type="button" class="btn btn-sm btn-danger remove-role-btn"
                                                                data-user-id="{{ $user->id_user }}"
                                                                data-role-id="{{ $role->id_role }}"
                                                                data-role-name="{{ $role->name }}">
                                                            <i class="bi bi-trash"></i> Usuń
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginacja -->
                @if($users->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-people" style="font-size: 4rem; color: #dee2e6;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Nie znaleziono użytkowników</h4>
                    <p class="text-muted mb-4">Spróbuj zmienić kryteria wyszukiwania</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dodawanie roli
    document.querySelectorAll('.add-role-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const roleId = this.getAttribute('data-role-id');
            const roleName = this.getAttribute('data-role-name');
            
            if (confirm(`Dodać rolę "${roleName}" temu użytkownikowi?`)) {
                fetch(`/admin/users/${userId}/role`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        role_id: roleId,
                        action: 'add'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Błąd: ' + data.error);
                    } else {
                        alert('Sukces: ' + data.success);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Wystąpił błąd podczas dodawania roli');
                });
            }
        });
    });
    
    // Usuwanie roli
    document.querySelectorAll('.remove-role-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const roleId = this.getAttribute('data-role-id');
            const roleName = this.getAttribute('data-role-name');
            
            if (confirm(`Usunąć rolę "${roleName}" z tego użytkownika?`)) {
                fetch(`/admin/users/${userId}/role`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        role_id: roleId,
                        action: 'remove'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Błąd: ' + data.error);
                    } else {
                        alert('Sukces: ' + data.success);
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Wystąpił błąd podczas usuwania roli');
                });
            }
        });
    });
});
</script>

<style>
    .badge {
        font-weight: 500;
        padding: 5px 10px;
        font-size: 0.85rem;
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection