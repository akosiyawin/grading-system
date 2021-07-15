<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function subject(){
        return $this->belongsToMany(Subject::class);
    }

    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
}
