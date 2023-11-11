<?php
/*
 * @Author: SQS 1491579574@qq.com
 * @Date: 2023-05-08 15:11:56
 * @LastEditors: wmzn-ltpp 1491579574@qq.com
 * @LastEditTime: 2023-11-12 00:58:38
 * @FilePath: \ChatGPT\app\controller\IndexController.php
 * @Description: Email:1491579574@qq.com
 * QQ:1491579574
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */

namespace app\controller;

use Exception;
use support\Request;

class IndexController
{
    /**
     * LTPP文件夹绝对路径
     * @var string $LTPP_path LTPP文件夹绝对路径
     */
    static $LTPP_path = '/home/LTPP/';

    /**
     * ChatGPT 信息路径
     */
    static $chat_gpt_file_name = 'ltpp_chat_gpt.json';

    /**
     * GPT Key在Redis中Key的名称
     */
    static $redis_chatgpt_json_key = 'chatgpt_json_key';

    /**
     * GPT API地址在Redis中Key的名称
     */
    static $chat_gpt_api_url_key = 'chatgpt_api_url';

    /**
     * 判断路径是否存在（路径以/开头），不存在创建路径中的文件夹
     * @param string $path 路径
     * @param int $grade 权限
     */
    static public function judgeCreatPath($path, $grade = 0666)
    {
        if (file_exists($path)) {
            return;
        }
        $name = [];
        $length = strlen($path);
        // 获取全部名称
        for ($i = 0; $i < $length; ++$i) {
            if ($path[$i] == '/') {
                $tem = '';
                for ($j = $i + 1; $j < $length; ++$j) {
                    if ($path[$j] == '/') {
                        $i = $j - 1;
                        break;
                    }
                    $tem .= $path[$j];
                    if ($j == $length - 1) {
                        $i = $j;
                        break;
                    }
                }
                if ($tem != '') {
                    $name[] = $tem;
                }
            }
        }
        $now_path = '/';
        foreach ($name as &$tem) {
            $now_path .= $tem . '/';
            $isfile = strripos($now_path, '.');
            if (!file_exists($now_path) && $isfile === false && !is_dir($now_path)) {
                try {
                    @mkdir($now_path, $grade, true);
                } catch (Exception $e) {
                    continue;
                }
            }
        }
    }


    /**
     * 写入文件
     * @param string $file 文件路径
     * @param string $content 写入的内容
     */
    static public function writeToFile($file, $content = '')
    {
        IndexController::judgeCreatPath($file);
        while (1) {
            try {
                $result = file_put_contents($file, $content);
                if ($result !== false) {
                    // 写入成功
                    return;
                }
            } catch (Exception $e) {
                continue;
            }
        }
    }

    /**
     * 获取GPT JSON对应配置
     * @param string $key 读取key名称
     * @return string|array $api_url|$key_list
     */
    static public function getChatGptJSON($key)
    {
        try {
            if ($key != IndexController::$chat_gpt_api_url_key && $key != IndexController::$redis_chatgpt_json_key) {
                return '';
            }
            // 存放路径
            $path = IndexController::$LTPP_path . IndexController::$chat_gpt_file_name;
            if (!file_exists($path)) {
                IndexController::writeToFile($path, '{"' . IndexController::$chat_gpt_api_url_key . '":"","' . IndexController::$redis_chatgpt_json_key . '":[]}');
                return [
                    IndexController::$chat_gpt_api_url_key => '',
                    IndexController::$redis_chatgpt_json_key => [],
                ][$key];
            }
            // 读取Key数组
            $json_str = file_get_contents($path);
            $json = json_decode($json_str, true);
            return $json[$key];
        } catch (Exception $e) {
        }
        return [
            IndexController::$chat_gpt_api_url_key => '',
            IndexController::$redis_chatgpt_json_key => [],
        ][$key];
    }

    /**
     * 获取GPT KEY LIST
     * @return array key_list
     */
    static public function getChatGptKeyList()
    {
        try {
            $key_list = IndexController::getChatGptJSON(IndexController::$redis_chatgpt_json_key);
            if (!$key_list) {
                return [];
            }
            return $key_list;
        } catch (Exception $e) {
        }
        return [];
    }

    /**
     * 获取GPT接口地址
     */
    static public function getChatGptUrl()
    {
        try {
            $api_url = IndexController::getChatGptJSON(IndexController::$chat_gpt_api_url_key);
            if (!$api_url) {
                return '';
            }
            return $api_url;
        } catch (Exception $e) {
        }
        return '';
    }

    /**
     * 发送请求
     * @param string $url 请求地址
     * @param array $header 请求头
     * @param array $body 请求体
     * @param bool $body_type_is_json 请求体是否是json
     * @return string $res 响应数据
     */
    static function sendRequest($url = '', $header = [], $body = [], $body_type_is_json = false)
    {
        if (!$url) {
            return '';
        }
        $res = '';
        try {
            if ($body_type_is_json) {
                $data_string = http_build_query($body);
            } else {
                $data_string = json_encode($body);
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_PROXY, 'http://172.17.0.1:7890');
            $res = curl_exec($ch);
        } catch (Exception $e) {
        }
        return $res;
    }

    /**
     * 发请求
     */
    public function index(Request $request)
    {
        try {
            $messages = $request->post('messages');
            if (!$messages) {
                return response('');
            }
            $api_key = '';
            $gpt_api_url = IndexController::getChatGptUrl();
            $data = [
                'model' => "gpt-3.5-turbo",
                'messages' => $messages,
                'temperature' => 0.9,
                'stream' => false,
            ];
            $key_list = IndexController::getChatGptKeyList();
            foreach ($key_list as &$api_key) {
                $headers = [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $api_key
                ];
                $result = IndexController::sendRequest($gpt_api_url, $headers, $data, true);
                $result = json_decode($result, true);
                if (isset($result['choices']) && sizeof($result['choices']) > 0 && isset($result['choices'][0]['message']) && isset($result['choices'][0]['message']['content'])) {
                    return response($result['choices'][0]['message']['content']);
                }
            }
            return response('');
        } catch (Exception $e) {
            return response('');
        }
    }
}