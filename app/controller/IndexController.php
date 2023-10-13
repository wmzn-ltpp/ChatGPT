<?php
/*
 * @Author: SQS 1491579574@qq.com
 * @Date: 2023-05-08 15:11:56
 * @LastEditors: 18855190718 1491579574@qq.com
 * @LastEditTime: 2023-10-13 21:29:27
 * @FilePath: \ChatGPT\app\controller\IndexController.php
 * @Description: Email:1491579574@qq.com
 * QQ:1491579574
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */

namespace app\controller;

use support\Request;

class IndexController
{
    public function index(Request $request)
    {
        $messages = $request->post('messages');
        if (!$messages) {
            return response('');
        }
        $api_key = '';
        $url = 'https://api.openai.com/v1/chat/completions';
        $data = json_encode([
            'model' => "gpt-3.5-turbo",
            'messages' => $messages,
            'temperature' => 0.9,
            'stream' => false,
        ]);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result, true);
        if (isset($result['choices']) && sizeof($result['choices']) > 0 && isset($result['choices'][0]['message']) && isset($result['choices'][0]['message']['content'])) {
            return response($result['choices'][0]['message']['content']);
        }
        return response('');
    }
}