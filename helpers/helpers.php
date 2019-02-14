<?php
//                              _(\_/)
//                             ,((((^`\         //+--------------------------------------------------------------
//                            ((((  (6 \        //|Copyright (c) 2017 http://www.shuwon.com All rights reserved.
//                          ,((((( ,    \       //+--------------------------------------------------------------
//      ,,,_              ,(((((  /"._  ,`,     //|Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
//     ((((\\ ,...       ,((((   /    `-.-'     //+--------------------------------------------------------------
//     )))  ;'    `"'"'""((((   (               //|Author: xww < xww@wyw1.cn >
//    (((  /            (((      \              //+--------------------------------------------------------------
//     )) |                      |
//    ((  |        .       '     |
//    ))  \     _ '      `t   ,.')
//    (   |   y;- -,-""'"-.\   \/
//    )   / ./  ) /         `\  \
//       |./   ( (           / /'                __     __ 
//       ||     \\          //'|                /  \~~~/  \
//       ||      \\       _//'||          ,----(     ..    ) 
//       ||       ))     |_/  ||         /      \__     __/   
//       \_\     |_/          ||       /|         (\  |(
//       `'"                  \_\     ^ \   /___\  /\ |  
//                            `'"        |__|   |__|-" 
if (!function_exists('dump')) {
    function dump($data)
    {
        echo "<pre>";
        var_dump($data);
    }
}

if (!function_exists('halt')) {
    function halt($data)
    {
        echo "<pre>";
        var_dump($data);
        exit;
    }
}
if (!function_exists('hide_phone')) {
    function hide_phone($str)
    {
        $res = substr_replace($str, '****', 3, 4);
        return $res;
    }
}

if (!function_exists('post_json')) {
    function post_json($url, $json)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);   //只需要设置一个秒的数量就可以
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
if (!function_exists('HttpPost')) {
    function HttpPost($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);   //只需要设置一个秒的数量就可以
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (is_array($post_data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;
    // 密匙
    $key = md5($key ? $key : 'shuwon');
    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) :
        substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
    //解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
        sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if ($operation == 'DECODE') {
        // 验证数据有效性，请看未加密明文的格式
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

//秒数格式
function getTime($ParkingTime)
{
    $ParkingTime = $ParkingTime * 60;
    $hours = floor($ParkingTime / 3600);
    $ParkingTime = ($ParkingTime % 3600);
    $minutes = floor($ParkingTime / 60);
    return $hours . '小时' . $minutes . '分钟';
}

/**
 * 获取配置项
 * @param null $key
 * @param null $defaultValue
 * @return null
 */
function config($key = null, $defaultValue = null)
{
    $params = app()->params;

    return $params[$key] ?? $defaultValue;
}

/**
 * app
 * @return \yii\console\Application|\yii\web\Application
 */
function app()
{
    return Yii::$app;
}

/**
 * user
 * @return mixed|\yii\web\User
 */
function user()
{
    return app()->user;
}

/**
 * userInfo
 * @param null $attribute
 * @return null|\yii\web\IdentityInterface
 */
function userInfo($attribute = null)
{
    $userInfo = user()->identity;
    return $attribute?$userInfo->$attribute:$userInfo;
}

/**
 * request
 * @return \yii\console\Request|\yii\web\Request
 */
function request()
{
    return app()->request;
}

/**
 * get
 * @param null $name
 * @param null $defaultValue
 * @return array|mixed
 */
function get($name = null, $defaultValue = null)
{
    return request()->get($name, $defaultValue);
}

/**
 * post
 * @param null $name
 * @param null $defaultValue
 * @return array|mixed
 */
function post($name = null, $defaultValue = null)
{
    return request()->post($name, $defaultValue);
}

/**
 * response
 * @return \yii\console\Response|\yii\web\Response
 */
function response()
{
    return app()->response;
}

/**
 * view
 * @return \yii\base\View|\yii\web\View
 */
function view()
{
    return app()->view;
}

/**
 * db
 * @return \yii\db\Connection
 */
function db()
{
    return app()->db;
}

/**
 * cache
 * @return \yii\caching\CacheInterface
 */
function cache()
{
    return app()->cache;
}

/**
 * @return mixed|\yii\web\Session
 */
function session()
{
    return app()->session;
}

function cookies()
{
    return response()->cookies;
}

/**
 * 字符串 加密
 * @param string $string
 * @return string
 */
function encryptString(string $string)
{
    return base64_encode(app()->getSecurity()->encryptByPassword($string, config('cryptSecretKey')));
}

/**
 * 字符串 解密
 * @param string $string
 * @return string
 */
function decryptString(string $string)
{
    return app()->getSecurity()->encryptByPassword(base64_decode($string), config('cryptSecretKey'));
}

/**
 * exceptionFormat
 * @param \Exception $exception
 * @return array
 */
function exceptionFormat($exception)
{
    return [
        'code'        => $exception->getCode(),
        'file'        => $exception->getFile(),
        'line'        => $exception->getLine(),
        'message'     => $exception->getMessage(),
        'traceString' => $exception->getTraceAsString(),
        // 'trace'       => $exception->getTrace(),
    ];
}


