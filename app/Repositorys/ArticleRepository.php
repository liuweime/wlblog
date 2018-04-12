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
        return is_null(
            $this->article->where([
                ['article_title', $title],
                ['author_id', $authorId],
                ['article_status', Article::ARTICLE_PUBLIS]
            ])->select('id')->first()
        ) ? false : true;
    }

    /**
     * 根据articleId判断文章是否存在
     * @param int $articleId
     * @return bool
     */
    public function isExistsArticleByArticleId(int $articleId) : bool
    {
        return is_null(
            $this->article->where([
                ['id', $articleId],
                ['article_status', Article::ARTICLE_PUBLIS]
            ])->select('id')->first()
        ) ? false : true;
    }

    /**
     * 根据id获取一篇文章
     * @param int $articleId
     * @param int $authorId
     * @return mixed
     */
    public function getOneArticleById(int $articleId, int $authorId = 0)
    {
        $condition = [];
        $condition[] = ['id', $articleId];
        $condition[] = ['article_status', Article::ARTICLE_PUBLIS];
        if ($authorId) {
            $condition[] = ['author_id', $authorId];
        }
        return $this->article->where($condition)->first();
    }

    /**
     * 根据作者id获取文章
     * @param int $authorId
     * @return mixed
     */
    public function getArticleByAuthorId(int $authorId)
    {
        return $this->article->from('articles AS a')->where([
            ['author_id', $authorId],
            ['article_status', Article::ARTICLE_PUBLIS]
        ])->leftJoin('categorys AS c', function ($join) {
            $join->on('a.category_id', '=', 'c.id');
        })->select([
            'ia.d','author_id','author_name','a.category_id','c.category_name',
            'article_title','article_tag','is_show_comment','article_status',
            'article_type','publish_time','created_at','updated_at'
        ])->get();
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