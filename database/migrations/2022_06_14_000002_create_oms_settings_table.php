<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("oms_settings", function (Blueprint $table) {
            $table->id();
            $table->string("oms_name")->nullable();
            $table->string("oms_company_name")->nullable();
            $table->string("oms_email")->nullable();
            $table->string("oms_company_tel")->nullable();
            $table->string("oms_company_address")->nullable();
            $table->string("oms_company_vat")->nullable();
            $table->string("oms_company_registration")->nullable();
            $table->string("oms_logo")->nullable();
            $table->string("date_format")->nullable()->default('d/m/Y');
            $table->string("invoice_series")->nullable()->default('INV');        
            $table->string("quote_series")->nullable()->default('QUOTE');        
            $table->longText("invoice_notes")->nullable();
            $table->longText("quote_notes")->nullable();
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
        Schema::dropIfExists("oms_settings");
    }
}
