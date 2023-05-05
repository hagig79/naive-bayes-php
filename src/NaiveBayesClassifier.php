<?php

namespace NaiveBayes;

use Youaoi\MeCab\MeCab;

use NaiveBayes\ArticleInterface;

/**
 * ナイーブベイズ分類器
 */
class NaiveBayesClassifier
{
    private array $trainData = [];

    /**
     * 記事を学習する
     *
     * @param ArticleInterface[][] $trainArticles
     */
    public function train(array $trainArticles)
    {
        $mecab = $this->createMeCab();
        $categoryWordCounts = [];
        $this->trainData = [];

        foreach ($trainArticles as $category => $articles) {
            $categoryWordCount = 0;
            $words = [];
            $wordCounts = [];
            foreach ($articles as $article) {
                $mecabWords = $mecab->analysis($article->getContent());
                foreach ($mecabWords as $mecabWord) {
                    if ($mecabWord->speech === '名詞') {
                        $categoryWordCount++;
                        $words[] = $mecabWord->text;
                        if (isset($wordCounts[$mecabWord->text])) {
                            $wordCounts[$mecabWord->text]++;
                        } else {
                            $wordCounts[$mecabWord->text] = 1;
                        }
                    }
                }
            }
            $this->trainData[$category] = [
                'count' => $categoryWordCount,
                'words' => $wordCounts,
            ];
            $categoryWordCounts[$category] = $categoryWordCount;
        }
    }

    /**
     * 記事を分類する
     *
     * @param ArticleInterface $testArticle
     * @return array
     */
    public function predict(ArticleInterface $testArticle): array
    {
        $mecab = $this->createMeCab();
        $mecabWords = $mecab->analysis($testArticle->getContent());
        $words = [];
        foreach ($mecabWords as $mecabWord) {
            if ($mecabWord->speech === '名詞') {
                $words[] = $mecabWord->text;
            }
        }

        $maxScore = -PHP_FLOAT_MAX;
        $maxCategory = '';
        foreach ($this->trainData as $category => $data) {
            $score = 0;
            foreach ($words as $word) {
                $score += log(($data['words'][$word] ?? 0) + 1) - log($data['count'] + count($words));
            }
            if ($score > $maxScore) {
                $maxScore = $score;
                $maxCategory = $category;
            }
        }

        return [
            $maxCategory,
            $maxScore,
        ];
    }

    private function createMeCab(): Mecab
    {
        $mecab = new Mecab();
        $mecab->setCommand('mecab -E ""');
        return $mecab;
    }
}
