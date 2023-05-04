<?php
use Youaoi\MeCab\MeCab;

require_once 'vendor/autoload.php';

$text = '明日は運動会に行く予定です。お弁当が楽しみです。';

$mecab = new MeCab();

// 形態素解析
$nodes = $mecab->parse($text);

var_dump($nodes);
