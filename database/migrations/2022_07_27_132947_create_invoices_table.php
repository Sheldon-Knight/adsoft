<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_date');
            $table->string('invoice_number');
            $table->string('invoice_due_date');         
            $table->foreignId('invoice_status')->nullable()->constrained('invoice_statuses');  
            $table->decimal('invoice_total');
            $table->decimal('invoice_subtotal');
            $table->decimal('invoice_tax');
            $table->decimal('invoice_discount');
            $table->json('items');
            $table->foreignId('user_id')->constrained('users');           
            $table->foreignId('client_id')->constrained('clients');    
            $table->boolean("is_quote")->default(false);
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
        Schema::dropIfExists('invoices');
    }
}
