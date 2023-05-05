<?php
declare(strict_types=1);

use NaiveBayes\NaiveBayesClassifier;
use NaiveBayes\LivedoorArticleReader;

require_once 'vendor/autoload.php';

/**
 * 配列を分割する
 * 
 * @param array $array
 * @param int $n
 * @return array
 */
function separateArray(array $array, int $n): array
{
    $result1 = [];
    $result2 = [];

    foreach ($array as $key => $value) {
        $result1[$key] = array_slice($value, 0, $n);
        $result2[$key] = array_slice($value, $n);
    }

    return [$result1, $result2];
}

// livedoor ニュースコーパスを読み込む
$articleReader = new LivedoorArticleReader();
$articles = $articleReader->readAll(200);

// 学習用データと検証用データに分ける
[$trainArticles, $testArticles] = separateArray($articles, 100);

// ナイーブベイズ分類器を学習させる
$naiveBayesClassifier = new NaiveBayesClassifier();
$naiveBayesClassifier->train($trainArticles);

// 検証用データを分類して正解率を計算する
$corrects = [];
foreach ($testArticles as $category => $articles) {
    $corrects[$category] = [
        'correct' => 0,
        'count' => 0,
    ];
    foreach ($articles as $article) {
        [$predictedCategory, $predictedScore] = $naiveBayesClassifier->predict($article);
        if ($category === $predictedCategory) {
            $corrects[$category]['correct']++;
        }
        $corrects[$category]['count']++;
    }
}
$allCorrect = 0;
$count = 0;
echo '正解率' . PHP_EOL;
foreach ($corrects as $category => $correct) {
    echo $category . ' ' . $correct['correct'] . ' / ' . $correct['count'] . PHP_EOL;
    $allCorrect += $correct['correct'];
    $count += $correct['count'];
}
echo $allCorrect / $count . PHP_EOL;
