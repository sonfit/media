<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRingtonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ringtones', function (Blueprint $table) {
            $table->id();
            $table->string('ringtone_name')->index();
            $table->string('ringtone_file')->index();
            $table->integer('ringtone_view_count')->default(1000);
            $table->integer('ringtone_like_count')->default(1000);
            $table->integer('ringtone_download_count')->default(1000);
            $table->integer('ringtone_feature')->default(0);
            $table->string('ringtone_extension')->default('audio/mp3');
            $table->string('ringtone_hash')->nullable()->index();
            $table->string('ringtone_type')->default('Ringtone');
            $table->tinyInteger('ringtone_status')->default(0);
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
        Schema::dropIfExists('ringtones');
    }
}
