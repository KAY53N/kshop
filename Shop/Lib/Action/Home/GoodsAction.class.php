<?php
class GoodsAction extends CommonAction {
    protected $goodsModel;
    public function _initialize()
    {
        $this->goodsModel = D('Home.Goods');
        $webInfo = $this->goodsModel->webInfo();
        $footerNews = $this->goodsModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

	function index()
    {
        $id = intval($_GET['id']);
        $data = $this->goodsModel->getGoodsIndexData($id);
        $this->assign('data', $data);
		$this->display();
	}

	function add_comment()
    {
        $_POST = $this->zaddslashes($_POST);
		$_POST['add_date'] = time();
		$_POST['show'] = 0;

        if(md5($_POST['code']) != $_SESSION['verify'])
        {
			$this->error(C('ERROR_VERIFY_ERROR'));
		}
        else
        {
            $addStatus = $this->goodsModel->getAddGoodsCommentDataStatus($_POST);
        	if($addStatus)
            {
        		$this->success(C('SUCCESS_COMMENT_SUCCESS'));
        	}
            else
            {
        		$this->error(C('ERROR_COMMENT_FAILURE'));
        	}
		}
	}
}
?>