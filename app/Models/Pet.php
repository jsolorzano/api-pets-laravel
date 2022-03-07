<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    
    /**
     * Get the user record associated with the pet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the user record associated with the pet.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
