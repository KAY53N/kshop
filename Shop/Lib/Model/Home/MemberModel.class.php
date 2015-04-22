<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 21:26
 */
class MemberModel extends BaseModel
{
    public function getMemberIndexData()
    {
        $condition['username'] = array('eq', Cookie::get('user_name'));
        $nameId = $this->tableUser()->where($condition)->getField('id');
        $selfInfo = $this->tableUser()->relation(true)->where('id='.$nameId)->find();
        if(!empty($selfInfo))
        {
            Cookie::set('member_name', $selfInfo['name']);
        }

        $commentCondition['author'] = array('eq', Cookie::get('user_name'));
        $commentCondition['show'] = array('eq', 1);
        $result['allCommentNum'] = $this->tableComment()->where($commentCondition)->count();

        $orderCondition['user_id'] = array('eq', $nameId);
        $result['order_list'] = $this->tableOrders()->where($orderCondition)->order('id desc')->limit(6)->select();
        $result['orderAllNum'] = $this->tableOrders()->where($orderCondition)->count();
        $result['allPoints'] = $selfInfo['sum_points'];
        $result['allPrice'] = $selfInfo['available_funds'];

        return $result;
    }

    public function getMembderMyOrderData($condition)
    {
        $count = $this->tableOrders()->where($condition)->count();
        $page = $this->pageNavgation($count, 10);
        $result['order_list'] = $this->tableOrders()->where($condition)->field('id, title, title_info, price, path, status')->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getSelfPoints($condition)
    {
        return $this->tableUser()->where($condition)->getField('sum_points');
    }

    public function getSelfInfoData($condition)
    {
        return $this->tableUser()->where($condition)->relation(true)->find();
    }

    public function getSaveInfoStatus($saveData)
    {
        return $this->tableUser()->relation(true)->save($saveData);
    }

    public function getModifyPasswordStatus($condition, $newPassword)
    {
        $status['pastPassword'] = false;
        $status['newPassword'] = false;
        $userId = $this->tableUser()->where($condition)->getField('id');
        if(!empty($userId))
        {
            $status['pastPassword'] = true;
            if($this->tableUser()->where('id ='.$userId)->save($newPassword))
            {
                $status['newPassword'] = true;
            }

        }
        return $this->tableUser()->getLastSql();
    }

    public function getReceiverData($condition)
    {
        return $this->tableUserinfo()->where($condition)->select();
    }

    public function getAddReceiverStatus($post, $condition)
    {
        return $this->tableUserinfo()->where($condition)->save($post);
    }

    public function getReceiverFindData($condition)
    {
        return $this->tableUserinfo()->where($condition)->find();
    }

    public function getReceiverFindSaveStatus($condition, $post)
    {
        return $this->tableUserinfo()->where($condition)->save($post);
    }

    public function getReceiverFindDeleteStatus($id)
    {
        return $this->tableUserinfo()->delete($id);
    }

    public function getCouponData($condition)
    {
        return $this->tableCoupon()->where($condition)->select();
    }

    public function getCouponDeleteStatus($condition)
    {
        return $this->tableCoupon()->where($condition)->delete();
    }

    public function getCommentData($condition)
    {
        $count = $this->tableComment()->where($condition)->count();
        $page = $this->pageNavgation($count, 10);
        $result['commentList'] = $this->tableComment()->where($condition)->order('goods_id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getOrderDetailsData($condition)
    {
        $result['list'] = $this->tableOrders()->where($condition)->find();

        $title_info = explode('-~-', $result['list']['title_info']);
        $title_info = array_filter($title_info);

        $more_price = explode('-~-', $result['list']['more_price']);
        $more_price = array_filter($more_price);

        $buy_num = explode('-~-', $result['list']['buy_num']);
        $buy_num = array_filter($buy_num);

        $result['total_points'] = 0;
        $points = explode('-~-', $result['list']['points']);
        $points = array_filter($points);
        for($i=0; $i<count($points); $i++)
        {
            $result['total_points'] = (int)$result['total_points'] + (int)$points[$i];
        }

        $order_pic = explode('-~-', $result['list']['order_pic']);
        $order_pic = array_filter($order_pic);

        $goods_id = explode('-~-', $result['list']['goods_id']);
        $goods_id = array_filter($goods_id);

        for($i=0; $i<count($goods_id); $i++)
        {
            $result['info'][$i]['title'] = $title_info[$i];
            $result['info'][$i]['price'] = $more_price[$i];
            $result['info'][$i]['buy_num'] = $buy_num[$i];
            $result['info'][$i]['points'] = $points[$i];
            $result['info'][$i]['order_pic'] = $order_pic[$i];
            $result['info'][$i]['goods_id'] = $goods_id[$i];
            $result['info'][$i]['path'] = $result['list']['path'];
        }

        $result['userInfo'] = $this->tableUser()->relation(true)->where('id = '.$result['list']['user_id'])->find();
        return $result;
    }

    public function getConfirmGoodsStatus($condition)
    {
        $status = false;
        $status = $this->tableOrders()->where($condition)->field('status')->find();
        if($status['status'] == 2)
        {
            $data['status'] = intval($status['status'])+1;
            if($this->tableOrders()->where($condition)->save($data))
            {
                $status = true;
            }
        }
        return $status;
    }

}