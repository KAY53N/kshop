<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 20:16
 */
class SearchModel extends BaseModel
{
    protected $thisGoodsSort;
    public function _initialize()
    {
        $this->thisGoodsSort = $this->goodsSort();
    }

    public function getSearchData($condition)
    {
        $count = $this->tableGoods()->where($condition)->count();
        $page = $this->pageNavgation($count, 10);
        $result['goods_list'] = $this->tableGoods()->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['goods_num'] = $count;
        $result['show'] = $page->show();
        $data = array_merge($this->thisGoodsSort, $result);
        return $data;
    }

    public function getGoodsSortData()
    {
        return $this->thisGoodsSort;
    }
}
?>