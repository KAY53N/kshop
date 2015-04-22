<?php
class CartAction extends CommonAction
{
    protected $cartModel;
    public function _initialize()
    {
        $this->cartModel = D('Home.Cart');
        $webInfo = $this->cartModel->webInfo();
        $footerNews = $this->cartModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

	function index()
    {
        Cookie::get('feifa_home') == 'passageway_home' ? $condition['qx'] = Cookie::get('feifa_home') == 'passageway_home' : 0;
        Cookie::get('user_name') ? $condition['userName'] = Cookie::get('user_name') : 0;
        cookie::get('user_id') ? $condition['userId'] = Cookie::get('user_id') : 0;
        $condition['goods_id'] = intval($_POST['goods_id']);
        $condition['buy_num']= intval($_POST['buy_num']);

        $result = $this->cartModel->getCartIndexData($condition);
        if($result['loginStatus'] == 1)
        {
            if($result['addStatus'] == 1)
            {
                $this->redirect('cart');
            }
            else
            {
                $this->error(C('ERROR_OPERATION_FAILURE'));
            }
        }
        else
        {
            $this->error(C('ERROR_NOT_LOGIN_NOT_BUY'));
        }
	}

	function cart()
    {
		Cookie::get('feifa_home') == "passageway_home" ? $qx = Cookie::get('feifa_home') == "passageway_home" : 0;
		Cookie::get('user_name') ? $user_name = Cookie::get('user_name') : 0;
        if(isset($qx) && isset($user_name))
        {
			$condition['user_id'] = array('eq', Cookie::get('user_id'));
            $data = $this->cartModel->getCartStatusData($condition);
            $this->assign('data', $data);
		}
        else
        {
			$this->error(C('ERROR_CART_EMPTY'));
		}
		$this->display('cart');
	}

	function up_ajax()
    {
        $get['conditionData']['total_price'] = intval($_GET['conditionData']['total_price']);
        $get['conditionData']['buy_num'] = intval($_GET['conditionData']['buy_num']);
        $get['conditionData']['condition_id'] = intval($_GET['conditionData']['condition_id']);
        $get['conditionData']['condition_userid'] = Cookie::get('user_id');
        $get['conditionData']['goods_id'] = intval($_GET['conditionData']['goods_id']);
        $userId = Cookie::get('user_id');
        $result = $this->cartModel->getUpdateCartAjaxData($get, $userId);
        if($result['ajaxReturn'] == 1)
        {

            $this->ajaxReturn($result, C('SUCCESS_UPDATE_CART_INFO_SUCCESS'), 1);
        }
        else if($result['ajaxReturn'] == -1)
        {
            $this->ajaxReturn($result, C('ERROR_CART_INVENTORY_LACK'), -1);
        }
        else
        {
            $this->ajaxReturn('', C('ERROR_UPDATE_CART_INFO_FAILURE'), 0);
        }
	}

	function del_goods()
    {
		$condition['id'] = array('eq', intval($_GET['condition_id']));
        $condition['user_id'] = array('eq', Cookie::get('user_id'));
        $condition['_logic'] = 'and';
        $status = $this->cartModel->getDeleteGoodsStatus($condition);
        if($status)
        {
			$this->ajaxReturn($condition['id'], C('SUCCESS_DELETE_CART_GOODS_SUCCESS'), 1);
		}
        else
        {
			$this->ajaxReturn('', C('ERROR_DELETE_CART_GOODS_FAILURE'), 0);
		}
	}

	function empty_cart()
    {
		$condition['user_id'] = array('eq', Cookie::get('user_id'));
        $status = $this->cartModel->getClearCartStatus($condition);
        if($status)
        {
			$this->success(C('SUCCESS_CLEAR_CART_SUCCESS'));
		}
        else
        {
			$this->error(C('ERROR_CLEAR_CART_FAILURE'));
		}
	}

	function coupon()
    {
        if((empty($_POST['coupon_code'])) || ($_POST['coupon_code'] == '请输入优惠券号码'))
        {
			$this->error(C('ERROR_COUPON_CODE_EMPTY'));
		}

		if($_POST['coupon_code'] == 'xujiantao')
        {
			$condition['user_id'] = array('eq', Cookie::get('user_id'));
            $result = $this->cartModel->setCouponPrice($condition);

			if(count($result['success']) == count($result['cart_list']))
            {
				$this->success(C('SUCCESS_TEST_COUPON_CODE_SUCCESS'));
			}
            else
            {
				$this->error(C('ERROR_TEST_COUPON_CODE_FAILURE'));
			}
		}

		$couponCodeCondition['coupon_code'] = array('eq', $this->zaddslashes($_POST['coupon_code']));
        $couponCode = $this->cartModel->getSearchCouponStatus($couponCodeCondition);
        if(!empty($couponCode))
        {
            $result = $this->cartModel->getCouponBizStatus($couponCode);
            if(intval(count($result['couponCode'])) == intval(count($result['cart_list'])))
            {
                $this->success(C('SUCCESS_DISCOUNT_SUCCESS'));
            }
            else
            {
                $this->error(C('ERROR_DISCOUNT_FAILURE'));
            }
		}
        else
        {
			$this->error(C('ERROR_COUPON_CODE_ERROR'));
		}

	}

	function cart_checkout()
    {
		$this->feifa();
        $condition['user_id'] = array('eq', Cookie::get('user_id'));
		$data['cartList'] = $this->cartModel->getUserCartInfo($condition);
        $data['title'] = 'Kshop数码,数码相机,手机商城,电脑商城,全国货到付款！  ';

		if(empty($data['cartList']))
        {
			$this->assign('jumpUrl', 'cart');
			$this->error(C('ERROR_CART_EMPTY'));
		}
        else
        {
            $data = $this->cartModel->getUserCartInfoSupplement($data);
		}
		$this->assign('data',$data);
		$this->display('cart_checkout');
	}

	function add_orders()
    {
        $userId = Cookie::get('user_id');
        $post = $this->zaddslashes($_POST);
        foreach($post as $k=>$v)
        {
            if(strpos($k, 'where_id') !== false)
            {
                $post[$k] = intval($v);
            }
            if($k == 'price')
            {
                $post[$k] = sprintf('%.2f', $v);
            }
        }

        $result = $this->cartModel->getAddOrdersStatus($post, $userId);
        if($result['status'])
        {
            header('Location:'.U('Home-Pay/aliPayTo/id/').$result['orderId']);
		}
        else
        {
			$this->error(C('ERROR_ORDER_FAILURE'));
		}
	}

	function cart_success()
    {
        $condition['order_No'] = array('eq', $_GET['orderno']);
        $condition['user_id'] = array('eq', Cookie::get('user_id'));
        $condition['_logic'] = 'and';

		$data = $this->cartModel->getPaymentSuccess($condition);
        $this->assign('data', $data);
		$this->display('cart_success');
	}

	function cart_orders_details()
    {
		$condition['id'] = array('eq', $_GET['id']);
        $data = $this->cartModel->getOrderDetailsData($condition);
        $this->assign('data', $data);
		$this->display('cart_orders_details');
	}
}
?>