<?php

namespace fize\provider\translate;

/**
 * 翻译
 */
class Translate
{

    /**
     * @var TranslateHandler 接口处理器
     */
    protected static $handler;

    public function getLanguage()
    {
        return [
            'zh', 'en'
        ];
    }

    /**
     * 取得单例
     * @param string $handler 使用的实际接口名称
     * @param array $config 配置项
     * @return TranslateHandler
     */
    public static function getInstance($handler, array $config = [])
    {
        if (empty(self::$handler)) {
            $class = '\\' . __NAMESPACE__ . '\\handler\\' . $handler;
            self::$handler = new $class($config);
        }
        return self::$handler;
    }
}
