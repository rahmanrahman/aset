<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KerusakanKomputer extends Model
{
    use HasFactory;
    protected $table = 'kerusakan_komputer';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_perbaikan' => 'datetime'
    ];

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

    public function status()
    {
        if ($this->status == 0) {
            return '<span class="badge badge-warning">Belum Diperbaiki</span>';
        } elseif ($this->status == 1) {
            return '<span class="badge badge-info">Sedang Diperbaiki</span>';
        } else {
            return '<span class="badge badge-success">Sudah Diperbaiki</span>';
        }
    }

    public function pergantian()
    {
        return $this->hasOne(PergantianKomputer::class, 'kerusakan_komputer_id', 'id');
    }
}
