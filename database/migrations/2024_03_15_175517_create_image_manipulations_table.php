<?php

use App\Models\Album;
use App\Models\User;
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
        Schema::create('image_manipulations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('path', 2000);
            $table->string('type', 25);
            $table->text('data');
            $table->string('output_path', 2000);
            $table->timestamp('created_at');
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Album::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_manipulations');
    }
};
