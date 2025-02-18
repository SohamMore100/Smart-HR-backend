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
        Schema::create('edu_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('ssc_schoole')->nullable();
            $table->decimal('ssc_per', 5, 2)->nullable();
            $table->year('ssc_passout_year')->nullable();
            $table->string('ssc_board')->nullable();
            $table->string('hsc_school')->nullable();
            $table->decimal('hsc_per', 5, 2)->nullable();
            $table->year('hsc_passout_year')->nullable();
            $table->string('hsc_board')->nullable();
            $table->string('hsc_stream')->nullable();
            $table->string('graduation_college')->nullable();
            $table->decimal('graduation_cgpa', 5, 2)->nullable();
            $table->year('graduation_start_year')->nullable();
            $table->year('graduation_passout_year')->nullable();
            $table->string('graduation_university')->nullable();
            $table->string('PG_college')->nullable();
            $table->decimal('pg_cgpa', 5, 2)->nullable();
            $table->year('pg_start_year')->nullable();
            $table->year('pg_passout_year')->nullable();
            $table->string('pg_university')->nullable();
            $table->string('doc_ssc')->nullable();
            $table->string('doc_hsc')->nullable();
            $table->string('doc_graduation')->nullable();
            $table->string('doc_pg')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('edu_details');
    }
};
