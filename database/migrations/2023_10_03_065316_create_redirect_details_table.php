<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedirectDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirect_details', function (Blueprint $table) {
            $table->id();
            $table->integer('redirect_id')->index();
            $table->integer('domain_id')->index();
            $table->string('ip_address');
            $table->string('ip_prefix')->index();
            $table->boolean('is_robot');
            $table->text('robot');
            $table->string('device_name');
            $table->string('device_name_full');
            $table->string('platform_name');
            $table->string('country');
            $table->integer('count');
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
        Schema::dropIfExists('redirect_details');
    }
}
