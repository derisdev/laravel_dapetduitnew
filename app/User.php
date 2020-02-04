<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function payments() {
        return $this->belongsToMany(Payment::class);
    }


    public function phone() {
        return $this->hasOne(Phone::class)->withDefault();;
    }

    public function refferal() {
        return $this->hasOne(Refferal::class)->withDefault();;
    }
    
    public function rewards() {
        return $this->hasOne(Rewards::class)->withDefault();;
    }

    public function historyrewards() {
        return $this->hasMany(HistoryRewards::class)->withDefault();;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
