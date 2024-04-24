<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PergantianKomputer extends Model
{
    use HasFactory;
    protected $table = 'pergantian_komputer';
    protected $guarded = ['id'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = \Str::uuid();
        });
    }


    public function komputer()
    {
        return $this->belongsTo(Komputer::class, 'komputer_id', 'id');
    }
}
