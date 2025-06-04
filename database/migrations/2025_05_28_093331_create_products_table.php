<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('serial')->nullable();
            $table->unsignedTinyInteger('warranty_period_unit')->default(Product::WARRANTY_UNIT_MONTH);
            $table->unsignedInteger('warranty_period')->nullable();
            $table->unsignedTinyInteger('periodic_warranty_unit')->default(Product::WARRANTY_UNIT_MONTH);
            $table->unsignedInteger('periodic_warranty')->nullable();

            $table->unsignedBigInteger('repairman_id')->nullable();
            $table->foreign('repairman_id')->references('id')->on('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
