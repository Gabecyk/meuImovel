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
        Schema::create('real_state', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onDelete('cascade');
            $table->string('title');
            $table->string('description');
            $table->text('content');
            $table->float('price', 10, 2);
            $table->integer('bathrooms');
            $table->integer('bedrooms');
            $table->integer('property_area');
            $table->integer('total_property_area');
            $table->string('slug');

            $table->timestamps();

            //$table->foreign('user_id')->references('id')->on('users'); //jeito antigo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_state');
    }
};
