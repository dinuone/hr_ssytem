<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryPkg extends Model
{
    use HasFactory;

    protected $fillable =[
        'job',
        'department',
        'basic',
        'epf_etf',
        'amount',
        'id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
