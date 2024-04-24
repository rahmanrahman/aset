<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    use HasFactory;

    protected $table = 'ip_address';
    protected $guarded = ['id'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = \Str::uuid();
        });
    }
}
