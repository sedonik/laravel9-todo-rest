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
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id')->unsigned()->autoIncrement()->unique();
            $table->integer('parent_task_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('user_id');
            $table->string('status', 32)->default('todo');
            $table->unsignedTinyInteger('priority')->default(5);
            $table->string('title');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('completed_at')->nullable()->default(null);
            $table->fullText('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
