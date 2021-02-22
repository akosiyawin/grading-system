<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    public $fillable = ['title','code','units','department_id'];
    public $timestamps = false;

    public function subject_teachers(){
        return $this->hasMany(SubjectTeacher::class);
    }

    public function teacher(){
        return $this->hasMany(SubjectTeacher::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function students()
    {
        return $this->hasMany(StudentSubject::class);
    }
}
