<?php
class UserModel extends BaseModel
{
    public function getUserListData()
    {
        $count = $this->tableUser()->count();
        $page = $this->pageNavgation($count);
        $result['list'] = $this->tableUser()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getUserCheckStatus($condition)
    {
        return $this->tableUser()->where($condition)->find();
    }

    public function getAddUserStatus($condition, $addData)
    {
        return $this->tableUser()->relation(true)->where($condition)->add($addData);
    }

    public function getRelationFindUserData($condition)
    {
        return $this->tableUser()->relation(true)->where($condition)->find();
    }

    public function getSaveUserDataStatus($saveData)
    {
        return $this->tableUser()->relation(true)->save($saveData);
    }

    public function getDeleteUserStatus($deleteId)
    {
        return $this->tableUser()->relation(true)->delete($deleteId);
    }

    public function getSearchUserData($gt, $lt, $keyword)
    {
        if(!empty($gt))
        {
            $condition['sum_points'] = array('gt',$gt);
        }
        if(!empty($lt))
        {
            $condition['sum_points'] = array('lt',$lt);
        }
        if(!empty($keyword))
        {
            $condition['username'] = array('like','%'.$keyword.'%');
        }

        $count = $this->tableUser()->where($condition)->count();
        $page = $this->pageNavgation($count);
        $result['list'] = $this->tableUser()->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();

        $result['searchInfo']['gt'] = $gt;
        $result['searchInfo']['lt'] = $lt;
        $result['searchInfo']['keyword'] = $keyword;
        $result['show'] = $page->show();
        return $result;
    }
}
?>