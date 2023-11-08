<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDomainLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domain_login_logs', function (Blueprint $table) {
            $table->string('device_name')->after('ip_prefix')->nullable();
            $table->string('device_name_full')->after('device_name')->nullable();
            $table->string('browser')->after('device_name_full')->nullable();
            $table->string('platform_name')->after('browser')->nullable();
            $table->string('country')->after('platform_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domain_login_logs', function (Blueprint $table) {
            //
        });
    }
}
