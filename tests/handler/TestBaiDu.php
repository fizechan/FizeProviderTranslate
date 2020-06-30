<?php

namespace handler;

use fize\provider\translate\handler\BaiDu;
use PHPUnit\Framework\TestCase;


class TestBaiDu extends TestCase
{

    public function testArticle()
    {
        $config = [
            'appid' => '20160118000009064',
            'seckey' => 'ae7qRtMhVEwlsXiglNnt'
        ];
        $translate = new BaiDu($config);
        $zh_string = '定制化翻译API语言方向目前只支持中文和英文。';
        $en_string = $translate->article($zh_string, 'en', 'zh');
        var_dump($en_string);
        self::assertIsString($en_string);
    }

    public function testSentence()
    {
        $config = [
            'appid' => '20160118000009064',
            'seckey' => 'ae7qRtMhVEwlsXiglNnt'
        ];
        $translate = new BaiDu($config);
        $zh_string = '我是中国人，我爱中国！';
        $en_string = $translate->sentence($zh_string, 'en');
        var_dump($en_string);
        self::assertIsString($en_string);
    }

    public function testWord()
    {
        $config = [
            'appid' => '20160118000009064',
            'seckey' => 'ae7qRtMhVEwlsXiglNnt'
        ];
        $translate = new BaiDu($config);
        $zh_string = '中国人';
        $en_string = $translate->word($zh_string, 'en');
        var_dump($en_string);
        self::assertIsString($en_string);
    }
}
