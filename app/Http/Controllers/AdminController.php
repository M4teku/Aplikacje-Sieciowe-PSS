<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function users(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $userRoles = Session::get('user_roles', []);
        
        // TYLKO ADMIN
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('home')->with('error', 'Tylko administrator może zarządzać użytkownikami.');
        }
        
        $query = User::with('roles');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('login', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $users = $query->paginate(20);
        $roles = Role::where('is_active', true)->get();
        
        return view('admin.users', compact('users', 'roles'));
    }
    
    public function updateUserRole(Request $request, $userId)
    {
        if (!Session::has('user_id')) {
            return response()->json(['error' => 'Brak uprawnień'], 403);
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return response()->json(['error' => 'Tylko administrator może zmieniać role'], 403);
        }
        
        $user = User::findOrFail($userId);
        
        if ($user->id_user == Session::get('user_id') && $request->role_id == Role::where('name', 'Admin')->first()->id_role) {
            return response()->json(['error' => 'Nie możesz zmienić swojej własnej roli Admin'], 400);
        }
        
        $validated = $request->validate([
            'role_id' => 'required|exists:role,id_role',
            'action' => 'required|in:add,remove',
        ]);
        
        if ($validated['action'] == 'add') {
            $existing = DB::table('user_role')
                         ->where('id_user', $userId)
                         ->where('id_role', $validated['role_id'])
                         ->exists();
            
            if (!$existing) {
                DB::table('user_role')->insert([
                    'id_user' => $userId,
                    'id_role' => $validated['role_id'],
                    'assigned_at' => now(),
                ]);
            }
        } else {
            // Zapobiegaj usunięciu ostatniej roli Czytelnik
            $userRolesCount = DB::table('user_role')
                ->where('id_user', $userId)
                ->count();
            
            if ($userRolesCount <= 1) {
                return response()->json(['error' => 'Użytkownik musi mieć przynajmniej jedną rolę'], 400);
            }
            
            DB::table('user_role')
              ->where('id_user', $userId)
              ->where('id_role', $validated['role_id'])
              ->delete();
        }
        
        return response()->json(['success' => 'Rola zaktualizowana']);
    }
    
    public function resetPassword(Request $request, $userId)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('admin.users')->with('error', 'Brak uprawnień.');
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('admin.users')->with('error', 'Brak uprawnień.');
        }
        
        $user = User::findOrFail($userId);
        
        $newPassword = Str::random(10);
        
        $user->password = Hash::make($newPassword);
        $user->save();
        
        return back()->with('success', 'Hasło zresetowane. Nowe hasło: ' . $newPassword);
    }
    
    public function toggleUserStatus($userId)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('admin.users')->with('error', 'Brak uprawnień.');
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('admin.users')->with('error', 'Brak uprawnień.');
        }
        
        $user = User::findOrFail($userId);
        
        if ($user->id_user == Session::get('user_id')) {
            return back()->with('error', 'Nie możesz dezaktywować swojego własnego konta.');
        }
        
        if ($user->roles()->where('name', '!=', 'Czytelnik')->exists()) {
            // Usuń wszystkie role oprócz Czytelnik
            DB::table('user_role')
                ->where('id_user', $userId)
                ->where('id_role', '!=', Role::where('name', 'Czytelnik')->first()->id_role)
                ->delete();
            $message = 'Uprawnienia użytkownika zredukowane do roli Czytelnik';
        } else {
            // Przywróć podstawowe role
            $readerRole = Role::where('name', 'Czytelnik')->first();
            if ($readerRole) {
                // Upewnij się że ma rolę Czytelnik
                $hasReader = DB::table('user_role')
                    ->where('id_user', $userId)
                    ->where('id_role', $readerRole->id_role)
                    ->exists();
                
                if (!$hasReader) {
                    DB::table('user_role')->insert([
                        'id_user' => $userId,
                        'id_role' => $readerRole->id_role,
                        'assigned_at' => now(),
                    ]);
                }
            }
            $message = 'Konto użytkownika aktywowane z podstawową rolą';
        }
        
        return back()->with('success', $message);
    }
    
    public function statistics()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('home')->with('error', 'Tylko administrator może przeglądać statystyki.');
        }
        
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'total_reviews' => DB::table('review')->count(),
            'active_today' => User::whereDate('created_at', now()->toDateString())->count(),
            'avg_books_per_user' => User::count() > 0 ? DB::table('user_book')->count() / User::count() : 0,
        ];
        
        $recentUsers = User::with('roles')->orderBy('created_at', 'desc')->take(5)->get();
        $recentBooks = Book::with('creator')->orderBy('created_at', 'desc')->take(5)->get();
        $topBooks = Book::select('book.*')
            ->selectRaw('(SELECT COUNT(*) FROM user_book WHERE user_book.id_book = book.id_book) as tracking_count')
            ->orderBy('tracking_count', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.statistics', compact('stats', 'recentUsers', 'recentBooks', 'topBooks'));
    }
    
    public function roles()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('home')->with('error', 'Tylko administrator może zarządzać rolami.');
        }
        
        $roles = Role::all();
        
        return view('admin.roles', compact('roles'));
    }
    
    public function addRole(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('admin.roles')->with('error', 'Brak uprawnień.');
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('admin.roles')->with('error', 'Brak uprawnień.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:60|unique:role,name',
        ]);
        
        Role::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);
        
        return back()->with('success', 'Rola dodana pomyślnie');
    }
    
    public function toggleRoleStatus($roleId)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('admin.roles')->with('error', 'Brak uprawnień.');
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('admin.roles')->with('error', 'Brak uprawnień.');
        }
        
        $role = Role::findOrFail($roleId);
        
        $basicRoles = ['Admin', 'Moderator', 'Czytelnik'];
        if (in_array($role->name, $basicRoles)) {
            return back()->with('error', 'Nie można dezaktywować podstawowych ról systemowych.');
        }
        
        $role->is_active = !$role->is_active;
        $role->save();
        
        $status = $role->is_active ? 'aktywowana' : 'dezaktywowana';
        
        return back()->with('success', "Rola {$role->name} {$status}");
    }
    
    // DODAJ TĘ METODĘ DO NAPRAWY RÓL
    public function fixRolesAndPermissions()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $userRoles = Session::get('user_roles', []);
        if (!in_array('Admin', $userRoles)) {
            return redirect()->route('home')->with('error', 'Tylko administrator może naprawiać role.');
        }
        
        // 1. Upewnij się że podstawowe role istnieją
        $basicRoles = ['Admin', 'Moderator', 'Czytelnik'];
        foreach ($basicRoles as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                Role::create([
                    'name' => $roleName,
                    'is_active' => true,
                ]);
                echo "Utworzono rolę: $roleName<br>";
            }
        }
        
        // 2. Sprawdź czy admin ma rolę Admin
        $adminUser = User::where('login', 'admin')->first();
        $adminRole = Role::where('name', 'Admin')->first();
        
        if ($adminUser && $adminRole) {
            $hasAdminRole = DB::table('user_role')
                ->where('id_user', $adminUser->id_user)
                ->where('id_role', $adminRole->id_role)
                ->exists();
            
            if (!$hasAdminRole) {
                DB::table('user_role')->insert([
                    'id_user' => $adminUser->id_user,
                    'id_role' => $adminRole->id_role,
                    'assigned_at' => now(),
                ]);
                echo "Przypisano rolę Admin do użytkownika admin<br>";
            }
        }
        
        // 3. Sprawdź czy moderator ma rolę Moderator
        $moderatorUser = User::where('login', 'moderator')->first();
        $moderatorRole = Role::where('name', 'Moderator')->first();
        
        if ($moderatorUser && $moderatorRole) {
            $hasModeratorRole = DB::table('user_role')
                ->where('id_user', $moderatorUser->id_user)
                ->where('id_role', $moderatorRole->id_role)
                ->exists();
            
            if (!$hasModeratorRole) {
                DB::table('user_role')->insert([
                    'id_user' => $moderatorUser->id_user,
                    'id_role' => $moderatorRole->id_role,
                    'assigned_at' => now(),
                ]);
                echo "Przypisano rolę Moderator do użytkownika moderator<br>";
            }
        }
        
        // 4. Upewnij się że każdy użytkownik ma rolę Czytelnik
        $readerRole = Role::where('name', 'Czytelnik')->first();
        if ($readerRole) {
            $users = DB::table('user')->get();
            foreach ($users as $user) {
                $hasReader = DB::table('user_role')
                    ->where('id_user', $user->id_user)
                    ->where('id_role', $readerRole->id_role)
                    ->exists();
                
                if (!$hasReader) {
                    DB::table('user_role')->insert([
                        'id_user' => $user->id_user,
                        'id_role' => $readerRole->id_role,
                        'assigned_at' => now(),
                    ]);
                    echo "Dodano rolę Czytelnik dla użytkownika ID: {$user->id_user}<br>";
                }
            }
        }
        
        echo "<hr><strong>Naprawa zakończona!</strong><br>";
        echo "<a href='/admin/users'>Przejdź do panelu admina</a>";
    }
}