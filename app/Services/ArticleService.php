<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/3/30
 * Time: 16:00
 */

namespace App\Services;


use App\Exceptions\ArticleException;
use App\Exceptions\CategoryException;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Model\Article;
use App\Repositorys\ArticleRepository;
use App\Repositorys\CategoryRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class ArticleService
{
    /**
     * @var $articleRepository ArticleRepository
     */
    private $articleRepository;

    /**
     * @var $categoryRepository CategoryRepository
     */
    private $categoryRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * 创建一篇文章
     * @param ArticleRequest $articleRequest
     * @return bool
     * @throws ArticleException
     * @throws CategoryException
     * @throws Exception
     */
    public function createArticle(ArticleRequest $articleRequest)
    {
        $userInfo = Auth::user();


        $title = $articleRequest->input('title');
        // 判断文章标题是否重复
        $bool = $this->articleRepository->isExistsTitle($title, $userInfo['user_id']);
        if (false === $bool) {
            throw new ArticleException(['ARTICLE_TITLE_EXISTS']);
        }

        // 检测分类
        $bool = $this->categoryRepository->isExistsCategoryId($articleRequest->input('category_id'));
        if (false === $bool) {
            throw new CategoryException(['CATEGORY_NOT_EXISTS']);
        }

        // 创建文章
        $articleArr = $articleRequest->all();
        $articleArr['author_id'] = $userInfo['user_id'];
        $bool = $this->articleRepository->createArticle($articleArr);
        if (false === $bool) {
            throw new ArticleException(['CREATE_ARTICLE_ERROR']);
        }

        // 文章添加成功 生成md文件
        createMarkdownFile($userInfo['user_name'], $title, $articleArr['content']);

        return $bool;
    }

    /**
     * 保存文章内容
     * @param ArticleRequest $articleRequest
     * @param int $articleId
     * @return bool
     * @throws ArticleException
     * @throws CategoryException
     * @throws Exception
     */
    public function saveArticle(ArticleRequest $articleRequest, int $articleId) : bool
    {
        // TODO 权限判断
        $user = Auth::user();
        // 判断article_id是否存在
        $articleOld = $this->articleRepository->getOneArticleById($articleId, $user['user_id']);
        if (is_null($articleOld)) {
            throw new ArticleException(['ARTICLE_NOT_FOUND']);
        }

        // 判断分类是否修改
        if (!empty($articleRequest->input('category_id')) &&
            $articleOld->category_id !== $articleRequest->input('category_id')) {

            // 检测分类是否合法
            $bool = $this->categoryRepository->isExistsCategoryId($articleRequest->input('category_id'));

            if (false === $bool) {
                throw new CategoryException(['CATEGORY_NOT_EXISTS', $articleRequest->input('category_id')]);
            }
        }

        $articleArray = $articleRequest->all();
        $bool = $this->articleRepository->saveArticle($articleOld, $articleArray);
        if (false === $bool) {
            throw new ArticleException(['SAVE_ARTICLE_ERROR']);
        }

        // 保存文章内容
        $title = $articleOld->article_title;
        if (!empty($articleArr['article_title'])
            && $articleArr['article_title'] !== $articleOld->article_title) {

            renameMarkdownFile($articleOld->author_name, $articleOld->article_title, $articleArr['article_title']);
            $title = $articleArr['article_title'];
        }

        return createMarkdownFile($articleOld->author_name, $title, $articleArr['article_content']);
    }

    /**
     * 获取归档文章列表
     * @param int $page
     * @return mixed
     */
    public function getArchiveArticle(int $page = 20)
    {
        return $this->articleRepository->getArticleList([], $page);
    }

    /**
     * 获取分类下文章列表
     *
     * @param string $name
     * @param int $page
     * @return mixed
     * @throws CategoryException
     */
    public function getArticleByCategoryName(string $name, int $page = 20)
    {
        $category = $this->categoryRepository->getCategoryByName($name);
        if (empty($category)) {

            throw new CategoryException(['CATEGORY_NOT_EXISTS']);
        }

        return $this->articleRepository->getArticleList([
            'category' => $category->id
        ], $page);
    }

    /**
     * 根据标签获取文章列表
     *
     * @param string $name
     * @param int $page
     * @return mixed
     */
    public function getArticleByTagName(string $name, int $page = 20)
    {
        return $this->articleRepository->getArticleList(['tag' => $name], $page);
    }
    
    /**
     * 获取首页文章列表
     * @param int $page
     * @return mixed|null
     * @throws Exception
     */
    public function getFrontArticle(int $page = 5)
    {
        $result = $this->articleRepository->getArticleList([], $page);

        if (!empty($result)) {
            foreach ($result as $article) {
                $article->excerpt = readMarkdownFileContent($article->user->name, $article->title, true);
            }
        }

        return $result;
    }

    /**
     * 获取文章列表
     * @param array $filter
     * @param int $page
     * @return mixed
     */
    public function getArticleListByFilter(array $filter, int $page = 5)
    {
        return $this->articleRepository->getArticleList($filter, $page);
    }

    /**
     * 获取指定文章
     * @param int $articleId
     * @return mixed
     * @throws ArticleException
     * @throws Exception
     */
    public function article(int $articleId)
    {
        // 获取文章
        $article = $this->articleRepository->getOneArticleById($articleId);
        if (is_null($article)) {
            throw new ArticleException(['ARTICLE_NOT_FOUND']);
        }

        // 获取文章内容
        $article->content = readMarkdownFileContent($article->user->name, $article->title, false);

        return $article;
    }

    /**
     * 删除指定文章
     * @param int $articleId
     * @return bool
     */
    public function deleteArticle(int $articleId)
    {
        // TODO 权限判断
        return $this->articleRepository->destory($articleId, Auth::id());
    }
}