<?php

namespace handler;

use fize\provider\translate\handler\WebXml;
use PHPUnit\Framework\TestCase;


class TestWebXml extends TestCase
{

    public function testArticle()
    {
        $translate = new WebXml();
        $zh_string = '定制化翻译API语言方向目前只支持中文和英文。';
        $en_string = $translate->article($zh_string, 'en', 'zh');
        var_dump($en_string);
        self::assertIsString($en_string);
    }

    public function testSentence()
    {

    }

    public function testWord()
    {
        $translate = new WebXml();
        $zh_string = '中国人';
        $en_string = $translate->word($zh_string, 'en');
        var_dump($en_string);
        self::assertIsString($en_string);
    }
}
