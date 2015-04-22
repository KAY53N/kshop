<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-12-8
 * Time: 16:23
 */
class ShopsetModel extends BaseModel
{
    public function getShopSetInfoData()
    {
        return $this->tableShopSet()->order('id desc')->find();
    }

    public function getSaveShopSetInfoStatus($condition, $saveData)
    {
        return $this->tableShopSet()->where($condition)->save($saveData);
    }
}