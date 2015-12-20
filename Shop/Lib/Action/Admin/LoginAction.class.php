<?php
class LoginAction extends CommonAction{
	function index()
    {
    	$this->display();
    }

    function login_user()
    {
        $user = M('admin');
        $condition['username'] = array('eq', $this->zaddslashes($_GET['username']));
        $condition['password'] = array('eq', md5($_GET['password']));
        $condition['_logic'] = 'and';

        if($user->where($condition)->find())
        {
            $this->ajaxReturn($condition,'用户名正确~',1);
        }
        else
        {
            $this->ajaxReturn('','用户名错误！',0);
        }
    }

    function login_verify()
    {
    	if($_SESSION['verify'] == md5(intval($_GET['code'])))
        {
    		$admin = M('admin');
    		$admin->setField('logintime',time());
    		Cookie::set('feifa_admin', 'passageway_admin', 60*60*24); //cookie 验证是否登录
    		//$_SESSION['feifa']="passageway"; //session 验证是否登录
    		$this->ajaxReturn('','验证码正确~', 1);
    	}
        else
        {
            $this->ajaxReturn('','验证码错误~', 0);
    	}
    }

    function login_out()
    {
    	Cookie::delete('feifa_admin');
    	if(empty($_COOKIE['feifa_admin']))
        {
    		$this->redirect('Admin-Login/index');
    	}
    }

    function deleteCache()
    {
        import('ORG.Io.Dir');
        $cacheDir = new Dir(APP_NAME.'/Runtime');
        if($cacheDir->delDir(APP_NAME.'/Runtime'))
        {
            $this->success('清除缓存成功!');
        }
        else
        {
            $this->error('清除缓存失败!请联系管理员!');
        }
    }
}