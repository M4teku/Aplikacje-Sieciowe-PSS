<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show($id = null)
    {
        if ($id === null) {
            if (!Session::has('user_id')) {
                return redirect()->route('login');
            }
            $id = Session::get('user_id');
        }
        
        $user = User::with(['reviews.book', 'roles'])->findOrFail($id);
        
        $stats = $this->calculateStats($id);
        
        $recentBooks = DB::table('user_book')
            ->join('book', 'user_book.id_book', '=', 'book.id_book')
            ->leftJoin('reading_status', 'user_book.id_status', '=', 'reading_status.id_status')
            ->where('user_book.id_user', $id)
            ->orderBy('user_book.created_at', 'desc')
            ->limit(3)
            ->select('book.*', 'user_book.id_status', 'reading_status.name as status_name')
            ->get();
        
        $totalBooks = DB::table('user_book')
            ->where('id_user', $id)
            ->count();
        
        return view('profile.show', [
            'user' => $user,
            'stats' => $stats,
            'recentBooks' => $recentBooks,
            'totalBooks' => $totalBooks
        ]);
    }
    
    private function calculateStats($userId)
    {
        $stats = [
            'total_books' => 0,
            'reading_now' => 0,
            'completed' => 0,
            'want_to_read' => 0,
            'abandoned' => 0,
            'average_rating' => 0,
        ];
        
        $userBooks = DB::table('user_book')
            ->where('id_user', $userId)
            ->get();
        
        foreach ($userBooks as $userBook) {
            $stats['total_books']++;
            
            if ($userBook->id_status == 1) {
                $stats['want_to_read']++;
            }
            if ($userBook->id_status == 2) {
                $stats['reading_now']++;
            }
            if ($userBook->id_status == 3) {
                $stats['completed']++;
            }
            if ($userBook->id_status == 4) {
                $stats['abandoned']++;
            }
        }
        
        $reviews = DB::table('review')
            ->where('id_user', $userId)
            ->get();
        
        if ($reviews->count() > 0) {
            $totalRating = 0;
            foreach ($reviews as $review) {
                $totalRating += $review->rating;
            }
            $stats['average_rating'] = round($totalRating / $reviews->count(), 2);
        }
        
        return $stats;
    }
    
    public function edit()
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $user = User::find(Session::get('user_id'));
        
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $user = User::find(Session::get('user_id'));
        
        $validated = $request->validate([
            'email' => 'required|email|max:100|unique:user,email,' . $user->id_user . ',id_user',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('profile.show')
                         ->with('success', 'Profil zaktualizowany!');
    }
}