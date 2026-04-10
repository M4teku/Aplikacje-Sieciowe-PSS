<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserBookController;
use Illuminate\Support\Facades\DB;

// Do Ajaxa
Route::get('/books/filter', [BookController::class, 'filter'])->name('books.filter');

// ========== DEBUG TEST ==========
Route::get('/debug-create', function() {
    // Sprawdź czy BookController istnieje
    if (!class_exists('App\Http\Controllers\BookController')) {
        return "ERROR: BookController nie istnieje!";
    }
    
    // Sprawdź czy metoda create istnieje
    $controller = new App\Http\Controllers\BookController();
    if (!method_exists($controller, 'create')) {
        return "ERROR: Metoda create() nie istnieje w BookController!";
    }
    
    // Sprawdź czy plik widoku istnieje
    $viewPath = resource_path('views/books/create.blade.php');
    if (!file_exists($viewPath)) {
        return "ERROR: Plik create.blade.php nie istnieje!<br>Ścieżka: " . $viewPath;
    }
    
    return "SUCCESS: Wszystko OK!<br>
            <a href='/books/create'>Przejdź do /books/create</a><br>
            <a href='/debug-create-force'>FORCE TEST</a>";
});


Route::get('/debug-create-force', function() {
    return view('books.create');
});


// Public routes
Route::get('/', [BookController::class, 'index'])->name('home');
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Wszystkie chronione trasy 
// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::get('/profile/{id?}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// User books (tracking) - moje ksiazki (lista czytania)
Route::get('/mybooks', [UserBookController::class, 'myBooks'])->name('userbooks.mybooks');
Route::post('/books/{bookId}/track', [UserBookController::class, 'store'])->name('userbooks.store');
Route::put('/books/{bookId}/track', [UserBookController::class, 'update'])->name('userbooks.update');
Route::delete('/books/{bookId}/track', [UserBookController::class, 'destroy'])->name('userbooks.destroy');
Route::get('/mybooks/{bookId}/edit', [UserBookController::class, 'edit'])->name('userbooks.edit');

// Reviews
Route::get('/books/{book}/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');


// 1. CREATE)
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/books', [BookController::class, 'store'])->name('books.store');

// 2. EDIT
Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');

// 3. DELETE
Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

// 4. Pokaż ksiązki
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');


// Admin routes (kontrolery same sprawdzają uprawnienia)
Route::prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{userId}/role', [AdminController::class, 'updateUserRole'])->name('admin.updateUserRole');
    Route::get('/users/{userId}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.resetPassword');
    Route::get('/users/{userId}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.toggleUserStatus');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    Route::get('/roles', [AdminController::class, 'roles'])->name('admin.roles');
    Route::post('/roles', [AdminController::class, 'addRole'])->name('admin.addRole');
    Route::get('/roles/{roleId}/toggle-status', [AdminController::class, 'toggleRoleStatus'])->name('admin.toggleRoleStatus');
    
    // FIX ROLE DLA MODERATORA
    Route::get('/fix-moderator-role', function() {
        // Dodaj rolę Moderator jeśli nie istnieje
        DB::table('role')->insertOrIgnore([
            'name' => 'Moderator', 
            'is_active' => 1, 
            'created_at' => now(), 
            'updated_at' => now()
        ]);
        
        // Znajdź użytkownika moderator
        $moderator = DB::table('user')->where('login', 'moderator')->first();
        $moderatorRole = DB::table('role')->where('name', 'Moderator')->first();
        
        if ($moderator && $moderatorRole) {
            // Przypisz rolę jeśli jeszcze nie ma
            DB::table('user_role')->insertOrIgnore([
                'id_user' => $moderator->id_user,
                'id_role' => $moderatorRole->id_role,
                'assigned_at' => now()
            ]);
            
            return "Rola Moderator przypisana do użytkownika 'moderator'!<br>
                   <a href='/login'>Zaloguj się ponownie</a>";
        }
        
        return "Błąd: Nie znaleziono użytkownika 'moderator' lub roli 'Moderator'";
    })->name('admin.fixModerator');
    
    // Trasa do naprawy wszystkich ról
    Route::get('/fix-roles', [AdminController::class, 'fixRolesAndPermissions'])->name('admin.fixRoles');
});

// Debug routes (możesz usunąć później)
Route::get('/setup-test-users', function() {
    // Upewnij się że podstawowe role istnieją
    $roles = ['Admin', 'Moderator', 'Czytelnik'];
    foreach ($roles as $roleName) {
        DB::table('role')->insertOrIgnore([
            'name' => $roleName,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    
    // Stwórz admina jeśli nie istnieje
    DB::table('user')->insertOrIgnore([
        'login' => 'admin',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        'email' => 'admin@booktracker.pl',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // Stwórz moderatora jeśli nie istnieje
    DB::table('user')->insertOrIgnore([
        'login' => 'moderator',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'email' => 'moderator@booktracker.pl',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // Przypisz role
    $admin = DB::table('user')->where('login', 'admin')->first();
    $moderator = DB::table('user')->where('login', 'moderator')->first();
    $adminRole = DB::table('role')->where('name', 'Admin')->first();
    $moderatorRole = DB::table('role')->where('name', 'Moderator')->first();
    $readerRole = DB::table('role')->where('name', 'Czytelnik')->first();
    
    if ($admin && $adminRole) {
        DB::table('user_role')->insertOrIgnore([
            'id_user' => $admin->id_user,
            'id_role' => $adminRole->id_role,
            'assigned_at' => now()
        ]);
    }
    
    if ($moderator && $moderatorRole) {
        DB::table('user_role')->insertOrIgnore([
            'id_user' => $moderator->id_user,
            'id_role' => $moderatorRole->id_role,
            'assigned_at' => now()
        ]);
    }
    
    // Każdy użytkownik dostaje też rolę Czytelnik
    if ($readerRole) {
        $users = DB::table('user')->get();
        foreach ($users as $user) {
            DB::table('user_role')->insertOrIgnore([
                'id_user' => $user->id_user,
                'id_role' => $readerRole->id_role,
                'assigned_at' => now()
            ]);
        }
    }
    
    echo "<h3>Użytkownicy testowi utworzeni:</h3>";
    echo "1. <strong>Admin</strong><br>";
    echo "   Login: admin<br>";
    echo "   Hasło: admin123<br>";
    echo "   Role: Admin, Czytelnik<br><br>";
    
    echo "2. <strong>Moderator</strong><br>";
    echo "   Login: moderator<br>";
    echo "   Hasło: password<br>";
    echo "   Role: Moderator, Czytelnik<br><br>";
    
    echo "<hr><a href='/login'>Przejdź do logowania</a>";
});

Route::get('/debug-users', function() {
    $users = DB::table('user')
        ->leftJoin('user_role', 'user.id_user', '=', 'user_role.id_user')
        ->leftJoin('role', 'user_role.id_role', '=', 'role.id_role')
        ->select('user.*', DB::raw('GROUP_CONCAT(role.name) as roles'))
        ->groupBy('user.id_user', 'user.login', 'user.password', 'user.email', 'user.created_at', 'user.updated_at')
        ->get();
    
    $html = "<h3>Użytkownicy w systemie:</h3>";
    
    foreach ($users as $user) {
        $html .= "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
        $html .= "<strong>{$user->login}</strong> (ID: {$user->id_user})<br>";
        $html .= "Email: {$user->email}<br>";
        $html .= "Role: " . ($user->roles ?: 'Brak ról') . "<br>";
        $html .= "</div>";
    }
    
    return $html;
});

Route::get('/reading-statuses', function() {
    return \App\Models\ReadingStatus::all();
});

Route::get('/test-books-create', function() {
    return "Test: /books/create powinno działać. Kontroler: " . 
           route('books.create') . "<br>" .
           "<a href='/books/create'>Przejdź do /books/create</a>";
});