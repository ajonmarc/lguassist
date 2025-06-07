<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
    
    ];

    protected $table = 'forms';

    // Optional: Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
