<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('github_id')->nullable()->after('role');
            $table->string('github_token')->nullable()->after('github_id');
            $table->string('avatar')->nullable()->after('github_token');
            // Le mot de passe devient optionnel (login GitHub = pas de mdp)
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['github_id', 'github_token', 'avatar']);
        });
    }
};