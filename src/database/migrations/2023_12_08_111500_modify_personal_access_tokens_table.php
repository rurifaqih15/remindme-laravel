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
        Schema::table('personal_access_tokens', function(Blueprint $table){
            $table->uuid('token', 36)->change();
            $table->dropColumn('abilities');
            $table->dropColumn('last_used_at');
            $table->string('refresh_token',36)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function(Blueprint $table){
            $table->string('token', 64)->change();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->dropColumn('refresh_token');
        });
    }
};
