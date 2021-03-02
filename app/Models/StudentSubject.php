<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject_teacher_id',
        'period_id',
        'student_id',
        'grade',
        'status',
        'semester_id'
    ];
//    todo enable timestamps for tracking of date
    public $timestamps = false;

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
