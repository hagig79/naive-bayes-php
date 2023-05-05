<?php
declare(strict_types=1);

namespace NaiveBayes;

class TextFileArticle implements ArticleInterface
{
    private $category;
    private $content;
    private $filePath;

    public function __construct(string $category, string $content, string $filePath)
    {
        $this->category = $category;
        $this->content = $content;
        $this->filePath = $filePath;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
