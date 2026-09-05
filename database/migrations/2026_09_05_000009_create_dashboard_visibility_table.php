<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_visibility', function (Blueprint $table) {
            $table->id();
            $table->string('role', 64)->index();
            $table->string('key', 128);
            $table->boolean('visible')->default(false);
            $table->timestamps();

            $table->unique(['role', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_visibility');
    }
};