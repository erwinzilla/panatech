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
        Schema::create('branch_service_sabbr', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_service')->nullable()->constrained('branch_services')->onDelete('restrict');
            $table->integer('open')->default(0);
            $table->integer('repair')->default(0);
            $table->integer('complete')->default(0);
            $table->integer('set_total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_service_sabbr');
    }
};
