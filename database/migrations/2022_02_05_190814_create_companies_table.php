<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('COMPANY', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name', 50);
            $table->string('description', 100);
            $table->string('symbol', 10);
            $table->json('market');
            $table->timestamps(3);
            $table->softDeletes(precision: 3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('COMPANY');
    }
}
