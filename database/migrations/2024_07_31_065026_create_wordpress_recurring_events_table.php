<?php

declare(strict_types=1);

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
        Schema::create('wordpress_recurring_events', function (Blueprint $table) {
            $table->id();
            $table->string('hook');
            $table->text('args');
            $table->string('schedule')->nullable();
            $table->integer('interval')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wordpress_recurring_events');
    }
};
