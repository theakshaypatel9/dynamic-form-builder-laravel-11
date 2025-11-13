<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title', 'description', 'config', 'created_by'];

    protected $casts = [
        'config' => 'array', // JSON -> array
    ];

    public function submissions()
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
