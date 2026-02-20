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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('symbol')->nullable();
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });

        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('units');
        Schema::dropIfExists('product_types');
        Schema::dropIfExists('payment_types');
    }
};
