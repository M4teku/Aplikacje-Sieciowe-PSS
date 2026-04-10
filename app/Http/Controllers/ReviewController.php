<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReviewController extends Controller
{
    public function create($bookId)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $book = Book::findOrFail($bookId);
        
        $existingReview = Review::where('id_user', Session::get('user_id'))
                                ->where('id_book', $bookId)
                                ->first();
        
        if ($existingReview) {
            return redirect()->route('books.show', $bookId)
                             ->with('error', 'Już dodałeś recenzję dla tej książki.');
        }
        
        return view('reviews.create', compact('book'));
    }
    
    public function store(Request $request, $bookId)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:4000',
        ]);
        
        $book = Book::findOrFail($bookId);
        
        $existingReview = Review::where('id_user', Session::get('user_id'))
                                ->where('id_book', $bookId)
                                ->first();
        
        if ($existingReview) {
            return redirect()->route('books.show', $bookId)
                             ->with('error', 'Już dodałeś recenzję dla tej książki.');
        }
        
        $validated['id_user'] = Session::get('user_id');
        $validated['id_book'] = $bookId;
        
        Review::create($validated);
        
        return redirect()->route('books.show', $bookId)
                         ->with('success', 'Recenzja dodana pomyślnie!');
    }
    
    public function edit($id)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $review = Review::with('book')->findOrFail($id);
        
        $userRoles = Session::get('user_roles', []);
        $userId = Session::get('user_id');
        
        // TYLKO MODERATOR może edytować WSZYSTKIE recenzje
        // USER może edytować tylko SWOJE recenzje
        // ADMIN NIE MOŻE edytować recenzji (NIE MA DOSTĘPU!)
        
        // User edytuje swoje recenzje
        if ($review->id_user == $userId) {
            return view('reviews.edit', compact('review'));
        }
        
        // Moderator edytuje wszystkie recenzje
        if (in_array('Moderator', $userRoles)) {
            return view('reviews.edit', compact('review'));
        }
        
        // Admin i inni - brak dostępu
        return redirect()->route('books.show', $review->id_book)
                         ->with('error', 'Brak uprawnień do edycji tej recenzji.');
    }
    
    public function update(Request $request, $id)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $review = Review::findOrFail($id);
        
        $userRoles = Session::get('user_roles', []);
        $userId = Session::get('user_id');
        
        // TYLKO MODERATOR może edytować WSZYSTKIE recenzje
        // USER może edytować tylko SWOJE recenzje
        // ADMIN NIE MOŻE edytować recenzji (NIE MA DOSTĘPU!)
        
        // User edytuje swoje recenzje
        if ($review->id_user == $userId) {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'content' => 'required|string|max:4000',
            ]);
            
            $review->update($validated);
            
            return redirect()->route('books.show', $review->id_book)
                             ->with('success', 'Recenzja zaktualizowana!');
        }
        
        // Moderator edytuje wszystkie recenzje
        if (in_array('Moderator', $userRoles)) {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'content' => 'required|string|max:4000',
            ]);
            
            $review->update($validated);
            
            return redirect()->route('books.show', $review->id_book)
                             ->with('success', 'Recenzja zaktualizowana!');
        }
        
        // Admin i inni - brak dostępu
        return redirect()->route('books.show', $review->id_book)
                         ->with('error', 'Brak uprawnień do edycji tej recenzji.');
    }
    
    public function destroy($id)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        
        $review = Review::findOrFail($id);
        
        $userRoles = Session::get('user_roles', []);
        $userId = Session::get('user_id');
        
        // TYLKO MODERATOR może usuwać WSZYSTKIE recenzje
        // USER może usuwać tylko SWOJE recenzje
        
        // User usuwa swoje recenzje
        if ($review->id_user == $userId) {
            $bookId = $review->id_book;
            $review->delete();
            
            return redirect()->route('books.show', $bookId)
                             ->with('success', 'Recenzja usunięta!');
        }
        
        // Moderator usuwa wszystkie recenzje
        if (in_array('Moderator', $userRoles)) {
            $bookId = $review->id_book;
            $review->delete();
            
            return redirect()->route('books.show', $bookId)
                             ->with('success', 'Recenzja usunięta!');
        }
        
        // Admin i inni - brak dostępu
        return redirect()->route('books.show', $review->id_book)
                         ->with('error', 'Brak uprawnień do usunięcia tej recenzji.');
    }
}