<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Si la table n'existe pas, la créer complètement
        if (!Schema::hasTable('task_attachments')) {
            Schema::create('task_attachments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('filename');
                $table->string('path');
                $table->string('mime_type');
                $table->unsignedBigInteger('size');
                $table->timestamps();
            });
        } else {
            // Ajouter les colonnes manquantes
            Schema::table('task_attachments', function (Blueprint $table) {
                if (!Schema::hasColumn('task_attachments', 'filename'))
                    $table->string('filename');
                if (!Schema::hasColumn('task_attachments', 'path'))
                    $table->string('path');
                if (!Schema::hasColumn('task_attachments', 'mime_type'))
                    $table->string('mime_type');
                if (!Schema::hasColumn('task_attachments', 'size'))
                    $table->unsignedBigInteger('size');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('task_attachments');
    }
};