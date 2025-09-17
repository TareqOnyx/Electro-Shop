<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
   protected $fillable = ['name','desc','price','image','stock','cat_id'];

    public function category() :BelongsTo {
        return $this->belongsTo(category::class);
    }
    public function review():HasMany {
        return $this->hasMany(review::class);
    }
}
