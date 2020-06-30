<?php

namespace fize\provider\translate\handler;

use RuntimeException;
use fize\crypt\Json;
use fize\crypt\Utf8;
use fize\misc\MbString;
use fize\net\Http;
use fize\provider\translate\TranslateHandler;

/**
 * 百度翻译
 *
 * 可以使用定制化接口来提高翻译准确度,但是由于申请需要提供大量词库，不太现实。
 * 目前使用通用API，效果一般
 */
class BaiDu extends TranslateHandler
{

    const URL_PRIVATE = 'http://api.fanyi.baidu.com/api/trans/private/translate';

    const URL = 'http://api.fanyi.baidu.com/api/trans/vip/translate';

    /**
     * @var string APPID
     */
    private $appId;

    /**
     * @var string 密钥
     */
    private $secKey;

    /**
     * 构造
     *
     * 参数 `$config`:
     *   ['appid' => string, 'seckey' => string]
     * @param array $config 配置
     */
    public function __construct(array $config = null)
    {
        parent::__construct($config);
        $this->appId = $config['appid'];
        $this->secKey = $config['seckey'];
    }

    /**
     * 签名
     * @param string $query 字符串
     * @param string $appID APPID
     * @param int $salt 随机数
     * @param string $secKey 密钥
     * @return string
     */
    private function buildSign($query, $appID, $salt, $secKey)
    {
        $str = $appID . $query . $salt . $secKey;
        return md5($str);
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
            $to = 'zh';
        }
        $salt = rand(10000, 99999);
        $sign = $this->buildSign($content, $this->appId, $salt, $this->secKey);
        $data = [
            'q'     => $content,
            'from'  => $from,
            'to'    => $to,
            'appid' => $this->appId,
            'salt'  => $salt,
            'sign'  => $sign
        ];
        $response = Http::post(self::URL, $data, true, ['Content-Type' => 'application/x-www-form-urlencoded']);

        $json = Json::decode($response);
        if (!isset($json['trans_result'])) {
            throw new RuntimeException($json['error_msg'], (int)$json['error_code']);
        }

        return $json['trans_result'][0]['dst'];
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
}
