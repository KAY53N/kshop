<?php
class NewsAction extends QxAction {
    protected $newsModel;
    public function _initialize()
    {
        $this->feifa();
        $this->newsModel = D('Admin.News');
    }

	function index()
    {
        $data = $this->newsModel->getNewsListData();
        $this->assign('data', $data);
		$this->display();
	}

	function add_news()
    {
		$this->display('add_news');
	}

	function add_news_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        $addData['news_title'] = $_POST['news_title'];
        $addData['news_date'] = $_POST['news_date'];
        $addData['news_con'] = $_POST['news_con'];

        $status = $this->newsModel->getAddNewsDataStatus($addData);
		if($status)
        {
		    $this->assign('waitSecond', 3);
		 	$this->success('添加新闻成功!');
		}
        else
        {
		 	$this->error('添加新闻失败!');
		}
	}

	function edit_news()
    {
        $condition['id'] = array('eq', intval($_GET['id']));
        $data['newsInfo'] = $this->newsModel->getFindNewsData($condition);
        $this->assign('data', $data);
		$this->display('edit_news');
	}

	function edit_news_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        $condition['id'] = array('eq', intval($_POST['id']));
		$saveData['news_title'] = $_POST['news_title'];
        $saveData['news_date'] = $_POST['news_date'];
        $saveData['news_con'] = $_POST['news_con'];
        $status = $this->newsModel->getSaveNewsDataStatus($condition, $saveData);

        if($status)
        {
			$this->assign('waitSecond', 3);
			$this->success('修改新闻成功!');
		}
        else
        {
			$this->error('修改新闻失败!');
		}
	}

	function del_news()
    {
		isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
        $deleteId = $this->zaddslashes($deleteId);
        $status = $this->newsModel->getDeleteNewsStatus($deleteId);
		if($status)
        {
			$this->assign('waitSecond', 3);
			$this->success('删除新闻成功!');
		}
        else
        {
			$this->error('删除新闻失败!');
		}
	}

    function search_news()
    {
        $keyword = $this->zaddslashes($_POST['keyword']);
        $data = $this->newsModel->getSearchNewsData($keyword);
        $this->assign('data',$data);
        $this->display('index');
    }
}