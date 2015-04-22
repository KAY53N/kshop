<?php
class MemberAction extends CommonAction {
    protected $memberModel;
    public function _initialize()
    {
        $this->feifa();
        $this->memberModel = D('Home.Member');
        $webInfo = $this->memberModel->webInfo();
        $footerNews = $this->memberModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

	function index()
    {
        $data = $this->memberModel->getMemberIndexData();
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('');
		$this->assign('data', $data);
		$this->display();
	}

    function getMemberLeftMenuHtml($active)
    {
        $arr = array(
            array(
                'oneMenu' => '交易记录',
                'twoMenu' => array(
                    'orders' => array(
                        'url' => U('Home-Member/orders'),
                        'name' => '我的订单'
                    ),
                    'points' => array(
                        'url' => U('Home-Member/pointHistory'),
                        'name' => '我的积分'
                    ),
                    'coupon' => array(
                        'url' => U('Home-Member/coupon'),
                        'name' => '我的优惠券'
                    )
                )
            ),
            array(
                'oneMenu' => '商品留言',
                'twoMenu' => array(
                    'comment' => array(
                        'url' => U('Home-Member/comment'),
                        'name' => '评论与咨询'
                    )
                )
            ),
            array(
                'oneMenu' => '个人设置',
                'twoMenu' => array(
                    'setting' => array(
                        'url' => U('Home-Member/setting'),
                        'name' => '个人信息'
                    ),
                    'security' => array(
                        'url' => U('Home-Member/security'),
                        'name' => '修改密码'
                    ),
                    'receiver' => array(
                        'url' => U('Home-Member/receiver'),
                        'name' => '收货地址'
                    )
                )
            )
        );

        $html = '<div class="member_left fl">';
        $html .= '    <div class="member_left_hd"></div>';

        foreach($arr as $key=>$val)
        {
            if($val['oneMenu'])
            {
                $html .= '<ul><li><div class="member_left_title bb pl10"><span class="span1">'.$val['oneMenu'].'</span></div></li></ul>';
            }

            if($val['twoMenu'])
            {
                $html .= '<ul class="center">';
                foreach($val['twoMenu'] as $key=>$val)
                {
                    $activeClass = $key == $active ? 'click' : '';
                    $html .= '<li class="h25 pl30 '.$activeClass.'"><a href="'.$val['url'].'">'.$val['name'].'</a></li>';
                }
                $html .= '</ul>';
            }
        }

        $html .= '<div class="member_left_ft"></div>';
        $html .= '</div>';

        return $html;
    }

	function orders()
    {
        $condition['user_id'] = array('eq', Cookie::get('user_id'));
        $data = $this->memberModel->getMembderMyOrderData($condition);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('orders');
        $this->assign('data', $data);
		$this->display('orders');
	}

	function pointHistory()
    {
        $userId = Cookie::get('user_id');
        $condition['id'] = array('eq', $userId);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('points');
        $data['allPoints'] = $this->memberModel->getSelfPoints($condition);
        $this->assign('data', $data);
		$this->display("pointHistory");
	}

	function setting()
    {
		$condition['id'] = array('eq', Cookie::get('user_id'));
        $data['selfInfo'] = $this->memberModel->getSelfInfoData($condition);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('setting');
        $this->assign('data', $data);
		$this->display("setting");
	}

	function save_setting()
    {
        $email = trim($_POST['email']);
        if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email))
        {
            $this->error('邮箱非法');
        }

        $name = trim($_POST['name']);
        if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $name))
        {
            $this->error('姓名非法');
        }
        $saveData['id'] = $_POST['where_id'];
        $saveData['email'] = $email;
        $saveData['userinfo'] = array(
		    'name'            =>$name,
		    'gender'          =>$_POST['gender'],
		    'birth_date'      =>$_POST['birth_date'],
		    'sel0'            =>$_POST['sel0'],
		    'sel1'            =>$_POST['sel1'],
		    'sel2'            =>$_POST['sel2'],
		    'site'            =>$_POST['site'],
		    'zip_code'        =>$_POST['zip_code'],
		    'mobile'          =>$_POST['mobile'],
		    'phone'           =>$_POST['phone']
		);
        $saveData['userinfo'] = $this->zaddslashes($saveData['userinfo']);
        $saveData['userinfo']['zip_code'] = intval($saveData['userinfo']['zip_code']);
        $saveData['userinfo']['mobile'] = intval($saveData['userinfo']['mobile']);

		$saveStatus = $this->memberModel->getSaveInfoStatus($saveData);

        if(isset($saveStatus))
        {
			$this->success(C('SUCCESS_MODIFY_SELF_INFO_SUCCESS'));
		}
        else
        {
			$this->error(C('ERROR_MODIFY_SELF_INFO_ERROR'));
		}
	}

	function security()
    {
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('security');
        $this->assign('data', $data);
		$this->display("security");
	}

	function security_sub()
    {
        $newPassword['password'] = md5($_POST['password']);
        $condition['username'] = Cookie::get('user_name');
        $condition['password'] = array('eq', md5($_POST['auldPassword']));
        $condition['_logic'] = 'and';

        $status = $this->memberModel->getModifyPasswordStatus($condition, $newPassword);
        if($status['pastPassword'])
        {
            if($status['newPassword'])
            {
                $this->success(C('SUCCESS_MODIFY_PASSWORD_SUCCESS'));
            }
            else
            {
                $this->error(C('ERROR_MODIFY_PASSWORD_FAILURE'));
            }
        }
        else
        {
            $this->error(C('ERROR_PAST_PASSWORD_ERROR'));
        }
	}

	function receiver()
    {
		$condition['user_id'] = array('eq', Cookie::get('user_id'));
		$data['list'] = $this->memberModel->getReceiverData($condition);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('receiver');
        $this->assign('data', $data);
		$this->display("receiver");
	}

	function add_receiver()
    {
		$condition['user_id'] = array('eq', Cookie::get('user_id'));
        $status = $this->memberModel->getReceiverData($condition);
		if($status)
        {
			$this->error(C('ERROR_ALREADY_ADD_RECEIPT_SITE'));
		}
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('security');
        $this->assign('data', $data);
        $this->display("add_receiver");
	}

	//添加收货数据处理
	function add_receiver_sub()
    {
        unset($_POST['__hash__']);
        $_POST = $this->zaddslashes($_POST);
        $_POST['mobile'] = intval($_POST['mobile']);
        $_POST['zip_code'] = intval($_POST['zip_code']);
        $condition['user_id'] = array('eq'=>Cookie::get('user_id'));
        $status = $this->memberModel->getAddReceiverStatus($_POST, $condition);
		if($status)
        {
			$this->assign('jumpUrl', 'receiver');
			$this->success(C('SUCCESS_ADD_RECEIPT_SUCCESS'));
		}
        else
        {
			$this->error(C('ERROR_ADD_RECEIPT_FAILURE'));
		}
	}

	function edit_receiver()
    {
        $condition['id'] = array('eq', $_GET['id']);
        $data['list'] = $this->memberModel->getReceiverFindData($condition);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('security');
        $this->assign('data', $data);
        $this->display("edit_receiver");
	}

	function edit_receiver_sub()
    {
		$condition['id'] = $_POST['where_id'];
        $status = $this->memberModel->getReceiverFindSaveStatus($condition, $_POST);
		if($status)
        {
			$this->success(C('SUCCESS_MODIFY_RECEIPT_SUCCESS'));
		}
        else
        {
			$this->error(C('ERROR_MODIFY_RECEIPT_FAILURE'));
		}
	}

	function del_receiver()
    {
        $status = $this->memberModel->getReceiverFindDeleteStatus($_GET['id']);
		if($status)
        {
			$this->success(C('SUCCESS_DELETE_RECEIPT_SUCCESS'));
		}
        else
        {
			$this->error(C('ERROR_DELETE_RECEIPT_FAILURE'));
		}
	}

	function coupon()
    {
		$condition['user_id'] = array('eq', Cookie::get('user_id'));
		$data['list'] = $this->memberModel->getCouponData($condition);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('coupon');
        $this->assign('data', $data);
        $this->display('coupon');
	}

	function del_coupon()
    {
		//双重条件判断，只可以删除并且登录的用户的优惠券选中的id
		$condition['id'] = array('eq', $_GET['id']);
		$condition['user_id'] = array('eq', Cookie::get('user_id'));
		$condition['_logic'] = 'and';
        $status = $this->memberModel->getCouponDeleteStatus($condition);

		if($status)
        {
			$this->success(C('SUCCESS_DELETE_COUPON_CODE_SUCCESS'));
		}
        else
        {
			$this->error(C('ERROR_DELETE_COUPON_CODE_FAILURE'));
		}
	}

	function comment()
    {
		$condition['author'] = array('eq', Cookie::get('user_name'));
		$data = $this->memberModel->getCommentData($condition);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('comment');
        $this->assign('data', $data);
		$this->display("comment");
	}

	function member_orders_details()
    {
		$condition['id'] = array('eq', intval($_GET['id']));
        $condition['user_id'] = array('eq', Cookie::get('user_id'));
        $condition['_logic'] = 'and';
        $data = $this->memberModel->getOrderDetailsData($condition);
        $data['leftMenu'] = $this->getMemberLeftMenuHtml('orders');
        $this->assign('data', $data);
 		$this->display('member_orders_details');
	}

	function order_shouhuo()
    {
        $condition['id'] = array('eq', intval($_GET['order_id']));
        $condition['user_id'] = array('eq', Cookie::get('user_id'));
        $condition['_logic'] = 'and';
        $status = $this->memberModel->getConfirmGoodsStatus($condition);

        if($status)
        {
            $this->success(C('SUCCESS_DEAL_SUCCESS'));
        }
        else
        {
            $this->error(C('ERROR_CONFIRM_REECEIPT_FAILURE'));
        }
	}
}
?>