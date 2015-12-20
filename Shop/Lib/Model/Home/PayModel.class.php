<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-30
 * Time: 15:31
 */
class PayModel extends BaseModel
{
    public function getOrderInfoData($condition)
    {
        return $this->tableOrders()->where($condition)->find();
    }

    public function getSaveOrderDataStatus($id, $saveData)
    {
        $orderTable = $this->tableOrders();
        
        $orderTable->startTrans();
        
        $orderTable->lock(true)->where('id='.$id)->getField('id');
        $orderTable->where('id='.$id)->save($saveData);
        
        return $orderTable->commit();
    }

    public function getOrderData($condition)
    {
        return $this->tableOrders()->where($condition)->find();
    }

    public function changeGoodsInventory($goodsId, $minusGoodsNum)
    {
        $goodsTable = M('Goods');
        
        $goodsTable->startTrans();
        
        $goodsTable->lock(true)->getField('id');
        $goodsTable->setDec('inventory', 'id='.$goodsId, $minusGoodsNum);
        
        return $goodsTable->commit();
    }

    public function getSaveUserSumPointsStatus($orderId, $totalPoints)
    {
        $userTable = $this->tableUser();
        
        $userTable->startTrans();
        
        $id = $userTable->lock(true)->where('id='.$orderId)->getField('id');
        $userTable->setInc('sum_points', 'id='.$id, $totalPoints);
        
        return $userTable->commit();
    }
}