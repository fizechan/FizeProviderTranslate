<?php
/**
 * 有道翻译，收费，有100元体验券
 */

namespace fize\tool\translate\handler;


use fize\net\Http;
use Exception;
use fize\tool\translate\TranslateHandler;


class YouDao implements TranslateHandler
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
     * @var Http
     */
    private $http;

    /**
     * @var string
     */
    private $appKey;

    /**
     * @var string
     */
    private $secKey;

    /**
     * YouDao constructor.
     * @param array $options ['appkey' => string, 'seckey' => string]
     */
    public function __construct(array $options)
    {
        $this->appKey = $options['appkey'];
        $this->secKey = $options['seckey'];
        $this->http = new Http();
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
        $ret = strtoupper(md5($str));
        return $ret;
    }

    /**
     * 文章翻译
     * 如果是XML、HTML，则只翻译字符节点
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public function article($content, $from = null, $to = null)
    {
        $cur_encoding = mb_detect_encoding($content) ;
        if($cur_encoding == "UTF-8" && mb_check_encoding($content, "UTF-8")){
            //nothing
        }else{
            $content = utf8_encode($content);
        }
        if(is_null($from)){
            $from = 'auto';
        }
        if(is_null($to)){
            $to = 'auto';
        }
        $salt = rand(10000, 99999);
        $sign = $this->buildSign($content, $this->appKey, $salt, $this->secKey);
        $data = [
            //'q' => rawurlencode($content),
            'q' => $content,
            'from' => $from,
            'to' => $to,
            'appKey' => $this->appKey,
            'salt' =>  $salt,
            'sign' => $sign
        ];
        //var_dump($data);
        $response = $this->http->post(self::URL, $data);

        if ($response === false) {
            throw new Exception("发送POST请求时发生错误", 100001);
        }

        $json = json_decode($response, true);

        //var_dump($json);

        if(!isset($json['translation'])){
            throw new Exception(self::$errMsgs[(string)$json['errorCode']], $json['errorCode']);
        }

        return $json['translation'][0];
    }

    /**
     * 句子翻译
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public function sentence($content, $from = null, $to = null)
    {
        return $this->article($content, $from, $to);
    }

    /**
     * 单词翻译
     * @param string $content 要翻译的内容
     * @param string $from FROM语言
     * @param string $to TO语言
     * @return string
     */
    public function word($content, $from = null, $to = null)
    {
        return $this->article($content, $from, $to);
    }
}