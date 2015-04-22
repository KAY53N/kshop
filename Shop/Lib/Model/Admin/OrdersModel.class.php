<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-28
 * Time: 21:16
 */
class OrdersModel extends BaseModel
{
    public function getOrdersListData()
    {
        $count = $this->tableOrders()->count();
        $page = $this->pageNavgation($count,15);
        $result['ordersList'] = $this->tableOrders()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getFindOrdersData($condition)
    {
        return $this->tableOrders()->where($condition)->find();
    }

    public function getFindUserInfoData($condition)
    {
        return $this->tableUser()->where($condition)->relation(true)->find();
    }

    public function getEditOrdersData($orderInfo)
    {
        $titleInfo = explode('-~-', $orderInfo['title_info']);
        $titleInfo = array_filter($titleInfo);

        $result['totalPrice'] = 0;
        $morePrice = explode("-~-", $orderInfo['more_price']);
        $morePrice = array_filter($morePrice);

        $buyNum = explode("-~-", $orderInfo['buy_num']);
        $buyNum = array_filter($buyNum);

        $result['totalPoints'] = 0;
        $points = explode('-~-', $orderInfo['points']);
        $points = array_filter($points);
        for($i=0; $i<count($points); $i++)
        {
            $result['totalPoints'] = $result['totalPoints'] + $points[$i];
        }

        $orderPic = explode('-~-', $orderInfo['order_pic']);
        $orderPic = array_filter($orderPic);

        $goodsId = explode('-~-', $orderInfo['goods_id']);
        $goodsId = array_filter($goodsId);

        for($i=0; $i<count($titleInfo); $i++)
        {
            $result['info'][$i]['title'] = $titleInfo[$i];
            $result['info'][$i]['price'] = $morePrice[$i];
            $result['info'][$i]['buyNum'] = $buyNum[$i];
            $result['info'][$i]['points'] = $points[$i];
            $result['info'][$i]['orderPic'] = $orderPic[$i];
            $result['info'][$i]["goodsId"] = $goodsId[$i];
        }
        $result['status'] = $orderInfo['status'];
        $result['orderNo'] = $orderInfo['order_No'];
        $result['addDate'] = $orderInfo['add_date'];
        $result['path'] = $orderInfo['path'];
        $result['orderId'] = $orderInfo['id'];

        return $result;
    }

    public function getSaveUserinfoDataStatus($condition, $saveData)
    {
        return $this->tableUserinfo()->where($condition)->save($saveData);
    }

    public function getSaveOrderDataStatus($condition, $saveData)
    {
        return $this->tableOrders()->where($condition)->save($saveData);
    }

    public function getDeleteOrderDataStatus($deleteId)
    {
        return $this->tableOrders()->delete($deleteId);
    }

    public function getSearchOrderData($condition)
    {
        $count = $this->tableOrders()->where($condition)->count();
        $page = $this->pageNavgation($count,15);
        $result['ordersList'] = $this->tableOrders()->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }
}