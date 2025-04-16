<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifycation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'is_read',
        'type',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
