<?php
class IndexAction extends CommonAction
{
    protected $indexModel;
    public function _initialize()
    {
        import('Message');
        $this->indexModel = D('Home.Index');
        $webInfo = $this->indexModel->webInfo();
        $footerNews = $this->indexModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

    function index()
    {
        Load('extend');
        $condition['user_id'] = Cookie::get('user_id');
        $data = $this->indexModel->getIndexData($condition);
        $this->assign('data', $data);
        $this->display();
    }
}