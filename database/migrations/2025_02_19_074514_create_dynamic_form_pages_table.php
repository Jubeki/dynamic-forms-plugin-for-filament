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
        Schema::create('dynamic_form_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_blueprint_id');
            $table->unsignedInteger('sort')->nullable();
            $table->json('name');
            $table->json('fields');
            $table->timestamps();
        });
    }
};
