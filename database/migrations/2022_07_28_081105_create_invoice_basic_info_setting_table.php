<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceBasicInfoSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_basic_info_settings', function (Blueprint $table) {
            $table->id();           
            $table->string("oms_company_name")->nullable();
            $table->string("oms_company_tel")->nullable();
            $table->string("oms_company_address")->nullable();
            $table->string("oms_company_vat")->nullable();
            $table->string("oms_company_registration")->nullable();
            $table->string("invoice_logo")->nullable();
            $table->string("date_format")->nullable()->default('d/m/Y');
            $table->string("series")->nullable()->default('INV');
            $table->longText("invoice_notes")->nullable();
            $table->foreignId("default_converted_status")->nullable()->constrained("invoice_statuses");
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
        Schema::dropIfExists('invoice_basic_info_settings');
    }
}
