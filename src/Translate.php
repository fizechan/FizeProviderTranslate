<?php
/**
 * 翻译统一接口
 */

namespace fize\tool\translate;


class Translate
{
    /**
     * @var TranslateHandler
     */
    private static $handler;

    /**
     * 禁止构造
     */
    private function __construct()
    {
    }

    /**
     * 初始化
     * @param string $handler 处理句柄方式
     * @param array $options 配置项
     */
    public static function init($handler, array $options = [])
    {
        $class = '\\fize\\translate\\handler\\' . ucfirst($handler);
        self::$handler = new $class($options);
    }

    /**
     * 文章翻译
     * 如果是XML、HTML，则只翻译字符节点
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public static function article($content, $from = null, $to = null)
    {
        return self::$handler->article($content, $from, $to);
    }

    /**
     * 句子翻译
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public static function sentence($content, $from = null, $to = null)
    {
        return self::$handler->sentence($content, $from, $to);
    }

    /**
     * 单词翻译
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public static function word($content, $from = null, $to = null)
    {
        return self::$handler->word($content, $from, $to);
    }
}