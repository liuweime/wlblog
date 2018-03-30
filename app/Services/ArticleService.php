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
use App\Repositorys\ArticleRepository;
use App\Repositorys\CategoryRepository;
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
     * @param ArticleRequest $articleRequest
     * @return bool
     * @throws ArticleException
     * @throws CategoryException
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
            throw new CategoryException('CATEGORY_EXISTS');
        }

        // 创建文章
        $articleArr = $articleRequest->all();
        $articleArr['author_id'] = $userInfo['user_id'];
        $articleArr['author_name'] = $userInfo['user_name'];
        $bool = $this->articleRepository->createArticle($articleArr);
        if (false === $bool) {
            throw new ArticleException(['CREATE_ARTICLE_ERROR']);
        }

        // 文章添加成功 生成md文件
        $this->createMarkdownFile($userInfo['user_name'], $title, $articleArr['article_content']);

        return $bool;
    }

    public function saveArticle(ArticleRequest $articleRequest) : bool
    {
        // 保存文章
    }

    /**
     * 生成markdown文件
     *
     * @param string $username
     * @param string $title
     * @param string $content
     * @return bool
     * @throws ArticleException
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
            throw new ArticleException($exception->getMessage());
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
            throw new ArticleException('MARKDOWN_FILE_NOT_FOUND');
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

}