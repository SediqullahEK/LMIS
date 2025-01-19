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
        Schema::create('table_psp_licenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('letter_id');
            $table->unsignedBigInteger('individual_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('stone_id');
            $table->string('stone_color_dr');
            $table->string('stone_color_en');
            $table->unsignedInteger('stone_amount');
            $table->unsignedInteger('serial_number')->unique();
            $table->string('issue_date');
            $table->string('expire_date');
            $table->boolean('is_valid')->default(false);
            $table->text('signed_version_image_path');

            $table->foreign('individual_id')
                ->references('id')
                ->on('individuals')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('stone_id')
                ->references('id')
                ->on('precious_semi_precious_stones')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_psp_licenses');
    }
};
