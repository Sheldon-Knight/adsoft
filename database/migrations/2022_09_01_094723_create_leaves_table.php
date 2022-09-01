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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');
            $table->date('from');
            $table->date('to');
            $table->string('type')->nullable();
            $table->json('attachments')->nullable();
            $table->foreignId('revisioned_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->date('revisioned_on')->nullable();
            $table->string('status')->default("Pending");
            $table->longText('user_notes')->nullable();
            $table->longText('revisioned_notes')->nullable();
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
        Schema::dropIfExists('leaves');
    }
};
