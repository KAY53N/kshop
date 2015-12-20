<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-28
 * Time: 18:54
 */
class GoodsModel extends BaseModel
{
    public function getGoodsListData()
    {
        $count = $this->tableGoods()->count();
        $page = $this->pageNavgation($count, 12);
        $result['list'] = $this->tableGoods()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getAddGoodsDataStatus($addData)
    {
        return $this->tableGoods()->add($addData);
    }

    public function getFindGoodsData($goodsCondition)
    {
        return $this->tableGoods()->where($goodsCondition)->find();
    }

    public function getSaveGoodsDataStatus($saveCondition, $saveData)
    {
        return $this->tableGoods()->where($saveCondition)->save($saveData);
    }

    public function getDeleteGoodsDataStatus($deleteId)
    {
        return $this->tableGoods()->delete($deleteId);
    }

    public function getSearchGoodsData($condition)
    {
        $count = $this->tableGoods()->where($condition)->count();
        $page = $this->pageNavgation($count, 12);
        $result['list'] = $this->tableGoods()->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

}