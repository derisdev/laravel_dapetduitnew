<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offerwall extends Model
{
    protected $fillable = ['icon','title', 'image','description', 'coin'];
    
}
