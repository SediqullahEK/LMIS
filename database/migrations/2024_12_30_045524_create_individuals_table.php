<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('f_name');
            $table->string('tin_num');
            $table->string('tazkira_num');
            $table->string('phone');
            $table->string('photo_path', 255);
            $table->unsignedBigInteger('province_id');
            $table->string('district');
            $table->string('date_of_birth');
            $table->string('nationality');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });
        DB::statement('ALTER TABLE individuals ADD FULLTEXT(name, f_name, tazkira_num, tin_num)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individuals');
    }
};
