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
        Schema::create('assistances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to the user who created the assistance
            $table->string('name'); // Assistance name
            $table->string('type')->nullable();
            $table->dateTime('deadline'); // Deadline for assistance
            $table->text('description')->nullable(); // Description of the assistance
            $table->integer('number_of_questions')->default(0); // Number of questions
            $table->text('survey_instructions')->nullable(); // Survey instructions
            $table->string('target_audience')->nullable(); // Target audience
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistances');
    }
};
