<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-28
 * Time: 15:55
 */
class NewsModel extends BaseModel
{
    public function getNewsListData()
    {
        $count = $this->tableNews()->count();
        $page = $this->pageNavgation($count);
        $result['list'] = $this->tableNews()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $result['show'] = $page->show();
        return $result;
    }

    public function getAddNewsDataStatus($addData)
    {
        return $this->tableNews()->add($addData);
    }

    public function getFindNewsData($condition)
    {
        return $this->tableNews()->where($condition)->find();
    }

    public function getSaveNewsDataStatus($condition, $saveData)
    {
        return $this->tableNews()->where($condition)->save($saveData);
    }

    public function getDeleteNewsStatus($deleteId)
    {
        return $this->tableNews()->delete($deleteId);
    }

    public function getSearchNewsData($keyword)
    {
        if(empty($keyword))
        {
            $count = $this->tableNews()->count();
            $page = $this->pageNavgation($count);
            $result['list'] = $this->tableNews()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        }
        else
        {
            $f_keyword = explode(' ', $keyword);
            $f_keyword = array_filter($f_keyword);

            for($i=0; $i<count($f_keyword); $i++)
            {
                $condition['news_title'][$i] = array('like', '%'.$f_keyword[$i].'%');
            }

            $count = $this->tableNews()->where($condition)->count();
            $page = $this->pageNavgation($count);
            $result['list'] = $this->tableNews()->where($condition)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();

        }
        $result['show'] = $page->show();
        return $result;
    }
}