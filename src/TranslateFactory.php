<?php

namespace fize\provider\translate;

/**
 * 翻译工厂
 */
class TranslateFactory
{

    /**
     * 创建一个实例
     * @param string $handler 使用的实际接口名称
     * @param array  $config  配置
     * @return TranslateHandler
     */
    public static function create($handler, array $config = [])
    {
        $class = '\\' . __NAMESPACE__ . '\\handler\\' . $handler;
        return new $class($config);
    }
}
