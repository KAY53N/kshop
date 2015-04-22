<?php
class GoodslistAction extends CommonAction
{
    protected $goodslistModel;
    public function _initialize()
    {
        $this->goodslistModel = D('Home.Goodslist');
        $webInfo = $this->goodslistModel->webInfo();
        $footerNews = $this->goodslistModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

	function index()
    {
		Load('extend');
        $id = intval($_GET['id']);
        $data = $this->goodslistModel->getGoodslistIndexData($id);
        $this->assign('data', $data);
		$this->display();
	}
}
?>