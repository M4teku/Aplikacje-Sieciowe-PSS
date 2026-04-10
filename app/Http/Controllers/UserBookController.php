<?php
namespace App\Http\Controllers;
use App\Models\UserBook;
use App\Models\Book;
use App\Models\ReadingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UserBookController extends Controller
{
    public function store(Request $request, $bookId) {
        if (!Session::has('user_id')) return redirect()->route('login');
        
        $book = Book::findOrFail($bookId);
        $existing = DB::table('user_book')->where('id_user', Session::get('user_id'))->where('id_book', $bookId)->exists();
        
        if ($existing) {
            return redirect()->route('books.show', $bookId)->with('error', 'Już śledzisz tę książkę.');
        }
        
        $validated = $request->validate(['id_status' => 'required|exists:reading_status,id_status']);
        
        DB::table('user_book')->insert([
            'id_user' => Session::get('user_id'),
            'id_book' => $bookId,
            'id_status' => $validated['id_status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('books.show', $bookId)->with('success', 'Książka dodana do Twojej listy!');
    }
    
    public function update(Request $request, $bookId) {
        if (!Session::has('user_id')) return redirect()->route('login');
        
        DB::table('user_book')->where('id_user', Session::get('user_id'))->where('id_book', $bookId)
            ->update(['id_status' => $request->id_status, 'updated_at' => now()]);
        
        return redirect()->route('userbooks.mybooks')->with('success', 'Status zaktualizowany!');
    }
    
    public function destroy($bookId) {
        if (!Session::has('user_id')) return redirect()->route('login');
        
        DB::table('user_book')->where('id_user', Session::get('user_id'))->where('id_book', $bookId)->delete();
        return redirect()->route('userbooks.mybooks')->with('success', 'Książka usunięta z listy!');
    }
    
    public function myBooks(Request $request) {
        if (!Session::has('user_id')) return redirect()->route('login');
        
        // POPRAWIONE - pobieranie tytułów książek
        $query = DB::table('user_book')
            ->join('book', 'user_book.id_book', '=', 'book.id_book')
            ->join('reading_status', 'user_book.id_status', '=', 'reading_status.id_status')
            ->where('user_book.id_user', Session::get('user_id'))
            ->select(
                'book.id_book',
                'book.title',  // DODANE
                'book.author', // DODANE
                'book.genre',  // DODANE
                'book.description', // DODANE
                'user_book.id_status',
                'reading_status.name as status_name',
                'user_book.created_at'
            );
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('user_book.id_status', $request->status);
        }
        
        $userBooks = $query->paginate(20);
        $statuses = ReadingStatus::all();
        
        return view('userbooks.index', compact('userBooks', 'statuses'));
    }

    public function edit($bookId) {
        if (!Session::has('user_id')) return redirect()->route('login');
        
        $userBook = DB::table('user_book')
            ->join('book', 'user_book.id_book', '=', 'book.id_book')
            ->where('user_book.id_user', Session::get('user_id'))
            ->where('user_book.id_book', $bookId)
            ->select('book.id_book', 'book.title', 'book.author', 'user_book.id_status')
            ->first();
        
        if (!$userBook) {
            return redirect()->route('userbooks.mybooks')->with('error', 'Książka nie znaleziona.');
        }
        
        $statuses = ReadingStatus::all();
        return view('userbooks.edit', compact('userBook', 'statuses'));
    }
}