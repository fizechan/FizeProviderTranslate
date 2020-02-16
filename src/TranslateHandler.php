<?php

namespace fize\provider\translate;

/**
 * 接口：翻译
 */
abstract class TranslateHandler
{

    /**
     * @var array 配置
     */
    protected $config;

    /**
     *  构造
     * @param array $config 配置
     */
    public function __construct(array $config = null)
    {
        $this->config = $config;
    }

    /**
     * 文章翻译
     * 如果是XML、HTML，则只翻译字符节点
     * @param string $content 要翻译的内容
     * @param string $to TO语言
     * @param string $from FROM语言
     * @return string
     */
    abstract public function article($content, $to = null, $from = null);

    /**
     * 句子翻译
     * @param string $content 要翻译的内容
     * @param string $to TO语言
     * @param string $from FROM语言
     * @return string
     */
    abstract public function sentence($content, $to = null, $from = null);

    /**
     * 单词翻译
     * @param string $content 要翻译的内容
     * @param string $to TO语言
     * @param string $from FROM语言
     * @return string
     */
    abstract public function word($content, $to = null, $from = null);
}
