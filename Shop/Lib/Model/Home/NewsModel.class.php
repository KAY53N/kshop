<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 20:07
 */
class NewsModel extends BaseModel
{
    public function getIndexNewsData($id)
    {
        $data['list'] = $this->tableNews()->where(array('id'=>$id))->find();
        $data['news_list'] = $this->tableNews()->order('id asc')->field('id, news_title')->select();
        return $data;
    }

    public function getNewsListData()
    {
        $count = $this->tableNews()->count();
        $page = $this->pageNavgation($count, 15);
        $data['list'] = $this->tableNews()->order('id desc')->limit($page->firstRow.','.$page->listRows)->select();
        $data['show'] = $page->show();
        $data['news_list'] = $this->tableNews()->order('id asc')->field('id,news_title')->select();
        return $data;
    }
}
?>