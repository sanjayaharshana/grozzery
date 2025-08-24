<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('business_name');
            $table->text('business_description')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->decimal('commission_rate', 5, 2)->default(10.00);
            $table->decimal('minimum_payout', 10, 2)->default(50.00);
            $table->decimal('total_sales', 15, 2)->default(0.00);
            $table->integer('total_orders')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'verified_at']);
            $table->index('business_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
