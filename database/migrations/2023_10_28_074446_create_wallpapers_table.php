<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWallpapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpapers', function (Blueprint $table) {
            $table->id();
            $table->string('wallpaper_name')->index();
            $table->string('wallpaper_image')->index();
            $table->integer('wallpaper_view_count')->default(1000);
            $table->integer('wallpaper_like_count')->default(1000);
            $table->integer('wallpaper_download_count')->default(1000);
            $table->integer('wallpaper_feature')->default(0);
            $table->string('wallpaper_extension')->default('image/jpeg');
            $table->string('wallpaper_hash')->nullable()->index();
            $table->string('wallpaper_type')->default('Landscape');
            $table->tinyInteger('wallpaper_status')->default(0);
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
        Schema::dropIfExists('wallpapers');
    }
}
