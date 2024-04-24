<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komputer extends Model
{
    use HasFactory;
    protected $table = 'komputer';
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_pembelian' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = \Str::uuid();
        });
    }

    public function sistem_operasi()
    {
        return $this->belongsTo(SistemOperasi::class, 'sistem_operasi_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function ip_address()
    {
        return $this->belongsTo(IpAddress::class, 'ip_address_id', 'id');
    }

    public function sistem_operasi_detail()
    {
        return $this->belongsTo(SistemOperasiDetail::class, 'sistem_operasi_detail_id', 'id');
    }

    public function tanggal_pembelian()
    {
        if ($this->tanggal_pembelian)
            return $this->tanggal_pembelian->translatedFormat('d F Y');
    }

    public function getOs()
    {
        if ($this->sistem_operasi) {
            return $this->sistem_operasi->nama . ' ' . $this->sistem_operasi_detail->tipe;
        } else {
            return '-';
        }
    }

    public function kerusakan()
    {
        return $this->hasMany(KerusakanKomputer::class, 'komputer_id', 'id')->latest();
    }

    public function pergantian()
    {
        return $this->hasMany(PergantianKomputer::class, 'komputer_id', 'id')->latest();
    }

    public function status()
    {
        $latestKerusakan = $this->kerusakan->first();

        if ($latestKerusakan && in_array($latestKerusakan->status, [0, 1])) {
            if ($latestKerusakan->status == 0)
                return '<span class="badge badge-warning">Mengalami Kerusakan</span>';
            else
                return '<span class="badge badge-warning">Dalam Proses Perbaikan</span>';
        } else {
            return '<span class="badge badge-success">Normal</span>';
        }
    }
}
