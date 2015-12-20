<?php
class CategoryModel extends BaseModel
{
    public function getCategoryListData()
    {
        $count = $this->tableCategory()->count();
        $page = $this->pageNavgation($count,20);
        $result['list'] = $this->tableCategory()->field('id,name,pid,path,concat(path,"-",id) as bpath')->order('bpath')->limit($page->firstRow.','.$page->listRows)->select();

        foreach($result['list'] as $key=>$value)
        {
            $result['list'][$key]['count'] = count(explode('-', $value['bpath']))-2;
        }
        $result['show'] = $page->show();
        return $result;
    }

    public function getFindCategoryData($condition)
    {
        return $this->tableCategory()->where($condition)->find();
    }

    public function getAddCategoryDataStatus($addData)
    {
        return $this->tableCategory()->add($addData);
    }

    public function getCategoryNameAndActiveData($condition)
    {
        $result['name'] = $this->tableCategory()->where($condition)->find();
        $result['active'] = $this->tableCategory()->where('id ='.$result['name']['pid'])->field('id,name')->find();
        return $result;
    }

    public function getSaveCategoryDataStatus($condition, $saveData)
    {
        return $this->tableCategory()->where($condition)->save($saveData);
    }

    public function getAllCategoryListData()
    {
        $result['list'] = $this->tableCategory()->field('id,name,pid,path,concat(path,"-",id) as bpath')->order('bpath')->select();

        foreach($result['list'] as $key=>$value)
        {
            $result['list'][$key]['count'] = count(explode('-', $value['bpath']))-2;
        }
        return $result;
    }

    public function getMoveCategoryDataStatus($conditionPid, $conditionNameId)
    {
        $pid_list = $this->tableCategory()->where($conditionPid)->find();
        $new_path = $pid_list['path'].'-'.$pid_list['id'];
        $new_pid = $pid_list['id'];
        $saveData['pid'] = $new_pid;
        $saveData['path'] = $new_path;

        return $this->tableCategory()->where($conditionNameId)->save($saveData);
    }

    public function getDeleteCategoryDataStatus($deleteId)
    {
        return $this->tableCategory()->delete($deleteId);
    }
}