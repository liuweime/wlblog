<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/30
 * Time: 15:59
 */

namespace App\Repositorys;


use App\Model\Article;

class ArticleRepository
{
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @param string $title
     * @param int $authorId
     * @return bool
     */
    public function isExistsTitle(string $title, int $authorId) : bool
    {
        return is_null(
            $this->article->where(['article_title' => $title, 'author_id' => $authorId])->select('id')->first()
        ) ? false : true;
    }

    /**
     * 创建一篇文章
     * @param array $articleArr
     * @return bool
     */
    public function createArticle(array $articleArr) : bool
    {
        $this->article->article_title = $articleArr['article_title'];
        $this->article->author_id = $articleArr['author_id'];
        $this->article->author_name = $articleArr['author_name'];
        $this->article->category_id = $articleArr['category_id'];
        $this->article->article_tag = $articleArr['article_tag'];
        $this->article->is_show_comment = isset($articleArr['is_show_comment']) ?
            $articleArr['is_show_comment'] : 1;
        $this->article->article_status = $articleArr['article_status'] === Article::ARTICLE_DRFAT ?
            Article::ARTICLE_DRFAT : Article::ARTICLE_PUBLIS;
        $this->article->article_type = isset($articleArr['article_type']) ? $articleArr['article_type'] : 0;
        $this->article->publish_time = isset($articleArr['publish_time']) ? $articleArr['publish_time'] : 0;

        return $this->article->save();
    }
}