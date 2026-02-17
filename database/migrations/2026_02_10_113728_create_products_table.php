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
             $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');


            $table->string('name');
            $table->string('sku')->unique();
            //foreign keys
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained('sub_categories')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->decimal('cost',10, 2);
            $table->integer('quantity')->default(0);
            $table->string('image');
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
