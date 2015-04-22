<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-12-5
 * Time: 13:21
 */
class IndexModel extends BaseModel
{
    public function getMainFrameData()
    {
        $result['wait_orderNum'] = $this->tableOrders()->where('status = 1')->count();  //待发货的订单数
        $result['no_confirmNum'] = $this->tableOrders()->where('status = 2')->count(); //未确认的订单数
        $result['wait_payNum'] = $this->tableOrders()->where('status = 0')->count();  //待支付数
        $result['successNum'] = $this->tableOrders()->where('status = 3')->count();  //成交的数
        $result['goodsNum'] = $this->tableGoods()->sum('inventory');  //商品总数
        $result['info'] = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
        );
        return $result;
    }

    public function deleteCache()
    {

    }
}