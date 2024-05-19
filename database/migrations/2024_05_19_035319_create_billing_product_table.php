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
        
        Schema::create('customer_mallow', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamps();
        });
        
        Schema::create('denominations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('value');
            $table->integer('count');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('purchase_price_per_unit')->default(1);
            $table->double('selling_price_per_unit')->default(1);
            $table->integer('available_stock')->default(0);
            $table->double('tax_percentage')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        
        Schema::create('customer_purchase_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customer_mallow');
            $table->date('bill_date');
            $table->double('paid_amount');
            $table->double('before_purc_ledger_balance');
            $table->double('after_purc_ledger_balance')->default(0);
            $table->timestamps();
        });
        
        Schema::create('purchase_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('sales_id')->constrained('customer_purchase_info');
            $table->double('purchase_price_per_unit')->default(1);
            $table->double('selling_price_per_unit')->default(1);
            $table->integer('stock_before_purchase')->default(0);
            $table->integer('stock_after_purchase')->default(0);
            $table->double('tax_percentage')->nullable();
            $table->double('price_without_tax');
            $table->double('price_with_tax');
            $table->integer('quantity');
            $table->timestamps();
        });
        
        Schema::create('denomination_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('denomination_id')->constrained('denominations');
            $table->foreignId('sales_id')->constrained('customer_purchase_info');
            $table->integer('count');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_mallow');
        Schema::dropIfExists('denominations');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customer_purchase_info');
        Schema::dropIfExists('purchase_logs');
        Schema::dropIfExists('denomination_logs');
    }
};
