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
        Schema::create('branch_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('code', 50)->unique();
            $table->text('address');
            $table->text('phone')->nullable();
            $table->string('fax', 20)->unique()->nullable();
            $table->text('email')->nullable();
            $table->foreignId('user')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('branch')->nullable()->constrained('branches')->onDelete('set null');
            $table->foreignId('branch_coordinator')->nullable()->constrained('branch_coordinators')->onDelete('set null');
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
        Schema::dropIfExists('branch_services');
    }
};
