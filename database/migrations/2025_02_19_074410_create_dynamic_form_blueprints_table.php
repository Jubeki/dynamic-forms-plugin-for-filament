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
        Schema::create('dynamic_form_blueprints', function (Blueprint $table) {
            $table->id();
            $table->string('handle');
            $table->unsignedInteger('version');
            $table->json('name');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->unique(['handle', 'version']);
        });
    }
};
