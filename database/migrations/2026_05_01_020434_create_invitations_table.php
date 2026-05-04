<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invited_by')
                  ->constrained('users')->onDelete('cascade');
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->boolean('used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        // Ajouter statut pending sur users
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['active', 'pending'])
                  ->default('active')->after('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};