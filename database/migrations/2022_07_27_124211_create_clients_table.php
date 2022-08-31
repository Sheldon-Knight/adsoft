<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('physical_address')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_surname')->nullable();
            $table->string('tel_num')->nullable();
            $table->string('cell_num')->nullable();
            $table->string('fax_num')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('reg_type')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_type')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('client_status')->default('Active');
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
        Schema::dropIfExists('clients');
    }
}
