<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['phone', 'via','amount','status', 'time'];

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
