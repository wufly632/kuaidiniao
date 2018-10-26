<?php
// +----------------------------------------------------------------------
// | KuaidiniaoService.php
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------
// | Time: 2018/10/26 下午9:00
// +----------------------------------------------------------------------
// | Author: wufly <wfxykzd@163.com>
// +----------------------------------------------------------------------

namespace Wufly\Kuaidiniao;

class KuaidiniaoService
{
    private $config;
    public function __construct($config) {
        $this->config = $config;
    }

    public function test() {
        dd($this->config);
    }
}
