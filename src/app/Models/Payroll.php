<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tahun',
        'bulan',
        'gaji_pokok',
        'jumlah_kehadiran',
        'jumlah_cuti',
        'insentif_kehadiran',
        'bonus',
        'total_gaji',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
