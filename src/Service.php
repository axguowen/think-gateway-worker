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

namespace think\gateway\worker;

class Service extends \think\Service
{
    /**
     * 注册服务
     * @access public
     * @return void
     */
    public function register()
    {
        // 设置命令
        $this->commands([
            'gateway:worker' => Command::class,
        ]);
    }
}
