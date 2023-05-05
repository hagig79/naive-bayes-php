<?php
declare(strict_types=1);

namespace NaiveBayes;

/**
 * Livedoor ニュースコーパスの記事を読み込む
 */
class LivedoorArticleReader implements ArticleReaderInterface
{
    public function read(string $path, string $category): ArticleInterface
    {
        $content = file_get_contents($path);
        return new TextFileArticle($category, $content, $path);
    }

    /**
     * @return ArticleInterface[][]
     */
    public function readAll(int $count): array
    {
        $articles = [];
        foreach (glob('text/*', GLOB_ONLYDIR) as $dir) {
            $category = basename($dir);
            $i = 0;
            foreach (glob($dir . '/*.txt') as $path) {
                $article = (new LivedoorArticleReader())->read($path, $category);
                $articles[$category][] = $article;
                $i++;
                if ($i >= $count) {
                    break;
                }
            }
        }

        return $articles;
    }
}
