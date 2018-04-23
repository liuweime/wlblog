<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id')->comment('文章id');
            $table->integer('author_id')->unsigned()->nullable(false)->default(0)->comment('作者ID');
            $table->integer('category_id')->unsigned()->nullable(false)->default(0)->comment('文章分类id');
            $table->string('title' , 100)->nullable(false)->default('')->comment('文章标题');
            $table->string('tag' , 100)->nullable(false)->default('')->comment('文章标签');
            $table->tinyInteger('is_show_comment')->unsigned()->nullable(false)->default(0)->comment('是否显示评论');
            $table->tinyInteger('status')->unsigned()->nullable(false)->default(0)->comment('0：草稿 1：发布 2：待审 3：删除');
            $table->tinyInteger('type')->unsigned()->nullable(false)->default(0)->comment(' 0：原创 1：转载 2：翻译');
            $table->integer('publish_time')->unsigned()->nullable(false)->default(0)->comment('自动发布时间');
            $table->index('author_id','idx_article_author_id');
            $table->index('category_id','idx_article_category_id');
            $table->index(['id','type'],'idx_article_type');
            $table->index(['id','status'],'idx_article_status');
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
        Schema::dropIfExists('articles');
    }
}
