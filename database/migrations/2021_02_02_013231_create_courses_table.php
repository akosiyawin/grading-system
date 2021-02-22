<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->foreignId('department_id')->constrained();
        });


        \App\Models\Course::insert(
            [
                [
                    'title' => \App\Base::ACT,
                    'department_id' => 2,
                ],
                [
                    'title' => \App\Base::BS_COE,
                    'department_id' => 4,
                ],
                [
                    'title' => \App\Base::BS_HRM,
                    'department_id' => 3,
                ],
                [
                    'title' => \App\Base::BS_CS,
                    'department_id' => 2,
                ],
                [
                    'title' => \App\Base::BS_HM,
                    'department_id' => 3,
                ],
                [
                    'title' => \App\Base::BS_IT,
                    'department_id' => 2,
                ],
                [
                    'title' => \App\Base::HRS,
                    'department_id' => 3,
                ],
                [
                    'title' => \App\Base::HRT,
                    'department_id' => 3,
                ]
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
        Schema::dropIfExists('courses');
    }
}
