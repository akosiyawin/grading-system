<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resubmission extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'student_subject_id',
        'grade',
    ];
}
