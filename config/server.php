<?php
/*
 * @Author: 18855190718 1491579574@qq.com
 * @Date: 2023-05-08 15:11:56
 * @LastEditors: 18855190718 1491579574@qq.com
 * @LastEditTime: 2023-05-08 15:18:34
 * @FilePath: \webman\config\server.php
 * @Description: Email:1491579574@qq.com
 * QQ:1491579574
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

return [
    'listen' => 'http://0.0.0.0:28787',
    'transport' => 'tcp',
    'context' => [],
    'name' => 'ChatGPT',
    'count' => cpu_count() * 4,
    'user' => '',
    'group' => '',
    'reusePort' => false,
    'event_loop' => '',
    'stop_timeout' => 2,
    'pid_file' => runtime_path() . '/ChatGPT.pid',
    'status_file' => runtime_path() . '/ChatGPT.status',
    'stdout_file' => runtime_path() . '/logs/stdout.log',
    'log_file' => runtime_path() . '/logs/ChatGPT.log',
    'max_package_size' => 10 * 1024 * 1024
];