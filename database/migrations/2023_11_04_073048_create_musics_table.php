<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->id();
            $table->string('music_id_ytb')->index();
            $table->text('music_url')->nullable();
            $table->text('music_thumb')->nullable();
            $table->text('music_title')->nullable();
            $table->integer('music_expire')->nullable();
            $table->integer('music_lengthSeconds')->nullable();
            $table->integer('music_view_count')->default(1000);
            $table->integer('music_like_count')->default(1000);
            $table->integer('music_download_count')->default(1000);
            $table->tinyInteger('music_status')->default(0);
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
        Schema::dropIfExists('musics');
    }
}
