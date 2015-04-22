<?php
class NewsAction extends CommonAction {
    protected $newsModel;
    public function _initialize()
    {
        $this->newsModel = D('Home.News');
        $webInfo = $this->newsModel->webInfo();
        $footerNews = $this->newsModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

	//首页新闻
    function index()
    {
        $id = intval($_GET['id']);
        $data = $this->newsModel->getIndexNewsData($id);
    	$this->assign('data', $data);
    	$this->display();
    }

    //首页新闻全部列表
    function news_list()
    {
        $data = $this->newsModel->getNewsListData();
        $this->assign('data', $data);
		$this->display('news_list');
    }
}
?>