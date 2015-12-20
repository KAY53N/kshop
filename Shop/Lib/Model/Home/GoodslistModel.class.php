<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 16:31
 */
class GoodslistModel extends BaseModel
{
    protected $thisGoodsCategory;
    public function _initialize()
    {
        $this->thisGoodsCategory = $this->goodsCategory();
    }

    public function getGoodslistIndexData($id)
    {
        $condition['brand'] = array('eq', $id);
        $condition['putaway'] = array('eq', 1);
        $condition['_logic'] = 'and';
        $count = $this->tableGoods()->where($condition)->field('id')->count();
        $page = $this->pageNavgation($count);
        $result['goods_list'] = $this->tableGoods()->where($condition)->field('id, title, title_info, inventory, sell_price, pic_one, path')->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        $result = array_merge($this->thisGoodsCategory, $result);
        return $result;
    }
}