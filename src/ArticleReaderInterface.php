<?php
declare(strict_types=1);

namespace NaiveBayes;

interface ArticleReaderInterface
{
    public function read(string $path, string $category): ArticleInterface;
}
