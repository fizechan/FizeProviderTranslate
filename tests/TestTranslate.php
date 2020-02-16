<?php


use fize\provider\translate\Translate;
use PHPUnit\Framework\TestCase;

class TestTranslate extends TestCase
{

    public function testGetInstance()
    {
        $config1 = [
            'appid' => '20160118000009064',
            'seckey' => 'ae7qRtMhVEwlsXiglNnt'
        ];
        $zh_string = '定制化翻译API语言方向目前只支持中文和英文。';
        $en_string = Translate::getInstance('BaiDu', $config1)->article($zh_string, 'en');
        var_dump($en_string);
        self::assertIsString($en_string);
    }
}
