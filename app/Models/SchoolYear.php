<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $fillable = ['year'];

    protected static function boot(){
        parent::boot();
        static::created(function($year){
            $year->semester()->create([
                'title' => "First Semester"
            ]);
            $year->semester()->create([
                'title' => "Second Semester"
            ]);
            $year->semester()->create([
                'title' => "Summer"
            ]);
        });
    }

    public function semester(){
        return $this->hasMany(Semester::class);
    }
}
