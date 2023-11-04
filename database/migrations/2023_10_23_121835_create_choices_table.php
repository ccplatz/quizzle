<?php

use App\Models\Answer;
use App\Models\QuizPosition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_position_id');
            $table->foreignIdFor(Answer::class)->constrained();
            $table->timestamps();

            $table->foreign('quiz_position_id')->references('id')->on('quiz_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};