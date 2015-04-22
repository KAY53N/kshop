<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-28
 * Time: 16:50
 */
class SlideModel extends BaseModel
{
    public function getSlideListData()
    {
        return $this->tableSlide()->order('id asc')->select();
    }

    public function getAddSlideDataStatus($addData)
    {
        return $this->tableSlide()->add($addData);
    }

    public function getFindSlideDataStatus($condition)
    {
        return $this->tableSlide()->where($condition)->find();
    }

    public function getSaveSlideDataStatus($condition, $saveData)
    {
        return $this->tableSlide()->where($condition)->save($saveData);
    }

    public function getDeleteSlideDataStatus($deleteId)
    {
        return $this->tableSlide()->delete($deleteId);
    }
}