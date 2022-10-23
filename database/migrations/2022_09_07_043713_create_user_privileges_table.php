<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_privileges', function (Blueprint $table) {
            $value = FORBIDDEN; // 0: Forbidden, 1:Can See, 2:Can Edit & Delete, 3:All Access

            $table->id();
            $table->string('name', 100)->unique();
            $table->tinyInteger('tickets')->default($value);
            $table->tinyInteger('customers')->default($value);
            $table->tinyInteger('products')->default($value);
            $table->tinyInteger('reports')->default($value);
            $table->tinyInteger('users')->default($value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_privileges');
    }
};
