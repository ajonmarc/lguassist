<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assistance extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'deadline',
        'description',
        'number_of_questions',
        'survey_instructions',
        'target_audience',
    ];

    protected $table = 'assistances';

    // Optional: Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
