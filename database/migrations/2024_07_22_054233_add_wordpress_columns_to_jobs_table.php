<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('wp_hook')->nullable()->index();
            $table->text('wp_args')->nullable();
            $table->string('wp_schedule')->nullable();
            $table->integer('wp_interval')->nullable();
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['wp_hook', 'wp_args', 'wp_schedule', 'wp_interval']);
        });
    }
};
