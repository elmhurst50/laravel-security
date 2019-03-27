<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class AddUserFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(config('security.users_table'), function (Blueprint $table) {
            $table->boolean('restrict_ip')->default(true);
            $table->boolean('login_locked')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(config('security.users_table'), function (Blueprint $table) {
            $table->dropColumn('restrict_ip');
            $table->dropColumn('login_locked');
        });
    }
}
