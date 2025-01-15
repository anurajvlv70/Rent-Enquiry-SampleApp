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
        Schema::create('enquiry_products', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('enquiry_id'); // Foreign key referencing enquiries.id
            $table->unsignedBigInteger('product_id'); // Foreign key referencing products.id
            $table->integer('quantity'); // Number of items requested
            $table->timestamps(); // created_at and updated_at

            // Add foreign key constraints
            $table->foreign('enquiry_id')->references('id')->on('enquiries')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_products');
    }
};
