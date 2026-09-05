<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nec_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('type', 60)->default('info')->index();
            $table->string('title', 150)->default('NEC Update');
            $table->string('message', 255);
            $table->string('link', 255)->nullable();
            $table->string('icon', 40)->default('bell');
            $table->string('color', 40)->default('primary');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nec_notifications');
    }
};