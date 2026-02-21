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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('trx_id')->unique();
            $table->date('trx_date');
            $table->string('trx_type');
            $table->bigInteger('total_item')->unsigned();
            $table->decimal('total_price', 12)->unsigned();
            $table->string('status');
            $table->foreignId('validate_by')->nullable()->constrained('users');
            $table->timestamp('validate_at', 6)->nullable();
            $table->foreignId('entry_by')->constrained('users');
            $table->timestamps(6);
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity')->unsigned();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->bigInteger('stock')->unsigned()->default(0);
            $table->decimal('stock_value', 10)->unsigned();
            $table->boolean('can_reorder')->default(true);
            $table->timestamps(6);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('items');
        Schema::dropIfExists('inventories');
    }
};
