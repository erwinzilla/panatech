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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('invoice_name', 100)->nullable()->unique();
            $table->text('note')->nullable();
            $table->string('accessories', 200)->nullable();
            $table->string('condition', 200)->nullable();
            $table->integer('labour')->unsigned()->nullable();
            $table->integer('transport')->unsigned()->nullable();
            $table->boolean('quality_report')->default(false);
            $table->boolean('dealer_report')->default(false);

            // repair date
            $table->dateTime('repair_at')->nullable();
            $table->dateTime('collection_at')->nullable();
            $table->dateTime('actual_start_at')->nullable();
            $table->dateTime('actual_end_at')->nullable();

            // data from ticket
            $table->text('service_info');
            $table->text('repair_info')->nullable();

            // customer data
            $table->string('customer_name', 100);
            $table->string('phone', 20);
            $table->string('phone2', 20)->nullable();
            $table->string('phone3', 20)->nullable();
            $table->text('address');
            $table->string('email')->nullable();
            $table->bigInteger('customer_type')->unsigned()->nullable();
            $table->string('tax_id',100)->nullable();
            // end customer data

            // warranty data
            $table->string('model', 100);
            $table->string('serial', 100)->nullable();
            $table->string('warranty_no', 100)->nullable();
            $table->date('purchase_date')->nullable();
            $table->boolean('warranty_type')->default(0)->nullable(); //0:out, 1:in
            // end warranty

            // relation job
            $table->foreignId('job_type')->nullable()->constrained('job_types')->onDelete('restrict');
            $table->foreignId('status')->nullable()->constrained('states')->onDelete('restrict');
            $table->foreignId('handle_by')->nullable()->constrained('users')->onDelete('restrict');

            // get data from ticket
            $table->foreignId('ticket')->nullable()->constrained('tickets')->onDelete('restrict');

            // crud by
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
        Schema::dropIfExists('jobs');
    }
};
