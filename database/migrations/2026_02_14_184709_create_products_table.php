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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->longText('description')->nullable();
            $table->decimal('cost', 10)->unsigned();
            $table->smallInteger('reorder')->unsigned();
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('product_type_id')->nullable()->constrained('product_types');
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('entry_by')->constrained('users');
            $table->softDeletes();
            $table->timestamps(6);
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->string('image');
            $table->boolean('is_thumbnail')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('images');
    }
};
