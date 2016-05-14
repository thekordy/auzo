<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRoleIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // get users table from the configured user model in Config/auzo.php
        Schema::table(app('AuzoUser')->getTable(), function (Blueprint $table) {
            $table->integer('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(app('AuzoUser')->getTable(), function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
    }
}
