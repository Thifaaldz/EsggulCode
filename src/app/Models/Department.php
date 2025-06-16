<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }
}
