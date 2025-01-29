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
        Schema::rename('wordpress_recurring_events', 'wp_events');

        Schema::table('wp_events', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(true);
            $table->integer('timestamp')->nullable();
            $table->integer('job_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('wp_events', function (Blueprint $table) {
            $table->dropColumn(['is_recurring', 'timestamp', 'job_id']);
        });

        Schema::rename('wp_events', 'wordpress_recurring_events');
    }
};
