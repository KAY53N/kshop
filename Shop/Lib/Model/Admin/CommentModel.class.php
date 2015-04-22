<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-28
 * Time: 19:47
 */
class CommentModel extends BaseModel
{
    public function getCommentListData()
    {
        $count = $this->tableComment()->count();
        $page = $this->pageNavgation($count, 10);
        $result['list'] = $this->tableComment()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getAddCommentDataStatus($addData)
    {
        return $this->tableComment()->add($addData);
    }

    public function getFindCommentData($condition)
    {
        return $this->tableComment()->where($condition)->find();
    }

    public function getGoodsTitleData($condition)
    {
        return $this->tableGoods()->where($condition)->getField('title');
    }

    public function getDeleteCommentDataStatus($deleteId)
    {
        return $this->tableComment()->delete($deleteId);
    }
}