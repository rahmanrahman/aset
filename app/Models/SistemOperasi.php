<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SistemOperasi extends Model
{
    use HasFactory;
    protected $table = 'sistem_operasi';
    protected $guarded = ['id'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = \Str::uuid();
        });
    }

    public function details()
    {
        return $this->hasMany(SistemOperasiDetail::class, 'sistem_operasi_id', 'id');
    }
}
