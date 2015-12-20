<?php
class OrdersAction extends QxAction {
    protected $ordersModel;
    public function _initialize()
    {
        $this->feifa();
        $this->ordersModel = D('Admin.Orders');
    }

    function index()
    {
        $data = $this->ordersModel->getOrdersListData();
        $this->assign('data', $data);
		$this->display();
	}

	function edit_order()
    {
        $_GET = $this->zaddslashes($_GET);
		$findOrderCondition['id'] = array('eq', intval($_GET['id']));
		$orderInfo = $this->ordersModel->getFindOrdersData($findOrderCondition);

        $userCondition['id'] = array('eq', $orderInfo['user_id']);
        $data['userInfo'] = $this->ordersModel->getFindUserInfoData($userCondition);

        $data['orderInfo'] = $this->ordersModel->getEditOrdersData($orderInfo);

        $this->assign('data', $data);
        $this->display();
	}

	function edit_order_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        $condition['user_id'] = array('eq', intval($_POST['user_id']));
        $status = $this->ordersModel->getSaveUserinfoDataStatus($condition, $_POST);
		if($status)
        {
			$this->success('修改信息成功!');
		}
        else
        {
			$this->error('修改信息失败!');
		}
	}

	function order_fahuo()
    {
        $_GET = $this->zaddslashes($_GET);
		$condition['id'] = array('eq', intval($_GET['order_id']));
		$saveData['status'] = 2;
        $status = $this->ordersModel->getSaveOrderDataStatus($condition, $saveData);

        if($status)
        {
			$this->success('成功更改为发货状态!');
		}
        else
        {
			$this->error('更改状态失败!');
		}
	}

	function del_orders()
    {
		isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
		$deleteId = $this->zaddslashes($deleteId);
        $status = $this->ordersModel->getDeleteOrderDataStatus($deleteId);

        if($status)
        {
			$this->assign('waitSecond', 3);
			$this->success('删除订单成功!');
		}
        else
        {
			$this->error('删除订单失败!');
		}
	}

    public function search_order()
    {
        $condition[$_GET['condition']] = array('eq', $this->zaddslashes($_GET['keyword']));
        $data = $this->ordersModel->getSearchOrderData($condition);
        $this->assign('data', $data);
        $this->display('index');
    }
}
?>