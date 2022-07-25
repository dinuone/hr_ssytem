<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable =[
        'department_id',
        'task_name',
        'task_desc',
        'deadline',
        'is_complete'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
