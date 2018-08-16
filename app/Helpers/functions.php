<?php
/**
 * Created by PhpStorm.
 * User: 刘威
 * Date: 2018/4/26
 * Time: 15:02
 */


/**
 * 生成markdown文件
 *
 * @param string $username
 * @param string $title
 * @param string $content
 * @return bool
 * @throws Exception
 */
function createMarkdownFile(string $username, string $title, string $content) : bool
{
    try {
        $fileDir = getMarkdownFilePath($username);
        if (false === is_dir($fileDir)) {
            mkdir($fileDir, 755, true);
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
 * @param bool $is_excerpt
 * @return string
 * @throws Exception
 */
function readMarkdownFileContent(string $username, string $title, $is_excerpt = false) : string
{
    try {

        $filedir = getMarkdownFilePath($username);
        $filepath = $filedir . $title . '.md';

        if (!file_exists($filepath)) {
            throw new Exception('文章不存在');
        }

        $content = file_get_contents($filepath);
        if ($is_excerpt === true) {
            // 获取分割符
            $split_tag = config('blog.split_tag');
            if (mb_strpos($content, $split_tag, 0, 'utf-8') !== false) {
                $content = mb_substr($content, 0, mb_strpos($content, $split_tag, 0, 'utf-8'), 'utf-8');
            } else {
                $content = strip_tags($content);
                $content = mb_substr($content, 0, 160, 'utf-8');
            }
        }

        return $content;
    } catch (\Exception $exception) {
        throw new Exception($exception->getMessage());
    }
}

/**
 * 获取markdown文件路径
 * @param string $username
 * @return string
 */
function getMarkdownFilePath(string $username) : string
{
    $uploadConfig = config('blog.upload');
    return public_path() . DIRECTORY_SEPARATOR . trim($uploadConfig, DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR . 'article' . DIRECTORY_SEPARATOR . md5($username) . DIRECTORY_SEPARATOR;
}

/**
 * 修改markdown文件名
 * @param string $username
 * @param string $oldtitle
 * @param string $newtitle
 * @throws Exception
 */
function renameMarkdownFile(string $username, string $oldtitle, string $newtitle)
{
    try {
        // 文件路径
        $dir = getMarkdownFilePath($username);
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