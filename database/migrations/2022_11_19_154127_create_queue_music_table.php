<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_music', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_id')->constrained('queues');
            $table->string('reference_name');
            $table->string('url');
            $table->unsignedInteger('order')->nullable();
            $table->boolean('done')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queue_music');
    }
};
