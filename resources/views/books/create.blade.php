<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj książkę - BookTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .container { max-width: 800px; }
        h1 { color: #333; margin-bottom: 30px; }
        .form-control { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Dodaj nową książkę do bazy</h1>
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <form method="POST" action="{{ route('books.store') }}">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Tytuł książki *</label>
                <input type="text" name="title" class="form-control" required 
                       placeholder="np. Władca Pierścieni" maxlength="256">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Autor *</label>
                <input type="text" name="author" class="form-control" required 
                       placeholder="np. J.R.R. Tolkien" maxlength="256">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Gatunek *</label>
                <input type="text" name="genre" class="form-control" required 
                       placeholder="np. Fantasy" maxlength="100">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Opis książki *</label>
                <textarea name="description" class="form-control" rows="5" required 
                          placeholder="Opisz książkę..." maxlength="4000"></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <a href="{{ route('books.index') }}" class="btn btn-secondary">Anuluj</a>
                <button type="submit" class="btn btn-primary">Dodaj książkę do bazy</button>
            </div>
        </form>
        
        <hr class="my-4">
        
        <div class="alert alert-info">
            <h5>ℹ️ Informacja:</h5>
            <p>Książki dodane tutaj trafią do <strong>ogólnej bazy danych</strong> i będą widoczne dla wszystkich użytkowników.</p>
            <p>Tylko użytkownicy z rolą <strong>Moderator</strong> mogą dodawać książki.</p>
        </div>
    </div>
</body>
</html>