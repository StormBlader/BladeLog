<?php

/**
 * 创建或获取实例
 */
function getInstance($class_name)
{
	if(!isset($GLOBALS['obj'][$class_name])) {;
		$GLOBALS['obj'][$class_name] = new $class_name();
		return $GLOBALS['obj'][$class_name];
	} else 
		return $GLOBALS['obj'][$class_name];
}

/**
 * 获取客户端IP
 */
function get_client_ip() 
{
	if (getenv ('HTTP_CLIENT_IP') && strcasecmp ( getenv ('HTTP_CLIENT_IP'), 'unknown' ))
		$ip = getenv ( 'HTTP_CLIENT_IP' );
	else if (getenv ('HTTP_X_FORWARDED_FOR') && strcasecmp ( getenv ('HTTP_X_FORWARDED_FOR'), 'unknown'))
		$ip = getenv ('HTTP_X_FORWARDED_FOR');
	else if (getenv ('REMOTE_ADDR') && strcasecmp ( getenv ('REMOTE_ADDR'), 'unknown'))
		$ip = getenv ('REMOTE_ADDR');
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], 'unknown'))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = 'unknown';
	return ($ip);
}
	
/**
 * CURL发送请求
 *
 * @param string $url
 * @param mixed $data
 * @param string $method
 * @param string $cookieFile
 * @param array $headers
 * @param int $connectTimeout
 * @param int $readTimeout
 */
function curlRequest($url, $data='', $method='POST', $cookieFile='', $headers='', $connectTimeout = 30, $readTimeout = 30)
{ 
    $method = strtoupper($method);
    if(!function_exists('curl_init')) return socketRequest($url, $data, $method, $cookieFile, $connectTimeout);

    $option = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => $connectTimeout,
        CURLOPT_TIMEOUT => $readTimeout
    );

    if($headers) $option[CURLOPT_HTTPHEADER] = $headers;

    if($cookieFile)
    {
        $option[CURLOPT_COOKIEJAR] = $cookieFile;
        $option[CURLOPT_COOKIEFILE] = $cookieFile;
    }

    if($data && strtolower($method) == 'post')
    {
        $option[CURLOPT_POST] = 1;
        $option[CURLOPT_POSTFIELDS] = $data;
    }
	
	if(stripos($url, 'https://') !== false)
    {
    	$option[CURLOPT_SSL_VERIFYPEER] = false;
    	$option[CURLOPT_SSL_VERIFYHOST] = false;
    }
    
    $ch = curl_init();
    curl_setopt_array($ch,$option);
    $response = curl_exec($ch);
    if(curl_errno($ch) > 0) throw_exception("CURL ERROR:$url ".curl_error($ch));
    curl_close($ch);
    return $response;
}

