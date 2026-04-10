<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('user_id')) {
            return redirect()->route('home')->with('info', 'Jesteś już zalogowany!');
        }
        
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string|min:3|max:60',
            'password' => 'required|string|min:8',
        ], [
            'login.required' => 'Login jest wymagany',
            'login.min' => 'Login musi mieć przynajmniej 3 znaki',
            'password.required' => 'Hasło jest wymagane',
            'password.min' => 'Hasło musi mieć przynajmniej 8 znaków',
        ]);
        
        $user = User::where('login', $request->login)->first();
        
        if (!$user) {
            return back()->withErrors(['login' => 'Nieprawidłowy login lub hasło'])->withInput();
        }
        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Nieprawidłowy login lub hasło'])->withInput();
        }
        
        // Pobierz role Z BAZY (nie z Eloquent - pewniejsze)
        $roles = DB::table('user_role')
            ->join('role', 'user_role.id_role', '=', 'role.id_role')
            ->where('user_role.id_user', $user->id_user)
            ->where('role.is_active', 1)
            ->pluck('role.name')
            ->toArray();
        
        // DEBUG: Sprawdź jakie role pobraliśmy
        // dd(["user_id" => $user->id_user, "roles" => $roles]);
        
        Session::put('user_id', $user->id_user);
        Session::put('user_login', $user->login);
        Session::put('user_email', $user->email);
        Session::put('user_roles', $roles);
        
        // ZAPISZ SESJĘ NATYCHMIAST
        Session::save();
        
        return redirect()->route('home')->with('success', 'Zalogowano pomyślnie! Witaj ' . $user->login . '! Role: ' . implode(', ', $roles));
    }
    
    public function showRegister()
    {
        if (Session::has('user_id')) {
            return redirect()->route('home')->with('info', 'Jesteś już zalogowany!');
        }
        
        return view('auth.register');
    }
    
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'login' => 'required|string|min:3|max:60|unique:user,login',
                'email' => 'required|email|max:100|unique:user,email',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ], [
                'login.required' => 'Login jest wymagany',
                'login.min' => 'Login musi mieć przynajmniej 3 znaki',
                'login.unique' => 'Ten login jest już zajęty',
                'email.required' => 'Email jest wymagany',
                'email.email' => 'Podaj poprawny adres email',
                'email.unique' => 'Ten email jest już zarejestrowany',
                'password.required' => 'Hasło jest wymagane',
                'password.min' => 'Hasło musi mieć przynajmniej 8 znaków',
                'password.confirmed' => 'Hasła nie są identyczne',
                'password_confirmation.required' => 'Potwierdzenie hasła jest wymagane',
            ]);
            
            $forbiddenLogins = ['admin', 'administrator', 'root'];
            foreach ($forbiddenLogins as $forbidden) {
                if (stripos($validated['login'], $forbidden) !== false) {
                    return back()->withErrors([
                        'login' => 'Login zawiera niedozwolone słowo'
                    ])->withInput();
                }
            }
            
            DB::beginTransaction();
            
            $user = User::create([
                'login' => $validated['login'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            $readerRole = Role::where('name', 'Czytelnik')->first();
            
            if (!$readerRole) {
                // Jeśli rola Czytelnik nie istnieje, stwórz ją
                $readerRole = Role::create([
                    'name' => 'Czytelnik',
                    'is_active' => true,
                ]);
            }
            
            // TYLKO rola Czytelnik dla nowych użytkowników!
            DB::table('user_role')->insert([
                'id_user' => $user->id_user,
                'id_role' => $readerRole->id_role,
                'assigned_at' => now(),
            ]);
            
            DB::commit();
            
            // Pobierz wszystkie role użytkownika (tylko Czytelnik)
            $allRoles = DB::table('user_role')
                ->join('role', 'user_role.id_role', '=', 'role.id_role')
                ->where('user_role.id_user', $user->id_user)
                ->where('role.is_active', 1)
                ->pluck('role.name')
                ->toArray();
            
            Session::put('user_id', $user->id_user);
            Session::put('user_login', $user->login);
            Session::put('user_email', $user->email);
            Session::put('user_roles', $allRoles);
            Session::save();
            
            return redirect()->route('home')->with('success', 'Rejestracja zakończona sukcesem! Twoje role: ' . implode(', ', $allRoles));
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Błąd rejestracji: ' . $e->getMessage()
            ])->withInput();
        }
    }
    
    public function logout()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Nie jesteś zalogowany.');
        }
        
        $login = Session::get('user_login', 'Użytkowniku');
        
        Session::flush();
        
        return redirect()->route('home')->with('success', 'Wylogowano pomyślnie. Do zobaczenia ' . $login . '!');
    }
    
    public function profile()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Musisz się zalogować.');
        }
        
        $user = User::with(['roles', 'reviews', 'trackedBooks'])
                    ->find(Session::get('user_id'));
        
        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Sesja wygasła.');
        }
        
        return view('profile.show', [
            'user' => $user,
            'is_admin' => in_array('Admin', Session::get('user_roles', [])),
            'is_moderator' => in_array('Moderator', Session::get('user_roles', [])),
        ]);
    }
}