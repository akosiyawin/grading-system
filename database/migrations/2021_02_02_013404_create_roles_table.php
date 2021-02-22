<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });

        \App\Models\Role::insert(
           [
               ['title' => 'Registrar']
           ]
        );

        \App\Models\Role::insert(
            [
                ['title' => 'Teacher']
            ]
        );

        \App\Models\Role::insert(
            [
                ['title' => 'Student']
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
        Schema::dropIfExists('roles');
    }
}
