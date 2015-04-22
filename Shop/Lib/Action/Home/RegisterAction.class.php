<?php
class RegisterAction extends CommonAction {
    protected $registerModel;
    public function _initialize()
    {
        $this->registerModel = D('Home.Register');
        $webInfo = $this->registerModel->webInfo();
        $footerNews = $this->registerModel->webFooterNews();
        $this->assign('webInfo', $webInfo);
        $this->assign('footerNews', $footerNews);
    }

    function index()
    {
    	$this->display();
    }

    function register_user()
    {
        $username = trim($_GET['username']);
        if(!preg_match('/^[a-zA-Z0-9_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+$/', $username))
        {
            $this->ajaxReturn('', '', -1);
            die();
        }

    	$condition['username'] = array('eq', $username);
        $status = $this->registerModel->getUserCheckStatus($condition);
        if(empty($status))
        {
    		$this->ajaxReturn('', C('ERROR_ACCOUNT_NOT_REGISTER'), 0);
    	}
        else
        {
    		$this->ajaxReturn($condition['username'], C('SUCCESS_ACCOUNT_CAN_REGISTER'), 1);
    	}
     
    }

    function register_sub()
    {
    	unset($_POST['pwd_confirm']);
    	unset($_POST['favorite']);

        $email = trim($_POST['email']);
        if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email))
        {
            $this->error('Email error');
        }

    	if(md5($_POST['code']) != $_SESSION['verify'])
        {
    		$this->error(C('ERROR_VERIFY_ERROR'));
    	}
        else
        {
    		$_POST['add_time'] = time();
            $checkUsernameCondition['username'] = array('eq', $this->zaddslashes($_POST['username']));
            $status = $this->registerModel->getUserCheckStatus($checkUsernameCondition);

            if(empty($status))
            {
                $saveData['username'] = trim($this->zaddslashes($_POST['username']));
                $saveData['password'] = md5($_POST['password']);
                $saveData['email'] = trim($this->zaddslashes($_POST['email']));
                $saveData['add_time'] = time();
                $userResult = $this->registerModel->getRigisterUser($saveData);
                if($userResult['status'])
                {
                    Cookie::set('user_name', $saveData['username']);
                    Cookie::set('user_id', $userResult['user_id'], 60*60*24); //user id
                    Cookie::set('feifa_home', 'passageway_home', 60*60*24); //cookie 验证是否登录
                    Cookie::set('cart_num', $userResult['cart_num'], 60*60*24);  // 设置cookie购物车商品数

                    $email = $saveData['username'];
                    $title = '感谢注册您的 Kshop数码 ！';
                    $content = '<div>';
                    $content .= sprintf('尊敬的&nbsp;%s<br>', COOKIE::get('user_name'));
                    $content .= '感谢您注册 Kshop数码，您的个人信息请妥善保管个人注册信息<br>';
                    $content .= sprintf('用户名：%s<br>发送时间：%s<br>', $saveData['username'], date('Y-m-d H:i:s', $_POST['add_time']));
                    $content .= '■重要信息：由于此邮件包含个人注册资料，请妥善保存!</div>';

                    //注册成功
                    $this->SendMail($email, $title, $content);
                    $this->redirect('passport_create');
                }
                else
                {
                    $this->error(C('ERROR_REGISTER_FAILURE'));
                }

            }
            else
            {
            	$this->error(C('ERROR_ACCOUNT_HAVE_USE'));
            }

    	}
    	
    }

    function passport_create()
    {
    	$this->assign('user_id', Cookie::get('user_id'));
    	$this->display('passport_create');
    }

    function saveuser_info()
    {
        if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', trim($_POST['name'])))
        {
            $this->error('姓名非法');
        }
        $_POST = $this->zaddslashes($_POST);
        $_POST['zip_code'] = intval($_POST['zip_code']);
        $_POST['mobile'] = intval($_POST['mobile']);
        $_POST['user_id'] = intval(Cookie::get('user_id'));
        $status = $this->registerModel->getSaveUserInfo($_POST);

        if($status)
        {
            $this->assign('jumpUrl', U('Home-Member/index'));
            $this->assign('waitSecond', 3);
            $this->success(C('SUCCESS_SUBMIT_USER_INFO_SUCCESS'));
        }
        else
        {
            $this->error(C('ERROR_SUBMIT_USER_INFO_FAILURE'));
        }
    }
}
?>