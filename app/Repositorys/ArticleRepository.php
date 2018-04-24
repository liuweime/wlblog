<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/30
 * Time: 15:59
 */

namespace App\Repositorys;


use App\Exceptions\ArticleException;
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
        return $this->article
                    ->where('title', $title)
                    ->where('author_id', $authorId)
                    ->published()
                    ->exists();
    }

    /**
     * 根据articleId判断文章是否存在
     * @param int $articleId
     * @return bool
     */
    public function isExistsArticleByArticleId(int $articleId) : bool
    {
        return $this->article->where('id', $articleId)->published()->exists();
    }

    /**
     * 根据id获取一篇文章
     * @param int $articleId
     * @param int $authorId
     * @return mixed
     */
    public function getOneArticleById(int $articleId, int $authorId = 0)
    {
        $query = $this->article;
        if ($authorId) {
            $query = $this->article->where('author_id', $authorId);
        }
        return $query->published()->find($articleId);
    }

    /**
     * 根据作者id获取文章
     * @param int $authorId
     * @return mixed
     */
    public function getArticleByAuthorId(int $authorId)
    {
        return $this->article->where('author_id', $authorId)->published()->get();
    }

    /**
     * 获取文章列表
     * @param int $page
     * @return mixed
     */
    public function getArticleList($page = 5)
    {
        return $this->article->orderBy('created_at', 'desc')->published()->simplePaginate($page);
    }

    /**
     * 创建一篇文章
     * @param array $articleArr
     * @return bool
     * @throws ArticleException
     */
    public function createArticle(array $articleArr) : bool
    {
        if (empty($articleArr['article_title'])) {
            throw new ArticleException(['ARTICLE_TITLE_NOT_EMPTY']);
        }
        if (empty($articleArr['author_id']) || empty($articleArr['author_name'])) {
            throw new ArticleException(['AUTHOR_NOT_EMPTY']);
        }
        $this->article->article_title = $articleArr['article_title'];
        $this->article->author_id = $articleArr['author_id'];
        $this->article->author_name = $articleArr['author_name'];
        $this->article->category_id = !empty($articleArr['category_id'])? : 0;
        $this->article->article_tag = !empty($articleArr['article_tag'])? : '';
        $this->article->is_show_comment = isset($articleArr['is_show_comment']) ?
            $articleArr['is_show_comment'] : 1;
        $this->article->article_status = $articleArr['article_status'] === Article::ARTICLE_DRFAT ?
            Article::ARTICLE_DRFAT : Article::ARTICLE_PUBLIS;
        $this->article->article_type = isset($articleArr['article_type']) ? $articleArr['article_type'] : 0;
        $this->article->publish_time = isset($articleArr['publish_time']) ? $articleArr['publish_time'] : 0;

        return $this->article->save();
    }

    /**
     * 保存文章
     * @param \stdClass $article
     * @param array $articleArr
     * @return bool
     */
    public function saveArticle(\stdClass $article, array $articleArr) : bool
    {
        $article->category_id = !empty($articleArr['category_id']) ? : $article->category_id;
        $article->article_title = !empty($articleArr['article_title']) ? : $article->article_title;
        $article->author_id = !empty($articleArr['author_id']) ? : $article->author_id;
        $article->author_name = !empty($articleArr['author_name']) ? : $article->author_name;
        $article->article_tag = !empty($articleArr['article_tag']) ? : $article->article_tag;
        $article->is_show_comment = !empty($articleArr['is_show_comment']) ? : $article->is_show_comment;
        $article->article_status = !empty($articleArr['article_status']) ? : $article->article_status;
        $article->article_type = !empty($articleArr['article_type']) ? : $article->article_type;
        $article->publish_time = !empty($articleArr['publish_time']) ? : $article->publish_time;
        if ($article->article_status === Article::ARTICLE_PUBLIS) {
            $article->publish_time = 0;
        }

        return $this->article->save();
    }
}