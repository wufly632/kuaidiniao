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
    protected $logisticCode;//快递公司编码
    protected $shipperCode; //物流单号
    protected $requestType; //请求指令类型 1002-及时查询Api 2002-单号识别Api
    protected $config;

    private $ReqURL = 'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx'; //即时接口地址
    private $OrderURL = 'http://api.kdniao.com/api/dist'; //即时接口地址

    public function __construct($RequestType=1002) {
        $this->requestType = $RequestType;
        $this->config = config('kuaidiniao');
    }

    /**
     * Json方式 查询订单物流轨迹
     */
    public function getOrderTracesByJson($ShipperCode, $LogisticCode){
        $requestData  = "{'OrderCode':'','ShipperCode':'".$ShipperCode."','LogisticCode':'".$LogisticCode."'}";
        $datas = array(
            'EBusinessID' => $this->config['ebusinessid'],
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        // print_r($datas);die;
        $datas['DataSign'] = $this->encrypt($requestData, $this->config['appkey']);
        $result=$this->sendPost($this->ReqURL, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     * Json方式  物流信息订阅
     */
    public function orderTracesSubByJson($ShipperCode, $LogisticCode){
        $requestData = "{'OrderCode':'','ShipperCode':'".$ShipperCode."','LogisticCode':'".$LogisticCode."'}";
        $datas = array(
            'EBusinessID' => $this->config['ebusinessid'],
            'RequestType' => '1008',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->config['appkey']);
        $result=$this->sendPost($this->OrderURL, $datas);

        //根据公司业务处理返回的信息......

        return $result;
    }

    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    private function sendPost($url, $datas)
    {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if (empty($url_info['port'])) {
            $url_info['port'] = 80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while ( ! feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while ( ! feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    private function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

    /**
     * 根据快递公司编码获取快递公司名称
     */
    public function kdNiaoName($ShipperCode)
    {
        if ( ! $ShipperCode) return '';

        $data = array(
            'AJ' => '安捷快递',
            'ANE' => '安能物流',
            'AXD' => '安信达快递',
            'BQXHM' => '北青小红帽',
            'BFDF' => '百福东方',
            'BTWL' => '百世快运',
            'CCES' => 'CCES快递',
            'CITY100' => '城市100',
            'COE' => 'COE东方快递',
            'CSCY' => '长沙创一',
            'CDSTKY' => '成都善途速运',
            'DBL' => '德邦',
            'DSWL' => 'D速物流',
            'DTWL' => '大田物流',
            'EMS' => 'EMS',
            'FAST' => '快捷速递',
            'FEDEX' => 'FEDEX联邦(国内件）',
            'FEDEX_GJ' => 'FEDEX联邦(国际件）',
            'FKD' => '飞康达',
            'GDEMS' => '广东邮政',
            'GSD' => '共速达',
            'GTO' => '国通快递',
            'GTSD' => '高铁速递',
            'HFWL' => '汇丰物流',
            'HHTT' => '天天快递',
            'HLWL' => '恒路物流',
            'HOAU' => '天地华宇',
            'hq568' => '华强物流',
            'HTKY' => '百世快递',
            'HXLWL' => '华夏龙物流',
            'HYLSD' => '好来运快递',
            'JGSD' => '京广速递',
            'JIUYE' => '九曳供应链',
            'JJKY' => '佳吉快运',
            'JLDT' => '嘉里物流',
            'JTKD' => '捷特快递',
            'JXD' => '急先达',
            'JYKD' => '晋越快递',
            'JYM' => '加运美',
            'JYWL' => '佳怡物流',
            'KYWL' => '跨越物流',
            'LB' => '龙邦快递',
            'LHT' => '联昊通速递',
            'MHKD' => '民航快递',
            'MLWL' => '明亮物流',
            'NEDA' => '能达速递',
            'PADTF' => '平安达腾飞快递',
            'QCKD' => '全晨快递',
            'QFKD' => '全峰快递',
            'QRT' => '全日通快递',
            'RFD' => '如风达',
            'SAD' => '赛澳递',
            'SAWL' => '圣安物流',
            'SBWL' => '盛邦物流',
            'SDWL' => '上大物流',
            'SF' => '顺丰快递',
            'SFWL' => '盛丰物流',
            'SHWL' => '盛辉物流',
            'ST' => '速通物流',
            'STO' => '申通快递',
            'STWL' => '速腾快递',
            'SURE' => '速尔快递',
            'TSSTO' => '唐山申通',
            'UAPEX' => '全一快递',
            'UC' => '优速快递',
            'WJWL' => '万家物流',
            'WXWL' => '万象物流',
            'XBWL' => '新邦物流',
            'XFEX' => '信丰快递',
            'XYT' => '希优特',
            'XJ' => '新杰物流',
            'YADEX' => '源安达快递',
            'YCWL' => '远成物流',
            'YD' => '韵达快递',
            'YDH' => '义达国际物流',
            'YFEX' => '越丰物流',
            'YFHEX' => '原飞航物流',
            'YFSD' => '亚风快递',
            'YTKD' => '运通快递',
            'YTO' => '圆通速递',
            'YXKD' => '亿翔快递',
            'YZPY' => '邮政平邮/小包',
            'ZENY' => '增益快递',
            'ZHQKD' => '汇强快递',
            'ZJS' => '宅急送',
            'ZTE' => '众通快递',
            'ZTKY' => '中铁快运',
            'ZTO' => '中通速递',
            'ZTWL' => '中铁物流',
            'ZYWL' => '中邮物流',
            'AMAZON' => '亚马逊物流',
            'SUBIDA' => '速必达物流',
            'RFEX' => '瑞丰速递',
            'QUICK' => '快客快递',
            'CJKD' => '城际快递',
            'CNPEX' => 'CNPEX中邮快递',
            'HOTSCM' => '鸿桥供应链',
            'HPTEX' => '海派通物流公司',
            'AYCA' => '澳邮专线',
            'PANEX' => '泛捷快递',
            'PCA' => 'PCA Express',
            'UEQ' => 'UEQ Express'
        );

        return $data[$ShipperCode];
    }


}
