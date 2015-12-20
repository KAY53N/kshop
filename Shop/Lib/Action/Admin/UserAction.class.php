<?php
class UserAction extends QxAction {
    protected $userModel;
    public function _initialize()
    {
        $this->feifa();
        $this->userModel = D('Admin.User');
    }

	function index()
    {
		$data = $this->userModel->getUserListData();
		$this->assign('data', $data);
        $this->display();
	}

	function user_check()
    {
        $condition['username'] = $this->zaddslashes($_GET['username']);
        $status = $this->userModel->getUserCheckStatus($condition);
    	if(empty($status))
        {
    		$this->ajaxReturn('', '用户名不可注册~', 0);
    	}
        else
        {
    		$this->ajaxReturn($_GET['username'], '用户名可以注册~', 1);
    	}
	}

	function add_user()
    {
		$this->feifa();
        $this->display('add_user');
	}

	function add_user_sub()
    {
        $_POST = $this->zaddslashes($_POST);
		$addData['username'] = $_POST['username'];
		$addData['password'] = md5($_POST['password']);
		$addData['email'] = $_POST['email'];
		$addData['add_time'] = time();
		$addData['userinfo']['gender'] = $_POST['gender'];
		$addData['userinfo']['birth_date'] = $_POST['birth_date'];
		$addData['userinfo']['phone'] = $_POST['phone'];
		$addData['userinfo']['mobile'] = intval($_POST['mobile']);
		$condition['username'] = array('eq', $_POST['username']);

        $status = $this->userModel->getAddUserStatus($condition, $addData);

        if($status)
        {
            $this->success('添加用户成功!');
        }
        else
        {
            $this->error('添加用户失败!');
        }
	}

	function edit_user()
    {
		$condition['id'] = array('eq', intval($_GET['id']));
        $data['userInfo'] = $this->userModel->getRelationFindUserData($condition);
		$this->assign('data',$data);
		$this->display('edit_user');
	}

	function edit_user_sub()
    {
        $_POST = $this->zaddslashes($_POST);
		$saveData['id'] = intval($_POST['id']);
        if(!empty($_POST['password']))
        {
            $saveData['password'] = md5($_POST['password']);
        }
        $saveData['email'] = $_POST['email'];
        $saveData['userinfo']['name'] = $_POST['name'];
        $saveData['userinfo']['gender'] = $_POST['gender'];
		$saveData['userinfo']['birth_date'] = $_POST['birth_date'];
        $saveData['userinfo']['sel0'] = $_POST['sel0'];
        $saveData['userinfo']['sel1'] = $_POST['sel1'];
        $saveData['userinfo']['sel2'] = $_POST['sel2'];
        $saveData['userinfo']['site'] = $_POST['site'];
        $saveData['userinfo']['zip_code'] = $_POST['zip_code'];
        $saveData['userinfo']['mobile'] = $_POST['mobile'];
        $saveData['userinfo']['phone'] = $_POST['phone'];
        $saveData['userinfo']['question'] = $_POST['question'];
        $saveData['userinfo']['answer'] = $_POST['answer'];

        $status = $this->userModel->getSaveUserDataStatus($saveData);

        if($status)
        {
			$this->success('修改会员信息成功!');
		}
        else
        {
			$this->error('修改会员信息失败!');
		}
	}

	function del_user()
    {
		isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
        $deleteId = $this->zaddslashes($deleteId);
        $status = $this->userModel->getDeleteUserStatus($deleteId);
		if($status)
        {
		    $this->success('删除会员成功!');
	    }
        else
        {
		    $this->error('删除会员失败!');
		}
	}

    function search_user()
    {
        $_POST = $this->zaddslashes($_POST);
        $gt = $_POST['pay_points_gt'];
        $lt = $_POST['pay_points_lt'];
        $keyword = $_POST['keyword'];
        $data = $this->userModel->getSearchUserData($gt, $lt, $keyword);
        $this->assign('data', $data);
        $this->display('index');
    }
}