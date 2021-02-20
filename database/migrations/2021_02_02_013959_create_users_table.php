<?php

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
            $table->string('middle_name')->nullable();
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
                'first_name' => "Chino",
                'middle_name' => "Tasyo",
                'last_name' => "Pasya",
                'password' => \Illuminate\Support\Facades\Hash::make(123)
            ]
        );
        \App\Models\User::insert(
            [
                'role_id' => 2,
                'username' => "teacher",
                'first_name' => "Susan",
                'middle_name' => "Palasoy",
                'last_name' => "Keriboom",
                'password' => \Illuminate\Support\Facades\Hash::make(123)
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
