<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable(false)->default(0)->index()->comment('父级id');
            $table->integer('lft')->nullable(false)->default(0)->index()->comment('左叶');
            $table->integer('rgt')->nullable(false)->default(0)->index()->comment('右叶');
            $table->integer('depth')->nullable(false)->default(0)->comment('深度');
            $table->string('category_name', 20)->nullable(false)->default('')->comment('分类名称');
            $table->string('category_alias' , 20)->nullable(false)->default('')->comment('分类别名');
            $table->string('category_desc' , 120)->default('')->comment('分类描述');
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
        Schema::dropIfExists('categorys');
    }
}
