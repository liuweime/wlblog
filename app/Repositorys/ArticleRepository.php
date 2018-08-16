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
     * @param array $filter
     * @param int $page
     * @return mixed
     */
    public function getArticleList(array $filter = [], $page = 5)
    {
        $builder = $this->article;
        if (isset($filter['title'])) {
            $builder = $builder->where('title', 'like', $filter['title'] . '%');
        }
        if (isset($filter['category'])) {
            $builder = $builder->where('category_id', $filter['category']);
        }
        if (isset($filter['status'])) {
            $builder = $builder->where('status', $filter['status']);
        } else {
            $builder = $builder->published();
        }
        if (isset($filter['author'])) {
            $builder = $builder->from('articles as a')->innerJoin('users as u', function ($join) {
                $join->on('a.author_id', '=', 'u.id');
            })->where('u.name', 'like', $filter['author'] . '%')->select('a.*');
        }

        return $builder->orderBy('created_at', 'desc')
            ->simplePaginate($page);
    }

    /**
     * 创建一篇文章
     * @param array $articleArr
     * @return bool
     */
    public function createArticle(array $articleArr) : bool
    {
        $this->article->fill($articleArr);

        $this->article->is_show_comment = isset($articleArr['is_show_comment']) ? $articleArr['is_show_comment'] : 1;
        $this->article->article_status = $articleArr['article_status'] === Article::ARTICLE_DRFAT ?
            Article::ARTICLE_DRFAT : Article::ARTICLE_PUBLIS;

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
        $article->fill($articleArr);
        return $article->save();
    }

    /**
     * 删除文章
     * @param int $articleId
     * @param int $authorId
     * @return bool
     */
    public function destory(int $articleId, int $authorId)
    {
        $article = $this->getOneArticleById($articleId, $authorId);
        if (empty($article)) {

            return true;
        }

        $article->status = Article::ARTICLE_DELETE;

        return $article->save();
    }
}