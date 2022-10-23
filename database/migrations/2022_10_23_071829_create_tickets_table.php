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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('service_info');

            // customer data
            $table->string('customer_name', 100);
            $table->string('phone', 20);
            $table->string('phone2', 20)->nullable();
            $table->string('phone3', 20)->nullable();
            $table->text('address');
            $table->string('email')->nullable();
            $table->bigInteger('customer_type')->unsigned()->nullable();
            // end customer data

            // warranty data
            $table->string('model', 100);
            $table->string('serial', 100)->nullable();
            $table->string('warranty_no', 100)->nullable();
            $table->date('purchase_date')->nullable();
            $table->boolean('warranty_type')->default(0)->nullable(); //0:out, 1:in
            // end warranty

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
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
        Schema::dropIfExists('tickets');
    }
};
