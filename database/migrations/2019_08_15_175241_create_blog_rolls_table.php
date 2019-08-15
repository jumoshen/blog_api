<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_rolls', function (Blueprint $table) {
            $table->increments('id');

            $table->string('web_name', 30)->comment('网站名称');
            $table->string('link', 50)->comment('网站链接');
            $table->string('logo_link')->comment('logo链接');
            $table->string('email', 50)->comment('邮箱');
            $table->unsignedTinyInteger('active')->default(0)->comment('是否激活 0/1');
            $table->string('remark')->nullable()->comment('备注');

            $table->dateTime('pass_time')->nullable()->comment('审核通过时间');
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
        Schema::dropIfExists('blog_rolls');
    }
}
