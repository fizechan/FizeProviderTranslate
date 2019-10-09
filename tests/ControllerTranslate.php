<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controller;

use fize\translate\Translate;

/**
 * Description of Index
 *
 * @author Administrator
 */
class ControllerTranslate
{
	
	/**
	 * 测试1
	 */
	public function actionBaidu()
    {
        $options1 = [
            'appid' => '20160118000009064',
            'seckey' => 'ae7qRtMhVEwlsXiglNnt'
        ];
        Translate::init('BaiDu', $options1);
        $zh_string = '定制化翻译API语言方向目前只支持中文和英文。';
        $en_string = Translate::article($zh_string, 'zh', 'en');
        var_dump($en_string);

        $options2 = [
            'appkey' => '2d757d5c19583e6d',
            'seckey' => 'Wyh4BmZWWTvPw486ShlUcaZATm8XHz0B'
        ];
        Translate::init('YouDao', $options2);
        $zh_string = '定制化翻译API语言方向目前只支持中文和英文。';
        $en_string = Translate::article($zh_string, 'zh-CHS', 'EN');
        var_dump($en_string);
	}
}
