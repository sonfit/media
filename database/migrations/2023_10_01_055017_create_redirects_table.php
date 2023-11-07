<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->string('redirect_name')->index();
            $table->string('redirect_url')->index();
            $table->string('redirect_url_block')->nullable();
            $table->string('domain_id')->index();
            $table->text('redirect_html')->nullable();
            $table->boolean('is_devices')->default(0)->comment('1: devices - 0: country');
            $table->longText('country_value')->nullable();
            $table->longText('devices_value')->nullable();
            $table->date('exp_date_at');
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
        Schema::dropIfExists('redirects');
    }
}
