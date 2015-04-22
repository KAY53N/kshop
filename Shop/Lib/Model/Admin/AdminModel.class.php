<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-27
 * Time: 21:31
 */
class AdminModel extends BaseModel
{
    public function getAdminListData()
    {
        return $this->tableAdmin()->order('id desc')->select();
    }

    public function getAdminFindData($condition)
    {
        return $this->tableAdmin()->where($condition)->find();
    }

    public function getSaveAdminDataStatus($saveCondition, $saveData)
    {
        return $this->tableAdmin()->where($saveCondition)->save($saveData);
    }

    public function getAddAdminDataStatus($condition, $addData)
    {
        $status['userExist'] = false;
        $status['addUser'] = false;
        if($this->tableAdmin()->where($condition)->find())
        {
            $status['userExist'] = true;
        }
        else
        {
            if(!empty($_POST['username']) && !empty($_POST['password']))
            {
                $status['addUser'] = $this->tableAdmin()->add($addData);
            }
        }
        return $status;
    }

    public function getDeleteAdminStatus($condition)
    {
        return $this->tableAdmin()->where($condition)->delete();
    }
}