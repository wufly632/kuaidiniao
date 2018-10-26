<?php
// +----------------------------------------------------------------------
// | KuaidiniaoFacade.php
// +----------------------------------------------------------------------
// | Description: 
// +----------------------------------------------------------------------
// | Time: 2018/10/26 下午8:57
// +----------------------------------------------------------------------
// | Author: wufly <wfxykzd@163.com>
// +----------------------------------------------------------------------

namespace Wufly\Kuaidiniao;

use \Illuminate\Support\Facades\Facade;

class KuaidiniaoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'kuaidiniao';
    }
}
