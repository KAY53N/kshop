<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 20:16
 */
class SearchModel extends BaseModel
{
    protected $thisGoodsCategory;
    public function _initialize()
    {
        $this->thisGoodsCategory = $this->goodsCategory();
    }

    public function getSearchData($condition)
    {
        $count = $this->tableGoods()->where($condition)->count();
        $page = $this->pageNavgation($count, 10);
        $result['goods_list'] = $this->tableGoods()->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['goods_num'] = $count;
        $result['show'] = $page->show();
        $data = array_merge($this->thisGoodsCategory, $result);
        return $data;
    }

    public function getGoodsCategoryData()
    {
        return $this->thisGoodsCategory;
    }
}
?>