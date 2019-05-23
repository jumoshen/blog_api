<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('active')->default(1)->comment('状态 0/1 禁止/正常');
            $table->rememberToken();

            $table->unsignedTinyInteger('is_admin')->default(0)->comment('是否是管理员 0/1 是/不是');
            $table->dateTime('be_admin_time')->nullable()->comment('成为管理员的时间');
            $table->integer('sign_version')->default(0)->comment('jwt额外盐值，需要登出用户时改变改参数即可');
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
