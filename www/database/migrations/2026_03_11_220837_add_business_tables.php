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
        Schema::create('fuel_types', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->integer('eco_class');
        });

        Schema::create('vendors', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
        });

        Schema::create('motors', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->foreign('fuel_type_id')->references('id')->on('fuel_types')->cascadeOnDelete();
            $table->decimal('price');
        });

        Schema::create('cars', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('motor_id')->references('id')->on('motors');
            $table->decimal('price');
        });

        Schema::create('options', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->decimal('price');
        });

        Schema::create('customers', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('first_name')->index();
            $table->string('last_name')->index();
            $table->date('birthday')->index();
        });

        Schema::create('orders', function (Blueprint $table)
        {
           $table->id();
           $table->timestamps();
           $table->softDeletes();
           $table->date('date')->index();
           $table->uuid('number')->unique()->index();
           $table->decimal('price');
           $table->foreign('customer_id')->references('id')->on('customers');
           $table->foreign('car_id')->references('id')->on('cars');
           $table->string('color');
           $table->string('status');
        });

        Schema::create('order_options', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('option_id')->references('id')->on('options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_types');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('motors');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('options');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_options');
    }
};
