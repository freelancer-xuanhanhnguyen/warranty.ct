<?php

use App\Models\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('repairman_id')->nullable();
            $table->string('code')->nullable();
            $table->unsignedTinyInteger('type')->default(Service::TYPE_WARRANTY);
            $table->text('content')->nullable();
            $table->decimal('fee_total', 20)->nullable();
            $table->text('note')->nullable();
            $table->date('reception_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->unsignedTinyInteger('evaluate')->nullable();
            $table->text('evaluate_note')->nullable();

            $table->foreign('repairman_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
