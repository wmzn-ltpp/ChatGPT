<?php
/*
 * @Author: 18855190718 1491579574@qq.com
 * @Date: 2023-02-28 22:41:18
 * @LastEditors: SQS 1491579574@qq.com
 * @LastEditTime: 2023-05-08 17:08:20
 * @FilePath: \ChatGPT\config\plugin\webman\console\app.php
 * @Description: Email:1491579574@qq.com
 * QQ:1491579574
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */
/*
 * @Author: 18855190718 1491579574@qq.com
 * @Date: 2023-02-28 22:41:18
 * @LastEditors: 18855190718 1491579574@qq.com
 * @LastEditTime: 2023-03-06 23:01:18
 * @FilePath: \API\config\plugin\webman\console\app.php
 * @Description: Email:1491579574@qq.com
 * QQ:1491579574
 * Copyright (c) 2023 by ${git_name_email}, All Rights Reserved. 
 */
return [
    'enable' => true,

    'phar_file_output_dir' => BASE_PATH . DIRECTORY_SEPARATOR . 'build',

    'phar_filename' => 'ChatGPT.phar',

    'bin_filename' => 'ChatGPT.bin',

    'signature_algorithm' => Phar::SHA256,
    //set the signature algorithm for a phar and apply it. The signature algorithm must be one of Phar::MD5, Phar::SHA1, Phar::SHA256, Phar::SHA512, or Phar::OPENSSL.

    'private_key_file' => '',
    // The file path for certificate or OpenSSL private key file.

    'exclude_pattern' => '#^(?!.*(composer.json|/.github/|/.idea/|/.git/|/.setting/|/runtime/|/vendor-bin/|/build/|/Music/|/Frontend/|/py/|/InstallMust/|/sh/|/public/|/.vscode|/.gitignore|/Dockerfile|/README.md|vendor/webman/admin))(.*)$#',

    'exclude_files' => [
        '.env',
        'LICENSE',
        'composer.json',
        'composer.lock',
        'start.php'
    ]
];