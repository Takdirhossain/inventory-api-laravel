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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_id');
            $table->string('twelve_kg')->nullable();
            $table->string('twentyfive_kg')->nullable();
            $table->string('thirtythree_kg')->nullable();
            $table->string('thirtyfive_kg')->nullable();
            $table->string('fourtyfive_kg')->nullable();
            $table->string('others_kg')->nullable();
            $table->string('empty_twelve_kg')->nullable();
            $table->string('empty_twentyfive_kg')->nullable();
            $table->string('empty_thirtythree_kg')->nullable();
            $table->string('empty_thirtyfive_kg')->nullable();
            $table->string('empty_fourtyfive_kg')->nullable();
            $table->string('empty_others_kg')->nullable();
            $table->string('date');
            $table->string('is_due_bill');
            $table->string('price');
            $table->string('pay');
            $table->string('due');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
