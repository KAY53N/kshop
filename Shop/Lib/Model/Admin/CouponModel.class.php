<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-28
 * Time: 20:26
 */
class CouponModel extends BaseModel
{
    public function getCouponListData()
    {
        $count = $this->tableCoupon()->count();
        $page = $this->pageNavgation($count, 10);
        $result['list'] = $this->tableCoupon()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getAddCouponDataStatus($addData)
    {
        return $this->tableCoupon()->add($addData);
    }

    public function getFindCouponData($condition)
    {
        return $this->tableCoupon()->where($condition)->find();
    }

    public function getSaveCouponDataStatus($condition, $saveData)
    {
        return $this->tableCoupon()->where($condition)->save($saveData);
    }

    public function getSearchUserIdData($condition)
    {
        return $this->tableUser()->where($condition)->getField('id');
    }

    public function getDeleteCouponDataStatus($deleteId)
    {
        return $this->tableCoupon()->delete($deleteId);
    }

    public function getSearchCouponData($condition)
    {
        $count = $this->tableCoupon()->where($condition)->count();
        $page = $this->pageNavgation($count, 10);
        $result['list'] = $this->tableCoupon()->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }
}