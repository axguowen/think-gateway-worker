<?php
// +----------------------------------------------------------------------
// | ThinkPHP Gateway Worker [Gateway Worker Service For ThinkPHP]
// +----------------------------------------------------------------------
// | ThinkPHP Gateway Worker 服务
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: axguowen <axguowen@qq.com>
// +----------------------------------------------------------------------

namespace think;

// 命令行入口文件
// 加载基础文件
require __DIR__ . '/../../../autoload.php';

// 如果命令不是 gateway:worker 则退出
if ($argc < 2 || $argv[1] != 'gateway:worker') {
    exit('Not Support Command: ' . $argv[1] . PHP_EOL);
}
// 应用初始化
(new App())->console->run();
