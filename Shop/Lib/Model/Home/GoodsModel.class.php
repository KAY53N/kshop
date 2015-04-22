<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-18
 * Time: 21:11
 */
class GoodsModel extends BaseModel
{
    protected $thisGoodsSort;
    public function _initialize()
    {
        $this->thisGoodsSort = $this->goodsSort();
    }

    public function getGoodsIndexData($id)
    {
        $result['g_list'] = $this->tableGoods()->where(array('id'=>$id))->find();
        $result['location'][1] = $this->tableSort()->where(array('id'=>$result['g_list']['brand']))->field('pid, name')->find();
        $result['location'][0] = $this->tableSort()->where(array('id'=>$result['location'][1]['pid']))->field('id, name')->find();

        // 遍历相关评论
        $commentCondition['goods_id'] = array('eq', $id);
        $commentCondition['show'] = array('eq', 1);
        $commentCondition['_logic'] = 'and';

        $count = $this->tableComment()->where($commentCondition)->count();
        $page = $this->pageNavgation($count, 5);
        $result['commentData'] = $this->tableComment()->where($commentCondition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        $result = array_merge($this->thisGoodsSort, $result);
        return $result;
    }

    public function getAddGoodsCommentDataStatus($addData)
    {
        return $this->tableComment()->add($addData);
    }
}