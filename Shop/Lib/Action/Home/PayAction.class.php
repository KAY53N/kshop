<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-28
 * Time: 23:45
 */
class PayAction extends CommonAction
{
    protected $payModel;
    public function _initialize()
    {
        vendor('Alipay.Corefunction');
        vendor('Alipay.Service');
        vendor('Alipay.Notify');
        vendor('Alipay.Submit');
        $this->payModel = D('Home.Pay');
    }

    public function aliPayTo()
    {
        $aliPayConfig = C('aliPayConfig');
        $condition['id'] = array('eq', intval($_GET['id']));
        $result = $this->payModel->getOrderInfoData($condition);

        $parameter = array(
            'service'			=> 'create_direct_pay_by_user',
            'payment_type'		=> '1',
            'partner'			=> trim($aliPayConfig['partner']),
            '_input_charset'	=> trim(strtolower($aliPayConfig['input_charset'])),
            'seller_email'		=> trim($aliPayConfig['seller_email']),
            'return_url'		=> trim($aliPayConfig['return_url']),
            'notify_url'		=> trim($aliPayConfig['notify_url']),
            'out_trade_no'		=> $result['order_No'],
            'subject'			=> $result['title_info'],
            'body'				=> $result['remark'],
            'total_fee'			=> $result['price'],
            'paymethod'			=> '',
            'defaultbank'		=> '',
            'anti_phishing_key'	=> '',
            'exter_invoke_ip'	=> '',
            'show_url'			=> 'http://www.xujiantao.com/works/lvsenshop',
            'extra_common_param'=> '',
            'royalty_type'		=> '',
            'royalty_parameters'=> ''
        );

        $aliPayService = new AlipayService($aliPayConfig);
        $html_text = $aliPayService->create_direct_pay_by_user($parameter);
        echo $html_text;
    }

    function notifyUrl()
    {
        $aliPayConfig = C('aliPayConfig');
        $aliPayNotify = new AlipayNotify($aliPayConfig);
        $verify_result = $aliPayNotify->verifyNotify();
        $_POST = $this->zaddslashes($_POST);

        if($verify_result)
        {
            //logResult(var_export($_POST, true));
            //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            $out_trade_no	= $_POST['out_trade_no'];	    //获取订单号
            $trade_no		= $_POST['trade_no'];	    	//获取支付宝交易号
            $total_fee		= $_POST['total_fee'];			//获取总价格

            if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS')
            {
                //交易成功结束
                //判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                echo 'success';		//请不要修改或删除

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            }
            else
            {
                $saveData['status'] = 1;
                $saveData['alipay_No'] = $trade_no;
                $orderCondition['order_No'] = array('eq', $out_trade_no);
    
                $orderInfo = $this->payModel->getOrderData($orderCondition);
                $points = explode('-~-', $orderInfo['points']);
                
                $this->payModel->getSaveUserSumPointsStatus(intval($orderInfo['id']), array_sum($points));
    
                $status = $this->payModel->getSaveOrderDataStatus(intval($orderInfo['id']), $saveData);
                
                //logResult(var_export($_GET, true));
                
                if($status)
                {
                    $this->changeGoodsInventory($orderInfo);
                    echo 'success';
                }
                else
                {
                    echo 'fail';
                }
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else
        {
            //验证失败
            echo 'fail';
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }
    
    //成功支付后调用减去商品库存
    private function changeGoodsInventory($orderInfo)
    {
        $totalPoints = 0;
        $buy_num = explode('-~-', $orderInfo['buy_num']);
        $goods_id = explode('-~-', $orderInfo['goods_id']);
        
        for($i=0; $i<count($goods_id); $i++)
        {
            if(empty($goods_id[$i]))
            {
                unset($goods_id[$i]);
            }
            
            if(empty($buy_num[$i]))
            {
                unset($buy_num[$i]);
            }
            
            $minusGoodsNum = $buy_num[$i];
            $this->payModel->changeGoodsInventory(intval($goods_id[$i]), $minusGoodsNum);
        }
        
    }


    function returnUrl()
    {
        $aliPayConfig = C('aliPayConfig');
        //计算得出通知验证结果
        $aliPayNotify = new AlipayNotify($aliPayConfig);
        $verify_result = $aliPayNotify->verifyReturn();

        if($verify_result)
        {
            //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            $out_trade_no	= $_GET['out_trade_no'];	//获取订单号
            $trade_no		= $_GET['trade_no'];		//获取支付宝交易号
            $total_fee		= $_GET['total_fee'];		//获取总价格

            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS')
            {
                //判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            }
            else
            {
                echo "trade_status=".$_GET['trade_status'];
            }

            $saveData['status'] = 1;
            $saveData['alipay_No'] = $trade_no;
            $orderCondition['order_No'] = array('eq', $out_trade_no);

            $orderInfo = $this->payModel->getOrderData($orderCondition);
            $points = explode('-~-', $orderInfo['points']);
            
            $this->payModel->getSaveUserSumPointsStatus(intval($orderInfo['id']), array_sum($points));

            $status = $this->payModel->getSaveOrderDataStatus(intval($orderInfo['id']), $saveData);
            
            //logResult(var_export($_GET, true));
            
            if($status)
            {
                $this->changeGoodsInventory($orderInfo);
                header('Location:'.U('Home-Cart/cart_success/orderno/').$out_trade_no);
                exit;
            }
            else
            {
                echo '付款失败';
            }
        }
        else
        {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数，比对sign和mysign的值是否相等，或者检查$responseTxt有没有返回true
            echo '验证失败';
        }
    }
}