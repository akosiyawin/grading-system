<?php

use App\Base;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained();
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('middle_name')->default(' ');
            $table->string('last_name');
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        \App\Models\User::insert(
            [
                'role_id' => 1,
                'username' => "registrar",
                'first_name' => "Eastwoods",
                'middle_name' => "",
                'last_name' => "Admin",
                'password' => \Illuminate\Support\Facades\Hash::make(Base::TEACHER_DEFAULT_PASSWORD)
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
        Schema::dropIfExists('users');
    }
}
