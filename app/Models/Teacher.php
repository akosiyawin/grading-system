<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function subjects(){
        return $this->hasMany(SubjectTeacher::class);
    }

 /*   public function students()
    {
        return $this->hasMany(StudentTeacher::class);
    }*/
    
}
