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

    public function getSaveOrderDataStatus($condition, $saveData)
    {
        $this->tableOrders()->startTrans();
        $this->tableOrders()->lock(true)->where($condition)->save($saveData);
        return $this->tableOrders()->commit();
    }

    public function getOrderData($condition)
    {
        return $this->tableOrders()->where($condition)->find();
    }

    public function getSaveGoodsInventoryStatus($updateCondition, $minusGoodsNum)
    {
        $this->tableGoods()->startTrans();
        $this->tableGoods()->lock(true)->setDec('inventory', $updateCondition, $minusGoodsNum);
        return $this->tableGoods()->commit();
    }

    public function getSaveUserSumPointsStatus($userPointsCondition, $totalPoints)
    {
        $this->tableUser()->startTrans();
        $this->tableUser()->lock(true)->setInc('sum_points', $userPointsCondition, $totalPoints);
        return $this->tableUser()->commit();
    }
}