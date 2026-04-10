<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBook extends Model
{
    use HasFactory;

    protected $table = 'user_book';
    
    // DLA COMPOSITE KEY
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = true;
    
    protected $fillable = [
        'id_user', 'id_book', 'id_status', 
        'progress', 'start_date', 'planned_end_date'
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'planned_end_date' => 'date',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    
    public function book()
    {
        return $this->belongsTo(Book::class, 'id_book');
    }
    
    public function status()
    {
        return $this->belongsTo(ReadingStatus::class, 'id_status');
    }
    
    // Metoda do znajdowania po composite key
    public static function findByCompositeKey($userId, $bookId)
    {
        return static::where('id_user', $userId)
                    ->where('id_book', $bookId)
                    ->first();
    }
}