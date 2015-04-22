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
        return $this->tableOrders()->where($condition)->save($saveData);
    }

    public function getOrderData($condition)
    {
        return $this->tableOrders()->where($condition)->find();
    }

    public function getSaveGoodsInventoryStatus($updateCondition, $minusGoodsNum)
    {
        return $this->tableGoods()->setDec('inventory', $updateCondition, $minusGoodsNum);
    }

    public function getSaveUserSumPointsStatus($userPointsCondition, $totalPoints)
    {
        return $this->tableUser()->setInc('sum_points', $userPointsCondition, $totalPoints);
    }
}