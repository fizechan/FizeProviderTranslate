<?php

namespace fize\provider\translate;

/**
 * 静态类
 */
class Translate
{

    /**
     * @var TranslateHandler 接口处理器
     */
    protected static $handler;

    /**
     * 常规调用请先初始化
     * @param string $handler 使用的实际接口名称
     * @param array  $config  配置项
     */
    public function __construct($handler, array $config = [])
    {
        self::$handler = TranslateFactory::create($handler, $config);
    }

    /**
     * 文章翻译
     * 如果是XML、HTML，则只翻译字符节点
     * @param string $content 要翻译的内容
     * @param string $to      TO语言
     * @param string $from    FROM语言
     * @return string
     */
    public static function article($content, $to = null, $from = null)
    {
        return self::$handler->article($content, $to, $from);
    }

    /**
     * 句子翻译
     * @param string $content 要翻译的内容
     * @param string $to      TO语言
     * @param string $from    FROM语言
     * @return string
     */
    public static function sentence($content, $to = null, $from = null)
    {
        return self::$handler->sentence($content, $to, $from);
    }

    /**
     * 单词翻译
     * @param string $content 要翻译的内容
     * @param string $to      TO语言
     * @param string $from    FROM语言
     * @return string
     */
    public static function word($content, $to = null, $from = null)
    {
        return self::$handler->word($content, $to, $from);
    }
}
