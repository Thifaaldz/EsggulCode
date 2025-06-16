<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'branch_id',
        'position_id',
        'nama',
        'email',
        'telepon',
        'tanggal_lahir',
        'foto',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function attendances() {
        return $this->hasMany(Attendance::class);
    }

    public function leaves() {
        return $this->hasMany(Leave::class);
    }

    public function payrolls() {
        return $this->hasMany(Payroll::class);
    }
}
