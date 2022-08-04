<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserjobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userjobs', function (Blueprint $table) {
            $table->id();   
            $table->string("title");   
            $table->longText("description");
            $table->date('date_completed')->nullable();         
            $table->foreignId("client_id")->nullable()->constrained('clients')  ;      
            $table->foreignId("user_id")->nullable()->constrained('users') ;      
            $table->foreignId("department_id")->nullable()->constrained('departments');      
            $table->foreignId("invoice_id")->nullable()->constrained('invoices');      
            $table->foreignId("status_id")->nullable()->constrained('status');  
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
        Schema::dropIfExists('userjobs');
    }
}
