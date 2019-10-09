<?php
/**
 * 翻译统一接口
 */

namespace fize\tool\translate;


interface TranslateHandler
{
    /**
     * 文章翻译
     * 如果是XML、HTML，则只翻译字符节点
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public function article($content, $from = null, $to = null);

    /**
     * 句子翻译
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public function sentence($content, $from = null, $to = null);

    /**
     * 单词翻译
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public function word($content, $from = null, $to = null);
}