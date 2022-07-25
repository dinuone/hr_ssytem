<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable =[
        'job_id',
        'deparment_id',
        'username',
        'avb_leave',
        'profile_img',
        'address',
        'date_hired',
        'birthdate',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
