<?php
/**
 * 百度翻译
 * 可以使用定制化接口来提高翻译准确度,但是由于申请需要提供大量词库，不太现实。
 * 目前使用通用API，效果一般
 */

namespace fize\tool\translate\handler;

use fize\net\Http;
use Exception;
use fize\tool\translate\TranslateHandler;


class BaiDu implements TranslateHandler
{
    const URL_PRIVATE = 'http://api.fanyi.baidu.com/api/trans/private/translate';

    const URL = 'http://api.fanyi.baidu.com/api/trans/vip/translate';

    /**
     * @var Http
     */
    private $http;

    /**
     * @var string
     */
    private $appId;

    /**
     * @var string
     */
    private $secKey;

    /**
     * BaiDu constructor.
     * @param array $options ['appid' => string, 'seckey' => string]
     */
    public function __construct(array $options)
    {
        $this->appId = $options['appid'];
        $this->secKey = $options['seckey'];
        $this->http = new Http();
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
        $ret = md5($str);
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
            $from = 'en';
        }
        if(is_null($to)){
            $to = 'zh';
        }
        $salt = rand(10000, 99999);
        $sign = $this->buildSign($content, $this->appId, $salt, $this->secKey);
        $data = [
            //'q' => rawurlencode($content),
            'q' => $content,
            'from' => $from,
            'to' => $to,
            'appid' => $this->appId,
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

        if(!isset($json['trans_result'])){
            throw new Exception($json['error_msg'], $json['error_code']);
        }

        return $json['trans_result'][0]['dst'];
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