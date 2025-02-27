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
        Schema::create('dynamic_form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_blueprint_id')->constrained()->cascadeOnDelete();
            $table->json('data');
            $table->timestamps();
        });
    }
};
