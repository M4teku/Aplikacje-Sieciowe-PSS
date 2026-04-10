@extends('layouts.app')

@section('title', 'Logi systemowe - BookTracker')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="bi bi-list-check"></i> Logi systemowe</h1>
                    <p class="lead text-muted">Przegląd logów systemowych i błędów</p>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-danger me-2" onclick="clearLogs()">
                        <i class="bi bi-trash"></i> Wyczyść logi
                    </button>
                    <a href="{{ route('admin.settings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-gear"></i> Ustawienia
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtry logów -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Poziom logów</label>
                    <select class="form-select">
                        <option value="all" selected>Wszystkie</option>
                        <option value="error">Błędy</option>
                        <option value="warning">Ostrzeżenia</option>
                        <option value="info">Informacje</option>
                    </select>
                </div>
                
                <div class="col-md-5">
                    <label class="form-label">Szukaj w logach</label>
                    <input type="text" class="form-control" placeholder="Wpisz frazę...">
                </div>
                
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-grid w-100 gap-2">
                        <button type="submit" class="btn btn-primary">Filtruj</button>
                        <button type="reset" class="btn btn-outline-secondary">Wyczyść</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Logi -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Ostatnie 100 wpisów</h5>
                <small class="text-muted">Plik: storage/logs/laravel.log</small>
            </div>
            
            @if(count($logs) > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Data i czas</th>
                                <th style="width: 100px;">Poziom</th>
                                <th>Wiadomość</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            <tr>
                                <td class="text-muted" style="font-size: 0.85rem;">
                                    {{ substr($log, 1, 19) }}
                                </td>
                                <td>
                                    @php
                                        $levelClass = 'secondary';
                                        if (str_contains($log, '.ERROR')) {
                                            $levelClass = 'danger';
                                        } elseif (str_contains($log, '.WARNING')) {
                                            $levelClass = 'warning';
                                        } elseif (str_contains($log, '.INFO')) {
                                            $levelClass = 'info';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $levelClass }}">
                                        @if(str_contains($log, '.ERROR')) BŁĄD
                                        @elseif(str_contains($log, '.WARNING')) OSTRZEŻENIE
                                        @elseif(str_contains($log, '.INFO')) INFO
                                        @else INNY
                                        @endif
                                    </span>
                                </td>
                                <td style="font-family: 'Courier New', monospace; font-size: 0.85rem;">
                                    {{ substr($log, 27) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-journal-check" style="font-size: 4rem; color: #dee2e6;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Brak logów systemowych</h4>
                    <p class="text-muted">System nie zarejestrował jeszcze żadnych zdarzeń</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function clearLogs() {
    if (confirm('Czy na pewno chcesz wyczyścić wszystkie logi systemowe?')) {
        fetch('/admin/clear-logs', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || 'Logi wyczyszczone pomyślnie!');
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Wystąpił błąd podczas czyszczenia logów');
        });
    }
}

// Automatyczne odświeżanie logów co 30 sekund
setTimeout(() => {
    location.reload();
}, 30000);
</script>

<style>
    .table-sm td, .table-sm th {
        padding: 0.5rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 4px 8px;
        font-size: 0.75rem;
    }
    
    .card-title {
        color: #333;
        font-weight: 600;
    }
</style>
@endsection