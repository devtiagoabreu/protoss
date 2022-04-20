<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beer extends Model
{
    use HasFactory;

    function category() {
        return $this->belongsTo(Category::class);
    }
    
    function style() {
        return $this->belongsTo(Style::class);
    }
    
    function brewery() {
        return $this->belongsTo(Brewery::class);
    }
}