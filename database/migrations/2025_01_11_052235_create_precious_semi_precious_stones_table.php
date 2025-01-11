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
        Schema::create('precious_semi_precious_stones', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('latin_name')->unique();
            $table->enum('quantity', ['گرام', 'کیلو گرام', 'تن', 'قیراط']);
            $table->string('image_path');
            $table->unsignedBigInteger('estimated_extraction')->nullable();
            $table->unsignedInteger('estimated_price_from')->nullable();
            $table->unsignedBigInteger('estimated_price_to')->nullable();
            $table->double('offered_royality_by_private_sector', 8, 2)->nullable();
            $table->double('final_royality_after_negotiations', 8, 2)->nullable();
            $table->unsignedBigInteger('estimated_revenue_based_on_ORPS')->nullable();
            $table->unsignedBigInteger('estimated_revenue_based_on_FRAN')->nullable();
            $table->boolean('is_precious')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precious_semi_precious_stones');
    }
};
