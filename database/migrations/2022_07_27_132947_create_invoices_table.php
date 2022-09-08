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
            $table->decimal('invoice_total', 8, 2);
            $table->decimal('invoice_subtotal', 8, 2);
            $table->decimal('invoice_tax', 8, 2);
            $table->decimal('invoice_discount', 8, 2);
            $table->json('items');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->string('invoice_status')->nullable();
            $table->boolean('is_quote')->default(false);
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
        Schema::dropIfExists('invoices');
    }
}
