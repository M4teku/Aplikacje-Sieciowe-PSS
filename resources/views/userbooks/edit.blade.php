@extends('layouts.app')

@section('title', 'Edytuj książkę w liście - BookTracker')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card book-card">
            <div class="card-body">
                <h2><i class="bi bi-pencil"></i> Edytuj książkę w liście</h2>
                
                <div class="mb-4 p-3" style="background: rgba(245, 230, 202, 0.5); border-radius: 8px; border-left: 4px solid var(--bronze);">
                    <h4>{{ $userBook->title }}</h4>
                    <p class="lead mb-0" style="color: #5D4037;">{{ $userBook->author }}</p>
                </div>
                
                <form action="{{ route('userbooks.update', $userBook->id_book) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label" style="color: #5D4037; font-weight: 500;">Status czytania</label>
                        <select name="id_status" class="form-select" style="border: 2px solid var(--wood-brown); padding: 10px;" required>
                            <option value="">Wybierz status...</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id_status }}" 
                                        {{ $userBook->id_status == $status->id_status ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/mybooks" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Anuluj
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Zapisz zmiany
                        </button>
                    </div>
                </form>
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
    }
    
    .btn-primary {
        background: linear-gradient(to bottom, var(--bronze), var(--rust));
        border: 2px solid var(--gold);
        color: var(--parchment);
        font-weight: bold;
        padding: 10px 25px;
    }
    
    .btn-secondary {
        border: 2px solid var(--wood-brown);
        color: #5D4037;
        padding: 10px 25px;
    }
</style>
@endsection