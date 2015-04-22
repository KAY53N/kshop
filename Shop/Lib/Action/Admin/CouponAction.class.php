<?php
class CouponAction extends QxAction
{
    protected $couponModel;
    public function _initialize()
    {
        $this->feifa();
        $this->couponModel = D('Admin.Coupon');
    }

	function index()
    {
        $data = $this->couponModel->getCouponListData();
        $this->assign('data', $data);
		$this->display();
	}

	function add_coupon()
    {
		$this->display();
	}

	function add_coupon_sub()
    {
        $_POST = $this->zaddslashes($_POST);
		//如果是插入多条优惠码则执行以下
		if(!empty($_POST['num']))
        {
			$number = date('mds') . str_pad(mt_rand(1,999999), 7, '0', STR_PAD_LEFT);
			//循环加入变量多条数据
			for($i=0; $i<$_POST['num']; $i++)
            {
				$data[$i]['name'] = $_POST['name'];
				$data[$i]['coupon_code'] = 'Kshop'.mt_rand(0, 999).$number.mt_rand(0, 999);
				$data[$i]['discount'] = $_POST['discount'];
				$data[$i]['validity_date'] = strtotime($_POST['validity_date']);
				$data[$i]['status'] = $_POST['status'];
			}

        	//循环插入多条数据
        	for($i=0; $i<count($data); $i++)
            {
        		($i == (count($data)-1)) ? $success = 1 : $success = 0;
        		$this->couponModel->getAddCouponDataStatus($data[$i]);
        	}

            if($success == 1)
            {
        		$this->success('添加优惠码成功!');
        	}
            else
            {
        		$this->error('添加优惠码失败!');
        	}
		}
        else
        {
		    //如果是执行单条优惠码则执行以下
		    $_POST['validity_date'] = strtotime($_POST['validity_date']);
		    if($this->couponModel->getAddCouponDataStatus($_POST))
            {
		    	$this->success('添加优惠码成功');
		    }
            else
            {
		    	$this->error('添加优惠码失败');
		    }
		}
	}

	//编辑优惠券
	function edit_coupon()
    {
		$condition['id'] = array('eq', intval($_GET['id']));
		$data['couponInfo'] = $this->couponModel->getFindCouponData($condition);
		$this->assign('data', $data);
		$this->display();
	}

	function edit_coupon_sub()
    {
        $_POST = $this->zaddslashes($_POST);
		$condition['id'] = array('eq', intval($_POST['where_id']));
		if($this->couponModel->getSaveCouponDataStatus($condition, $_POST))
        {
			$this->success('更新成功!');
		}
        else
        {
			$this->error('更新失败!');
		}
	}

	function give_coupon()
    {
		$condition['id'] = array('eq', intval($_GET['id']));
		$data['couponInfo'] = $this->couponModel->getFindCouponData($condition);
		$this->assign('data', $data);
		$this->display();
	}

	function give_coupon_sub()
    {
        $_POST = $this->zaddslashes($_POST);
		$findCondition['username'] = array('eq', $_POST['username']);
	    $user_id = $this->couponModel->getSearchUserIdData($findCondition);
	    $saveCondition['id'] = intval($_POST['coupon_id']);
	    $data['user_id'] = $user_id;

        if($this->couponModel->getSaveCouponDataStatus($saveCondition, $data))
        {
        	$this->success('赠送成功!');
        }
        else
        {
        	$this->error('赠送失败!');
        }
	}

    function del_coupon()
    {
        isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
        if($this->couponModel->getDeleteCouponDataStatus($deleteId))
        {
            $this->assign('waitSecond', 3);
            $this->success('删除优惠码成功!');
        }
        else
        {
            $this->error('删除优惠码失败!');
        }
    }

    function search_discount()
    {
        $_GET = $this->zaddslashes($_GET);
        $condition['discount']= array($_GET['condition'], $_GET['keyword']);
        $data = $this->couponModel->getSearchCouponData($condition);
        $this->assign('data', $data);
        $this->display('index');
    }

    function search_date()
    {
        $_GET = $this->zaddslashes($_GET);
        $date = strtotime($_GET['keyword']);
        $condition['validity_date'] = array($_GET['condition'], $date);
        $data = $this->couponModel->getSearchCouponData($condition);
        $this->assign('data', $data);
        $this->display('index');
    }
}
?>