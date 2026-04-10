<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    
    // DODAJ TE 3 LINIJKI:
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = ['login', 'password', 'email'];
    protected $hidden = ['password'];
    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'id_user', 'id_role')
                    ->withPivot('assigned_at');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_user');
    }
    
    public function trackedBooks()
    {
        return $this->belongsToMany(Book::class, 'user_book', 'id_user', 'id_book')
                    ->withPivot('id_status', 'progress', 'start_date', 'planned_end_date')
                    ->withTimestamps();
    }
    
    public function booksAdded()
    {
        return $this->hasMany(Book::class, 'created_by');
    }
    
    public function booksUpdated()
    {
        return $this->hasMany(Book::class, 'updated_by');
    }
    
    // DODAJ TĄ METODĘ NA SAMYM KOŃCU:
    public function getBooksCountByStatus($statusId = null)
    {
        $query = $this->trackedBooks();
        
        if ($statusId) {
            $query->wherePivot('id_status', $statusId);
        }
        
        return $query->count();
    }
}