<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tid')->unsigned()->nullable(false)->default(0)->comment('主贴id');
            $table->integer('fid')->unsigned()->nullable(false)->default(0)->comment('父id');
            $table->string('nickname', 50)->nullalbe(false)->default('')->comment('用户昵称');
            $table->string('email', 50)->nullalbe(false)->default('')->comment('用户邮箱');
            $table->text('content')->comment('贴子内容');
            $table->tinyInteger('status')->nullable(false)->default(1)->comment('帖子状态，0：删除 1显示');
            $table->index('tid','idx_topic_id');
            $table->index('fid','idx_parent_id');
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
        Schema::dropIfExists('comments');
    }
}
