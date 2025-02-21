<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exp_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->unique();
            $table->string('experience')->nullable();
            $table->string('last_company')->nullable();
            $table->date('exp_start_date')->nullable();
            $table->date('exp_end_date')->nullable();
            $table->string('last_designation')->nullable();
            $table->decimal('last_salary')->nullable();
            $table->decimal('current_exp')->nullable();
            $table->decimal('offered_salary')->nullable();
            $table->decimal('total_exp')->nullable();
            $table->bigInteger('payslip1_id')->nullable()->unique();
            $table->string('payslip1_name')->nullable();
            $table->string('payslip1')->nullable();
            $table->bigInteger('payslip2_id')->nullable()->unique();
            $table->string('payslip2_name')->nullable();
            $table->string('payslip2')->nullable();
            $table->bigInteger('payslip3_id')->nullable()->unique();
            $table->string('payslip3_name')->nullable();
            $table->string('payslip3')->nullable();
            $table->bigInteger('offer_id')->nullable()->unique();
            $table->string('offer_name')->nullable();
            $table->string('offer_letter')->nullable();
            $table->bigInteger('exp_id')->nullable()->unique();
            $table->string('exp_name')->nullable();
            $table->string('exp_letter')->nullable();
            $table->bigInteger('inc_id')->nullable()->unique();
            $table->string('inc_name')->nullable();
            $table->string('inc_letter')->nullable();
            $table->string('UAN')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exp_details');
    }
};
