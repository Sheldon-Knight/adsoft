<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
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
            $table->string("title");   
            $table->longText("description");
            $table->date('date_completed')->nullable();         
            $table->foreignId("client_id")->nullable()->constrained('clients')->onDelete('cascade');         
            $table->foreignId("user_id")->nullable()->constrained('users')->onDelete('cascade');         
            $table->foreignId("created_by")->nullable()->constrained('users')->onDelete('cascade');         
            $table->foreignId("department_id")->nullable()->constrained('departments')->onDelete('cascade');         
            $table->foreignId("invoice_id")->nullable()->constrained('invoices')->onDelete('cascade');         
            $table->foreignId("status_id")->nullable()->constrained('status')->onDelete('cascade');     
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
        Schema::dropIfExists('userjobs');
    }
}
