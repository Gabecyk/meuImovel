<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name', 'description', 'slug'
    ];

    public function realStates()
    {
        return $this->belongsToMany(RealState::class, 'real_state_categories');
    }
}
