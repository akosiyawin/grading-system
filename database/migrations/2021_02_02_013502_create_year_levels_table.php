<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_levels', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });

        \App\Models\YearLevel::insert([
           ['title' => 'First Year'],
           ['title' => 'Second Year'],
           ['title' => 'Third Year'],
           ['title' => 'Fourth Year'],
           ['title' => 'Fifth Year'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('year_levels');
    }
}
