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
        Schema::create('shared_notes', function (Blueprint $table) {
            $table->id('share_id');
            $table->foreignid('note_id')->constrained('notes', 'note_id')->onDelete('cascade');
            $table->foreignid('shared_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignid('shared_with_id')->constrained('users')->onDelete('cascade');
            $table->enum('permission', ['view', 'edit']);
            $table->timestamp('shared_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_notes');
    }
};
