<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empsalary extends Model
{
    use HasFactory;

    protected $fillable =[
        'job_id',
        'dep_id',
        'pkg_id',
        'emp_id',
        'amount',
        'status',
        'month'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }
    public function package()
    {
        return $this->belongsTo(SalaryPkg::class,'pkg_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'dep_id');
    }
    public function job()
    {
        return $this->belongsTo(Job::class,'job_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
