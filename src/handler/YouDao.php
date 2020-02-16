<?php

namespace fize\provider\translate\handler;

use RuntimeException;
use fize\misc\MbString;
use fize\crypt\Utf8;
use fize\net\Http;
use fize\crypt\Json;
use fize\provider\translate\TranslateHandler;

/**
 * 有道翻译
 *
 * 收费，有100元体验券
 */
class YouDao extends TranslateHandler
{
    const URL = 'http://openapi.youdao.com/api';

    /**
     * @var array 错误信息
     */
    private static $errMsgs = [
        '101' => '缺少必填的参数，出现这个情况还可能是et的值和实际加密方式不对应',
        '102' => '不支持的语言类型',
        '103' => '翻译文本过长',
        '104' => '不支持的API类型',
        '105' => '不支持的签名类型',
        '106' => '不支持的响应类型',
        '107' => '不支持的传输加密类型',
        '108' => 'appKey无效',
        '109' => 'batchLog格式不正确',
        '110' => '无相关服务的有效实例',
        '111' => '开发者账号无效',
        '113' => 'q不能为空',
        '201' => '解密失败，可能为DES,BASE64,URLDecode的错误',
        '202' => '签名检验失败',
        '203' => '访问IP地址不在可访问IP列表',
        '301' => '辞典查询失败',
        '302' => '翻译查询失败',
        '303' => '服务端的其它异常',
        '401' => '账户已经欠费',
        '411' => '访问频率受限,请稍后访问',
        '412' => '长请求过于频繁，请稍后访问'
    ];

    /**
     * @var string APPKEY
     */
    private $appKey;

    /**
     * @var string 密钥
     */
    private $secKey;

    /**
     * 构造
     *
     * 参数 `$config`:
     *   ['appkey' => string, 'seckey' => string]
     * @param array $config
     */
    public function __construct(array $config = null)
    {
        parent::__construct($config);
        $this->appKey = $config['appkey'];
        $this->secKey = $config['seckey'];
    }

    /**
     * 文章翻译
     * 如果是XML、HTML，则只翻译字符节点
     * @param string $content 要翻译的内容
     * @param string $to TO语言
     * @param string $from FROM语言
     * @return string
     */
    public function article($content, $to = null, $from = null)
    {
        $cur_encoding = MbString::detectEncoding($content);
        if ($cur_encoding == "UTF-8" && MbString::checkEncoding($content, "UTF-8") == false) {
            $content = Utf8::encode($content);
        }

        if (is_null($from)) {
            $from = 'auto';
        }
        if (is_null($to)) {
            $to = 'auto';
        }
        $from = self::languageMap($from);
        $to = self::languageMap($to);

        $salt = rand(10000, 99999);
        $sign = $this->buildSign($content, $this->appKey, $salt, $this->secKey);

        $data = [
            'q'      => $content,
            'from'   => $from,
            'to'     => $to,
            'appKey' => $this->appKey,
            'salt'   => $salt,
            'sign'   => $sign
        ];
        $response = Http::post(self::URL, $data);

        if ($response === false) {
            throw new RuntimeException(Http::getLastErrMsg(), Http::getLastErrCode());
        }

        $json = Json::decode($response);
        if (!isset($json['translation'])) {
            throw new RuntimeException(self::$errMsgs[(string)$json['errorCode']], $json['errorCode']);
        }

        return $json['translation'][0];
    }

    /**
     * 句子翻译
     * @param string $content 要翻译的内容
     * @param string $to TO语言
     * @param string $from FROM语言
     * @return string
     */
    public function sentence($content, $to = null, $from = null)
    {
        return $this->article($content, $to, $from);
    }

    /**
     * 单词翻译
     * @param string $content 要翻译的内容
     * @param string $to TO语言
     * @param string $from FROM语言
     * @return string
     */
    public function word($content, $to = null, $from = null)
    {
        return $this->article($content, $to, $from);
    }

    /**
     * 签名
     * @param string $query 字符串
     * @param string $appKey APPID
     * @param int $salt 随机数
     * @param string $secKey 密钥
     * @return string
     */
    private function buildSign($query, $appKey, $salt, $secKey)
    {
        $str = $appKey . $query . $salt . $secKey;
        return strtoupper(md5($str));
    }

    /**
     * 统一化语言标识
     * @param string $lang 语言标识
     * @return string
     */
    private static function languageMap($lang)
    {
        $maps = [
            'zh-CHS' => 'zh',
            'EN'     => 'en'
        ];
        if (isset($maps[$lang])) {
            return $maps[$lang];
        }
        return $lang;
    }
}
