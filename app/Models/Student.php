<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'course_id',
        'birthdate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->hasMany(StudentSubject::class);
    }

/*    public function teachers()
    {
        return $this->hasMany(StudentTeacher::class);
    }*/

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subjectThisSem()
    {
        return $this->hasMany(StudentSubject::class)
            ->join('subjects','student_subjects.subject_id','subjects.id')
            ->join('semesters','subjects.semester_id','semesters.id')
            ->where('semesters.status',1);

    }

}
