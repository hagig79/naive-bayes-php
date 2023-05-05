<?php
declare(strict_types=1);

namespace NaiveBayes;

interface ArticleInterface
{
    public function getCategory(): string;
    public function getContent(): string;
    public function getFilePath(): string;
}
