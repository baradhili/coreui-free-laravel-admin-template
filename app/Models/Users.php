<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Users extends Model
{
    use SoftDeletes;
    use HasFactory;

    /**
     * Get the notes for the users.
     */
    public function notes()
    {
        return $this->hasMany(\App\Models\Notes::class);
    }
}
