<?php
class SortModel extends BaseModel
{
    public function getSrotListData()
    {
        $count = $this->tableSort()->count();
        $page = $this->pageNavgation($count,20);
        $result['list'] = $this->tableSort()->field('id,name,pid,path,concat(path,"-",id) as bpath')->order('bpath')->limit($page->firstRow.','.$page->listRows)->select();

        foreach($result['list'] as $key=>$value)
        {
            $result['list'][$key]['count'] = count(explode('-', $value['bpath']))-2;
        }
        $result['show'] = $page->show();
        return $result;
    }

    public function getFindSortData($condition)
    {
        return $this->tableSort()->where($condition)->find();
    }

    public function getAddSortDataStatus($addData)
    {
        return $this->tableSort()->add($addData);
    }

    public function getSortNameAndActiveSortData($condition)
    {
        $result['name'] = $this->tableSort()->where($condition)->find();
        $result['active'] = $this->tableSort()->where('id ='.$result['name']['pid'])->field('id,name')->find();
        return $result;
    }

    public function getSaveSortDataStatus($condition, $saveData)
    {
        return $this->tableSort()->where($condition)->save($saveData);
    }

    public function getAllSortListData()
    {
        $result['list'] = $this->tableSort()->field('id,name,pid,path,concat(path,"-",id) as bpath')->order('bpath')->select();

        foreach($result['list'] as $key=>$value)
        {
            $result['list'][$key]['count'] = count(explode('-', $value['bpath']))-2;
        }
        return $result;
    }

    public function getZhuanyiSortDataStatus($conditionPid, $conditionNameId)
    {
        $pid_list = $this->tableSort()->where($conditionPid)->find();
        $new_path = $pid_list['path'].'-'.$pid_list['id'];
        $new_pid = $pid_list['id'];
        $saveData['pid'] = $new_pid;
        $saveData['path'] = $new_path;

        return $this->tableSort()->where($conditionNameId)->save($saveData);
    }

    public function getDeleteSortDataStatus($deleteId)
    {
        return $this->tableSort()->delete($deleteId);
    }
}
?>