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

        $title = $articleRequest->input('article_title');
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
        $this->createMarkdownFile($userInfo['user_name'], $title, $articleArr['article_content']);

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

            $this->renameMarkdownFile($articleOld->author_name, $articleOld->article_title, $articleArr['article_title']);

            $title = $articleArr['article_title'];
        }

        return $this->createMarkdownFile($articleOld->author_name, $title, $articleArr['article_content']);
    }

    /**
     * 获取文章列表
     * @param array $filter
     * @param int $page
     * @return ArticleCollection|null
     */
    public function getArticleList(array $filter = [], $page = 5)
    {
        $list = $this->articleRepository->getArticleList($filter, $page);
        if (empty($list)) {
            return null;
        }

        return new ArticleCollection($list);
    }

    /**
     * 获取指定文章
     * @param int $articleId
     * @return mixed
     * @throws ArticleException
     */
    public function getArticle(int $articleId)
    {
        // 获取文章
        $article = $this->articleRepository->getOneArticleById($articleId);
        if (is_null($article)) {
            throw new ArticleException(['ARTICLE_NOT_FOUND']);
        }
        $article = ArticleResource::collection($article);
        // 获取文章内容
        $article->content = $this->readMakrdownFileContent($article->author_name, $article->title);

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

    /**
     * 格式化文章内容
     * @param \stdClass $article
     * @return \stdClass
     * @throws ArticleException
     */
    private function parseArticleData(\stdClass $article)
    {
        // 文章标签
        if ($article->article_tag) {
            $article->article_tag = explode(',', $article->article_tag);
        }
        // 类型
        switch ($article->article_type) {
            case Article::ARTICLE_TYPE_ORIGINAL:
                $article->article_type_text = '原创';
                break;
            case Article::ARTICLE_TYPE_REPRODUCED:
                $article->article_type_text = '转载';
                break;
            case Article::ARTICLE_TYPE_TRANSLATION:
                $article->article_type_text = '翻译';
                break;
            default:
                $article->article_type_text = '未知';
                break;
        }
        // 获取文章内容
        $user = Auth::user();
        $content = $this->readMakrdownFileContent($user['user_name'], $article->article_title);
        $article->content = '';
        if (!empty($content)) {
            $article->content = $content;
        }

        return $article;
    }

    /**
     * 生成markdown文件
     *
     * @param string $username
     * @param string $title
     * @param string $content
     * @return bool
     * @throws Exception
     */
    protected function createMarkdownFile(string $username, string $title, string $content) : bool
    {
        try {
            $fileDir = $this->getMarkdownFilePath($username);
            if (false === is_dir($fileDir)) {
                mkdir($fileDir, 755);
            }
            $filepath = $fileDir . $title . '.md';

            return false === file_put_contents($filepath, $content, LOCK_EX)
                ? false : true;
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * 获取markdown文件内容
     * @param string $username
     * @param string $title
     * @return string
     * @throws ArticleException
     */
    protected function readMakrdownFileContent(string $username, string $title) : string
    {
        $filedir = $this->getMarkdownFilePath($username);
        $filepath = $filedir . $title . '.md';

        if (!file_exists($filepath)) {
            throw new ArticleException(['MARKDOWN_FILE_NOT_FOUND']);
        }

        return file_get_contents($filepath);
    }

    /**
     * @param string $username
     * @return string
     */
    protected function getMarkdownFilePath(string $username) : string
    {
        $uploadConfig = config('upload');
        return public_path() . DIRECTORY_SEPARATOR . trim($uploadConfig['article'], DIRECTORY_SEPARATOR)
            . md5($username) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param string $username
     * @param string $oldtitle
     * @param string $newtitle
     * @throws Exception
     */
    protected function renameMarkdownFile(string $username, string $oldtitle, string $newtitle)
    {
        try {
            // 文件路径
            $dir = $this->getMarkdownFilePath($username);
            if (false === is_dir($dir)) {
                mkdir($dir, 755);
            }
            $oldfile = $dir . $oldtitle . '.md';
            $newfile = $dir . $newtitle . '.md';

            $bool = rename($oldfile, $newfile);
            if (false === $bool) {
                throw new \Exception('rename old title error');
            }
        } catch (\Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }
}