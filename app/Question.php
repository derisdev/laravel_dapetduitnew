<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['phone','title', 'category','description', 'screenshot'];
    
}
