<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTableModification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
          $table->string('password')->nullable()->change();
          $table->string('avatar')->nullable();
          $table->string('provider');
          $table->string('provider_id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
          $table->string('password')->change();
          $table->dropColumn('avatar')();
          $table->dropColumn('provider');
          $table->dropColumn('provider_id')();          
        });
    }
}
