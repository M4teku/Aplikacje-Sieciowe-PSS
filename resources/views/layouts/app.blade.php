<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookTracker @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --dark-brown: #3C2F2F;
            --medium-brown: #4A3728;
            --light-brown: #5D4037;
            --wood-brown: #6D4C41;
            --parchment: #F5E6CA;
            --gold: #D4AF37;
            --bronze: #CD7F32;
            --rust: #B7410E;
            --cream: #FAF3E0;
        }
        
        body {
            background-color: var(--cream);
            color: var(--dark-brown);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        /* NAVBAR */
        .navbar {
            background: linear-gradient(135deg, var(--dark-brown) 0%, var(--medium-brown) 100%);
            border-bottom: 3px solid var(--gold);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            padding: 10px 0;
        }
        
        .navbar-brand {
            color: var(--gold) !important;
            font-family: Georgia, serif;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 5px 0;
        }
        
        .navbar-brand:hover {
            color: #FFD700 !important;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
        }
        
        .nav-link {
            color: var(--parchment) !important;
            font-weight: 500;
            padding: 8px 15px !important;
            border-radius: 6px;
            transition: all 0.3s;
            margin: 0 5px;
        }
        
        .nav-link:hover {
            background: rgba(212, 175, 55, 0.15);
            color: var(--gold) !important;
            transform: translateY(-2px);
        }
        
        /* BUTTONS */
        .btn-primary {
            background: linear-gradient(135deg, var(--bronze) 0%, var(--rust) 100%);
            border: 2px solid var(--gold);
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--rust) 0%, var(--bronze) 100%);
            border-color: #FFD700;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            border: 2px solid #4CAF50;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, var(--bronze) 0%, var(--rust) 100%);
            border: 2px solid var(--gold);
            color: white;
        }
        
        .btn-outline-warning {
            border-color: var(--gold);
            color: var(--gold);
        }
        
        .btn-outline-warning:hover {
            background-color: var(--gold);
            color: var(--dark-brown);
        }
        
        /* CARDS */
        .book-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8f4e9 100%);
            border: 2px solid var(--wood-brown);
            border-radius: 12px;
            box-shadow: 8px 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        
        .book-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--gold), var(--bronze), var(--gold));
        }
        
        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 12px 16px 30px rgba(0,0,0,0.15);
            border-color: var(--gold);
        }
        
        .card-title {
            color: var(--dark-brown) !important;
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .card-subtitle {
            color: var(--light-brown) !important;
            font-weight: 500;
        }
        
        /* ALERTS */
        .alert-success {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.9) 0%, rgba(33, 136, 56, 0.9) 100%);
            border: 2px solid #28a745;
            color: white;
            border-radius: 10px;
            border-left: 6px solid #4CAF50;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.9) 0%, rgba(200, 35, 51, 0.9) 100%);
            border: 2px solid #dc3545;
            color: white;
            border-radius: 10px;
            border-left: 6px solid #f44336;
        }
        
        /* FORMS */
        .form-control, .form-select {
            background-color: rgba(245, 230, 202, 0.8);
            border: 2px solid var(--wood-brown);
            color: var(--dark-brown);
            border-radius: 8px;
            padding: 12px;
            font-size: 1rem;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: var(--parchment);
            border-color: var(--gold);
            box-shadow: 0 0 0 0.3rem rgba(212, 175, 55, 0.25);
            color: var(--dark-brown);
        }
        
        /* HEADINGS */
        h1, h2, h3, h4, h5 {
            color: var(--gold);
            font-family: Georgia, serif;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        h1::after, h2::after, h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }
        
        /* FOOTER */
        footer {
            background: linear-gradient(135deg, var(--dark-brown) 0%, var(--medium-brown) 100%);
            border-top: 3px solid var(--bronze);
            margin-top: 50px;
            padding: 25px 0;
            text-align: center;
            color: var(--parchment);
        }
        
        /* BADGES */
        .badge {
            font-family: 'Segoe UI', sans-serif;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .bg-warning {
            background: linear-gradient(135deg, var(--bronze) 0%, var(--rust) 100%) !important;
            color: white !important;
        }
        
        .bg-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
        }
        
        .bg-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%) !important;
        }
        
        /* PAGINATION */
        .pagination .page-link {
            background-color: var(--medium-brown);
            border-color: var(--wood-brown);
            color: var(--parchment);
            font-weight: 500;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--bronze) 0%, var(--rust) 100%);
            border-color: var(--gold);
        }
        
        /* UTILITIES */
        .lead {
            color: var(--light-brown);
            font-size: 1.1rem;
        }
        
        .text-muted {
            color: #8D6E63 !important;
        }
        
        a {
            color: var(--rust);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        a:hover {
            color: var(--gold);
        }
        
        /* CONTAINER */
        .container {
            max-width: 1200px;
            padding: 0 20px;
        }
        
        /* DROPDOWN */
        .dropdown-menu {
            background: linear-gradient(135deg, #ffffff 0%, #f8f4e9 100%);
            border: 2px solid var(--wood-brown);
            border-radius: 10px;
            box-shadow: 5px 5px 15px rgba(0,0,0,0.1);
        }
        
        .dropdown-item {
            color: var(--dark-brown);
            padding: 10px 15px;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background: rgba(212, 175, 55, 0.1);
            color: var(--rust);
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-book-half me-2"></i>BookTracker
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Przeglądaj książki -->
                    <li class="nav-item">
                        <a class="nav-link" href="/books">
                            <i class="bi bi-book me-1"></i> Przeglądaj książki
                        </a>
                    </li>
                    
                    @if(session('user_id'))
                        <!-- MOJE KSIĄŻKI -->
                        <li class="nav-item">
                            <a class="nav-link" href="/mybooks">
                                <i class="bi bi-bookmarks me-1"></i> Moje książki
                            </a>
                        </li>
                        
                        @php
                            $userRoles = session('user_roles', []);
                            $hasModeratorRole = in_array('Moderator', $userRoles);
                            $hasAdminRole = in_array('Admin', $userRoles);
                        @endphp
                        
                        <!-- Dodaj książkę - TYLKO dla MODERATORÓW (Admin NIE!) -->
                        @if($hasModeratorRole && !$hasAdminRole)
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-warning btn-sm mx-2" href="{{ route('books.create') }}">
                                    <i class="bi bi-plus-circle me-1"></i> Dodaj książkę
                                </a>
                            </li>
                        @endif
                        
                        <!-- Panel admina - TYLKO dla ADMINÓW -->
                        @if($hasAdminRole)
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-danger btn-sm mx-2" href="/admin/users">
                                    <i class="bi bi-shield-check me-1"></i> Panel admina
                                </a>
                            </li>
                        @endif
                        
                        <!-- Dropdown użytkownika -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                <span>{{ session('user_login') }}</span>
                                @if($hasAdminRole)
                                    <span class="badge bg-danger ms-2">Admin</span>
                                @endif
                                @if($hasModeratorRole && !$hasAdminRole)
                                    <span class="badge bg-warning ms-1">Moderator</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="/profile">
                                        <i class="bi bi-person me-2"></i> Mój profil
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                
                                <li>
                                    <form method="POST" action="/logout" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Wyloguj się
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Niezalogowany -->
                        <li class="nav-item">
                            <a class="nav-link" href="/login">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Zaloguj się
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-light btn-sm ms-2" href="/register">
                                <i class="bi bi-person-plus me-1"></i> Rejestracja
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p class="mb-2">
                <i class="bi bi-book me-1"></i> BookTracker &copy; {{ date('Y') }}
            </p>
            <small class="text-muted">Twój osobisty system śledzenia czytania</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Automatyczne zamykanie alertów po 5 sekundach
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Aktywuje tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>
</body>
</html>