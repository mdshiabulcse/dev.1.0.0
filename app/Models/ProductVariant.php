<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public function variant(){
        return $this->$this->hasMany(Variant::class, 'variant_id','id');
    }
}
