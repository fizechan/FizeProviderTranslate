<?php

namespace handler;

use fize\provider\translate\handler\YouDao;
use PHPUnit\Framework\TestCase;


class TestYouDao extends TestCase
{

    public function testArticle()
    {
        $config = [
            'appkey' => '2d757d5c19583e6d',
            'seckey' => 'Wyh4BmZWWTvPw486ShlUcaZATm8XHz0B'
        ];
        $translate = new YouDao($config);
        $zh_string = '定制化翻译API语言方向目前只支持中文和英文。';
        $en_string = $translate->article($zh_string, 'en', 'zh');
        var_dump($en_string);
        self::assertIsString($en_string);
    }

    public function testSentence()
    {
        $config = [
            'appkey' => '2d757d5c19583e6d',
            'seckey' => 'Wyh4BmZWWTvPw486ShlUcaZATm8XHz0B'
        ];
        $translate = new YouDao($config);
        $zh_string = '我是中国人，我爱中国！';
        $en_string = $translate->sentence($zh_string, 'en');
        var_dump($en_string);
        self::assertIsString($en_string);
    }

    public function testWord()
    {
        $config = [
            'appkey' => '2d757d5c19583e6d',
            'seckey' => 'Wyh4BmZWWTvPw486ShlUcaZATm8XHz0B'
        ];
        $translate = new YouDao($config);
        $zh_string = '中国人';
        $en_string = $translate->word($zh_string, 'en');
        var_dump($en_string);
        self::assertIsString($en_string);
    }
}
