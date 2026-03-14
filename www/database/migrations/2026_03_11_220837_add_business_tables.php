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
            $table->unsignedBigInteger('fuel_type_id');
            $table->foreign('fuel_type_id')->references('id')->on('fuel_types')->cascadeOnDelete();
            $table->decimal('price');
        });

        Schema::create('cars', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->decimal('price');
        });

        Schema::create('car_motors', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('car_id');
            $table->foreign('car_id')->references('id')->on('cars');
            $table->unsignedBigInteger('motor_id');
            $table->foreign('motor_id')->references('id')->on('motors');
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
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('orders', function (Blueprint $table)
        {
           $table->id();
           $table->timestamps();
           $table->softDeletes();
           $table->uuid('number')->unique()->index();
           $table->decimal('price');
           $table->unsignedBigInteger('customer_id');
           $table->foreign('customer_id')->references('id')->on('customers');
           $table->unsignedBigInteger('car_id');
           $table->foreign('car_id')->references('id')->on('cars');
           $table->unsignedBigInteger('motor_id');
           $table->foreign('motor_id')->references('id')->on('motors');
           $table->string('color');
           $table->string('status');
        });

        Schema::create('order_options', function (Blueprint $table)
        {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('option_id');
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
        Schema::dropIfExists('car_motors');
        Schema::dropIfExists('options');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_options');
    }
};
