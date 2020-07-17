<?php

namespace fize\provider\translate\handler;

use DOMDocument;
use RuntimeException;
use fize\net\Http;
use fize\provider\translate\TranslateHandler;

/**
 * WebXml
 *
 * 号称永久免费，但功能不够强大
 */
class WebXml extends TranslateHandler
{

    /**
     * 构造
     * @param array $config
     */
    public function __construct(array $config = null)
    {
        parent::__construct($config);
    }

    /**
     * 文章翻译
     * 如果是XML、HTML，则只翻译字符节点
     * @param string $content 要翻译的内容
     * @param string $to      TO语言
     * @param string $from    FROM语言
     * @return string
     */
    public function article($content, $to = null, $from = null)
    {
        throw new RuntimeException('WebXml翻译服务暂时不提供文章翻译功能！');
    }

    /**
     * 句子翻译
     * @param string $content 要翻译的内容
     * @param string $to      TO语言
     * @param string $from    FROM语言
     * @return string
     */
    public function sentence($content, $to = null, $from = null)
    {
        throw new RuntimeException('WebXml翻译服务暂时不提供整句翻译功能！');
    }

    /**
     * 单词翻译
     * @param string $content 要翻译的内容
     * @param string $to      TO语言
     * @param string $from    FROM语言
     * @return string
     */
    public function word($content, $to = null, $from = null)
    {
        $url = 'http://fy.webxml.com.cn/webservices/EnglishChinese.asmx/Translator';
        $data = [
            'wordKey' => $content
        ];
        $content = Http::post($url, $data);
        $doc = new DOMDocument();
        $doc->loadXML($content);
        $items = $doc->getElementsByTagName("Translation");
        if (!$items) {
            throw new RuntimeException('使用WebXml翻译服务时发生错误！');
        }
        $strs = explode('；', $items->item(0)->nodeValue);
        return $strs[0];
    }
}
