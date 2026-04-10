@extends('layouts.app')

@section('title', 'Edytuj książkę - BookTracker')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card book-card">
            <div class="card-body">
                <h2><i class="bi bi-pencil"></i> Edytuj książkę</h2>
                
                <form action="{{ route('books.update', $book->id_book) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Tytuł książki *</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $book->title) }}" required maxlength="256">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Autor *</label>
                        <input type="text" name="author" class="form-control" value="{{ old('author', $book->author) }}" required maxlength="256">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Gatunek *</label>
                        <input type="text" name="genre" class="form-control" value="{{ old('genre', $book->genre) }}" required maxlength="100" list="genres">
                        <datalist id="genres">
                            <option value="Fantasy">
                            <option value="Science Fiction">
                            <option value="Kryminał">
                            <option value="Thriller">
                            <option value="Romans">
                            <option value="Historyczna">
                            <option value="Biografia">
                            <option value="Poradnik">
                            <option value="Dramat">
                            <option value="Komedia">
                        </datalist>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Opis książki *</label>
                        <textarea name="description" class="form-control" rows="6" required maxlength="4000">{{ old('description', $book->description) }}</textarea>
                        <small class="text-muted">Maksymalnie 4000 znaków</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('books.show', $book->id_book) }}" class="btn btn-secondary">
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