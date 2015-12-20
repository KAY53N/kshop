<?php
class SearchAction extends CommonAction {
    protected  $searchModel;
    public function _initialize()
    {
        $this->searchModel = D('Home.Search');
        $webInfo = $this->searchModel->webInfo();
        $footerNews = $this->searchModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

	function index()
    {
		Load('extend');
		$condition['title'] = array('like', '%'.$_GET['keyword'].'%');
		$condition['title_info'] = array('like', '%'.$_GET['keyword'].'%');
		$condition['item_No'] = array('like', '%'.$_GET['keyword'].'%');
		$condition['sell_price'] = array('like', '%'.$_GET['keyword'].'%');
		$condition['_logic'] = 'or';
        $conditionMap['_complex'] = $condition;
        $conditionMap['putaway'] = array('eq', 1);

        $data = $this->searchModel->getSearchData($conditionMap);
		$this->assign('data', $data);
		$this->display();
	}

	//高级搜索
	function adv_search()
    {
		$data = $this->searchModel->getGoodsCategoryData();
        $this->assign('data', $data);
		$this->display('adv_search');
	}

	//高级搜索处理
	function adv_search_sub()
    {
		Load('extend');       // 导入中文字符扩展类
		if(!empty($_GET['keyword']))
        {
            $keyword = $_GET['keyword'];
        }

		if(!empty($_GET['item_No']))
        {
            $itemNo = $_GET['item_No'];
        }

		if(!empty($_GET['start']))
        {
            $start = $_GET['start'];
        }

        if(!empty($_GET['end']))
        {
            $end = $_GET['end'];
        }

        if(isset($keyword))
        {
            $condition["title"] = array('like','%'.$keyword.'%');
            $condition["title_info"] = array('like','%'.$keyword.'%');
            $condition["item_No"] = array('like','%'.$keyword.'%');
            $condition["sell_price"] = array('like','%'.$keyword.'%');
        }

        if(isset($itemNo))
        {
            $condition['item_No'] = array('eq', $itemNo);
        }

        $condition['sell_price'] = array();

        $condition['sell_price'] = array(
            isset($start) ? array('gt', intval($start)) : array('gt', 0),
            isset($end) ? array('lt', intval($end)) : '',
        );

        for($i=0; $i<=count($condition['sell_price']); $i++)
        {
            if(empty($condition['sell_price'][$i]))
            {
                unset($condition['sell_price'][$i]);
            }
        }
        $condition['_logic'] = 'or';
        $conditionMap['_complex'] = $condition;
        $conditionMap['putaway'] = array('eq', 1);

        $data = $this->searchModel->getSearchData($conditionMap);
        $this->assign('data', $data);
        $this->display('index');
	}
}
?>