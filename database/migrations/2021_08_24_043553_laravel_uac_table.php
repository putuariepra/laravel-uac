<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LaravelUacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('id')->unique();
        });

        Schema::create('uac_menu', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->integer('order');
            $table->string('title');
            $table->string('icon');
            $table->string('uri');            
            $table->timestamps();
        });

        Schema::create('uac_roles', function (Blueprint $table) {
            $table->id();                        
            $table->string('name');
            $table->string('slug')->unique();            
            $table->timestamps();
        });

        Schema::create('uac_role_menu', function (Blueprint $table) {                              
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
        });

        Schema::create('uac_role_users', function (Blueprint $table) {                              
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
        });

        Artisan::call('db:seed', [
            '--class' => 'LaravelUacSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('uac_menu');
        Schema::dropIfExists('uac_roles');
        Schema::dropIfExists('uac_role_users');
        Schema::dropIfExists('uac_role_menu');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
        });
    }
}
