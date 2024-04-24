<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenDetail extends Model
{
    use HasFactory;
    protected $table = 'komponen_detail';
    protected $guarded = ['id'];

    public function komponen()
    {
        return $this->belongsTo(Komponen::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = \Str::uuid();
        });
    }
}
