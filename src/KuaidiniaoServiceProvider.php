<?php
// +----------------------------------------------------------------------
// | KuaidiniaoServiceProvider.php
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------
// | Time: 2018/10/26 下午9:01
// +----------------------------------------------------------------------
// | Author: wufly <wfxykzd@163.com>
// +----------------------------------------------------------------------

namespace Wufly\Kuaidiniao;

use Illuminate\Support\ServiceProvider;

class KuaidiniaoServiceProvider extends ServiceProvider
{
    protected $defer = true;
    public function boot() {
        $this->publishes([
            __DIR__.'/../config/kuaidiniao.php' => config_path('kuaidiniao.php'),
        ], 'kuaidiniao');
    }
    public function register() {
        
    }
    public function provides() {
        return ['kuaidiniao'];
    }
}
