<?php

// 设置脚本不超时
set_time_limit(0);

$url      = get('url');
$response = $url ? analyze($url, get('requests'), get('concurrency')) : [];

header('Content-Type: application/json; charset=utf-8');
exit(json_encode(array_merge(['name'=>get('name')], $response)));

/**
 * 分析
 *
 * @param   string  $url            URL
 * @param   int     $requests       总共请求次数
 * @param   int     $concurrency    并发数
 * @return  array
 */
function analyze($url, $requests = 1, $concurrency = 1)
{
    exec("ab -n {$requests} -c {$concurrency} {$url} 2>&1", $output);
    $output = implode("\n", $output);

    $keys = [

        // 目标服务器Apache版本号
        'Server Software'                       => 'server_software',

        // 目标网页大小 (单位：字节)
        'Document Length'                       => 'size',

        // 并发线程数
        'Concurrency Level'                     => 'concurrency',

        // 使用时间 (单位：秒)
        'Time taken for tests'                  => 'taken_time',

        // 成功请求的数量
        'Complete requests'                     => 'complete_requests',

        // 失败请求的数量
        'Failed requests'                       => 'failed_requests',

        // 全部使用的流量
        'Total transferred'                     => 'total_transferred',

        // HTML 文件使用的流量
        'HTML transferred'                      => 'html_transferred',

        // 指标一 平均每秒请求数
        'Requests per second'                   => 'per_requests_second',

        // 指标二 平均事务响应时间 (单位：毫秒)
        'Time per request'                      => 'per_requests_time_avg',

        // 每秒请求时间  per_requests_time

        // 传输速率
        'Transfer rate'                         => 'transfer_rate',

        // 每个请求响应时间 (单位：毫秒) 开启并发时有效
        '50%'                                   => 'requests_50_ms',
        '66%'                                   => 'requests_66_ms',
        '75%'                                   => 'requests_75_ms',
        '80%'                                   => 'requests_80_ms',
        '90%'                                   => 'requests_90_ms',
        '95%'                                   => 'requests_95_ms',
        '98%'                                   => 'requests_98_ms',
        '99%'                                   => 'requests_99_ms',
        '100%'                                  => 'requests_100_ms',
    ];

    preg_match_all('/('.implode('|', array_keys($keys)).'):?\s+(.*?)(?:\n|\s+)/i', $output, $matches, PREG_SET_ORDER);
    if(empty($matches))
        return array();

    $info = array();
    foreach($matches as $match){

        if(strtolower($match[1]) == 'time per request' && isset($info[$keys[$match[1]]])){
            $info['per_requests_time'] = $match[2];
        }else{
            $info[$keys[$match[1]]] = $match[2];
        }
    }

    return $info;
}

/**
 * 获取 $_GET 中的值
 *
 * @param string $key       键名
 * @param mixed  $default   默认值
 * @return mixed
 */
function get($key = null, $default = null){

    if(is_null($key))
        return $_GET;

    return isset($_GET[$key])?$_GET[$key]:$default;
}