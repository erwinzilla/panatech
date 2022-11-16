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
        Schema::create('branch_service_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_service')->nullable()->constrained('branch_services')->onDelete('restrict');
            $table->integer('income_target')->default(0);
            $table->integer('income_div')->default(0);
            $table->integer('speed_repair_target')->default(0);
            $table->integer('speed_repair_div')->default(0);
            $table->integer('sabbr_target')->default(0);
            $table->integer('sabbr_div')->default(0);
            $table->integer('sabbr_max_result')->default(0);
            $table->integer('incentive')->default(0);
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
        Schema::dropIfExists('branch_service_targets');
    }
};
