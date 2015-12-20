<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 17:35
 */
class CartModel extends BaseModel
{
    public function getCartIndexData($condition)
    {
        $result['loginStatus'] = 0;
        $result['addStatus'] = 0;

        if(isset($condition['qx']) && isset($condition['userName']) && isset($condition['userId']))
        {
            $result['loginStatus'] = 1;
            $cartInfo = $this->tableGoods()->where('id='.$condition['goods_id'])->field(array(
                'id'=>'goods_id',
                'pic_one'=>'pic',
                'path',
                'title'=>'goods_name',
                'item_No',
                'points',
                'market_price',
                'sell_price')
            )->find();
            $cartInfo['buy_num'] = $condition['buy_num'];
            $cartInfo['total_price'] = intval($cartInfo['sell_price'])*intval($condition['buy_num']);
            $cartInfo['user_id'] = $condition['userId'];

            if($this->tableCart()->add($cartInfo))
            {
                $result['addStatus'] = 1;
            }
        }
        return $result;
    }

    public function getCartStatusData($condition)
    {
        $result['list'] = $this->tableCart()->where($condition)->order('id desc')->select();
        $result['all_price'] = 0;

        foreach($result['list'] as $key=>$val)
        {
            $result['all_price'] = (float)$result['all_price']+(float)$val['total_price'];;
        }

        Cookie::delete('cart_num'); //删除旧数量
        $cartCondition['user_id'] = array('eq', Cookie::get('user_id')); //查询商品数量条件
        $cart_num = count($this->tableCart()->where($cartCondition)->field('id')->select());
        Cookie::set('cart_num', $cart_num, 60*60*24);  // 设置cookie购物车商品数
        return $result;
    }

    public function getUpdateCartAjaxData($get, $userId)
    {
        $result['ajaxReturn'] = 0;
        $condition['id'] = array('eq', $get['conditionData']['condition_id']);
        $condition['user_id'] = array('eq', $userId);
        $condition['_logic'] = 'and';

        $currentPrice = $this->tableCart()->where($condition)->getField('sell_price');

        $saveData['buy_num'] = $get['conditionData']['buy_num'];
        $saveData['total_price'] = $get['conditionData']['total_price'];

        $goodsSurplusCondition['id'] = array('eq', $get['conditionData']['goods_id']);
        $goodsInventory = $this->tableGoods()->where($goodsSurplusCondition)->getField('inventory');

        $saveStatus = false;
        if((($currentPrice * $saveData['buy_num']) == $saveData['total_price']) && (intval($goodsInventory) >= intval($saveData['buy_num'])))
        {
            $saveStatus = $this->tableCart()->where($condition)->save($saveData);
        }
        else
        {
            $result['cartNum'] = $this->tableCart($condition)->getField('buy_num');
            $result['ajaxReturn'] = -1;
        }

        $userCondition['user_id'] = array('eq', $userId);
        if(!empty($saveStatus))
        {
            $list = $this->tableCart()->where($userCondition)->order('id desc')->select();
            $result['all_price'] = 0;

            foreach($list as $key=>$val)
            {
                $result['all_price'] = (float)$result['all_price']+(float)$val['total_price'];;
            }

            //查询总价
            $resData = $this->tableCart()->where($condition)->field('total_price')->order('id desc')->find();
            $result['total_price'] = $resData['total_price'];
            $result['ajaxReturn'] = 1;
        }

        return $result;
    }

    public function getDeleteGoodsStatus($condition)
    {
        return $this->tableCart()->where($condition)->delete();
    }

    public function getClearCartStatus($condition)
    {
        return $this->tableCart()->where($condition)->delete();
    }

    public function setCouponPrice($condition)
    {
        $saveData['total_price'] = 0.01;
        $status = $result['cart_list'] = $this->tableCart()->where($condition)->save($saveData);
        return $status;
    }

    public function getCouponStatus($condition)
    {
        $result['cart_list'] = $this->tableCart()->where($condition)->order("id desc")->select();

        for($i=0; $i<count($result['cart_list']); $i++)
        {
            $data[$i]["total_price"] = (float)0.01;
            $saveCondition["id"] = array("eq",$data['cart_list'][$i]["id"]);
            $data['success'][] .= $this->tableCart()->where($saveCondition)->save($data[$i]);
        }
    }

    public function getCouponBizStatus($couponCode)
    {
        $this->tableCoupon()->delete($couponCode['id']);  //删除优惠码
        //打折处理
        $couponCodeCondition['user_id'] = array('eq', Cookie::get('user_id'));
        $result['cart_list'] = $this->tableCart()->where($couponCodeCondition)->order('id desc')->select();

        for($i=0; $i<count($result['cart_list']); $i++)
        {
            $saveData[$i]['total_price'] = (float)$result['cart_list'][$i]['total_price'] * (float)$couponCode['discount'];
            $saveCondition['id'] = array('eq', $result['cart_list'][$i]['id']);
            $result['couponCode'][] .= $this->tableCart()->where($saveCondition)->save($saveData[$i]);
        }
        return $result;
    }

    public function getUserCartInfo($condition)
    {
        return $this->tableCart()->where($condition)->order('id desc')->select();
    }

    public function getUserCartInfoSupplement($data)
    {
        //总价和积分
        $data['all_price'] = 0;
        $data['all_points'] = 0;

        foreach($data['cartList'] as $key=>$val)
        {
            $data['all_price'] = (float)$data['all_price'] + (float)$val['total_price'];
            $data['all_points'] = (int)$data['all_points'] + (int)$val['points'];
        }

        //会员信息
        $condition['user_id'] = array('eq', Cookie::get('user_id'));
        $data['info'] = $this->tableUserinfo()->where($condition)->find();
        $data['userAllPoints'] = $this->tableUser()->where('id='.Cookie::get('user_id'))->getField('sum_points');
        //订单
        $data['order_No'] = 'D' . mt_rand(1, 99) . date('Ymd') . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
        return $data;
    }

    public function getAddOrdersStatus($post, $userId)
    {
        $post['add_date'] = time();
        $post['user_id'] = $userId;

        foreach($post as $key=>$val)
        {
            $post[$key] = rtrim($val, '-~-');
        }

        $buy_num = explode('-~-', $post['buy_num']);
        $buy_num = array_filter($buy_num);

        $goods_id = explode('-~-', $post['goods_id']);
        $goods_id = array_filter($goods_id);

        for($i=0; $i<count($goods_id); $i++)
        {
            $inventory = $this->tableGoods()->where(array('id'=>$goods_id[$i]))->getField('inventory');
            $data['inventory'] = intval($inventory)-intval($buy_num[$i]);
            
            $this->tableGoods()->startTrans();
            if(intval($data['inventory']) <= 0)
            {
                $putawayData['putaway'] = 0;
                $this->tableGoods()->lock(true)->where(array('id'=>$goods_id[$i]))->save($putawayData);
            }
            else
            {
                $this->tableGoods()->lock(true)->where(array('id'=>$goods_id[$i]))->save($data);
            }
            $this->tableGoods()->commit();
        }

        $deleteCondition['user_id'] = array('eq', $userId);
        Cookie::set('cart_num', 0, 60*60*24);
        $result['orderId'] = $this->tableOrders()->add($post);
        $result['status'] = $result['orderId'] && $this->tableCart()->where($deleteCondition)->delete();
        return $result;
    }

    public function getPaymentSuccess($condition)
    {
        $data['info'] = $this->tableOrders()->where($condition)->find();
        $more_price = explode('-~-',$data['info']['more_price']);
        $more_price = array_filter($more_price);

        $result['total_price'] = 0;
        for($i=0; $i<count($more_price); $i++)
        {
            $result['total_price'] = $result['total_price'] + $more_price[$i];
        }
        $result['orderNo'] = $data['info']['order_No'];
        $result['id'] = $data['info']['id'];
        return $result;
    }

    public function getOrderDetailsData($condition)
    {
        $data['list'] = $this->tableOrders()->where($condition)->find();
        $title_info = explode('-~-',$data['list']['title_info']);
        $title_info = array_filter($title_info);

        $more_price = explode('-~-',$data['list']['more_price']);
        $more_price = array_filter($more_price);

        $buy_num = explode('-~-',$data['list']['buy_num']);
        $buy_num = array_filter($buy_num);

        $data['total_points'] = 0;
        $points = explode('-~-',$data['list']['points']);
        $points = array_filter($points);
        for($i=0; $i<count($points); $i++)
        {
            $data['total_points'] = $data['total_points'] + $points[$i];
        }

        $order_pic = explode("-~-",$data['list']['order_pic']);
        $order_pic = array_filter($order_pic);

        $goods_id = explode('-~-',$data['list']['goods_id']);
        $goods_id = array_filter($goods_id);

        for($i=0; $i<count($title_info); $i++)
        {
            $data['info'][$i]['title'] = $title_info[$i];
            $data['info'][$i]['price'] = $more_price[$i];
            $data['info'][$i]['buy_num'] = $buy_num[$i];
            $data['info'][$i]['points'] = $points[$i];
            $data['info'][$i]['order_pic'] = $order_pic[$i];
            $data['info'][$i]['goods_id'] = $goods_id[$i];
        }

        $data['userInfo'] = $this->tableUser()->relation(true)->where('id = '.$data['list']['user_id'])->find();
        return $data;
    }

    public function getSearchCouponStatus($condition)
    {
        return $this->tableCoupon()->where($condition)->find();
    }

}