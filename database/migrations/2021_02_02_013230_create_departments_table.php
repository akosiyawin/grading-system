<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });

        \App\Models\Department::insert(
            [
                ['title' => \App\Base::GEN_ED_DEPT]
            ]
        );

        \App\Models\Department::insert(
            [
                ['title' => \App\Base::CS_DEPT]
            ]
        );

        \App\Models\Department::insert(
            [
                ['title' => \App\Base::HRM_DEPT]
            ]
        );

        \App\Models\Department::insert(
            [
                ['title' => \App\Base::ENG_DEPT]
            ]
        );

    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
