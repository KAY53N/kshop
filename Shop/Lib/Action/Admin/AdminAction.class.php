<?php
class AdminAction extends QxAction {
    protected $adminModel;
    public function _initialize()
    {
        $this->feifa();
        $this->adminModel = D('Admin.Admin');
    }

	function index()
    {
        $data['list'] = $this->adminModel->getAdminListData();
		$this->assign('data', $data);
		$this->display();
	}

    function edit_admin()
    {
        $condition['id'] = array('eq', intval($_GET['id']));
        $data['info'] = $this->adminModel->getAdminFindData($condition);
        $this->assign('data', $data);
        $this->display('edit_admin');
    }

    function edit_admin_sub(){
        $condition['id'] = intval($_POST['id']);
        $saveData['email'] = $this->zaddslashes($_POST['email']);

        if(!empty($_POST['password']))
        {
            $saveData['password'] = md5($this->zaddslashes($_POST['password']));
        }

        $status = $this->adminModel->getSaveAdminDataStatus($condition, $saveData);

        if(!empty($status))
        {
            $this->success('修改管理员资料成功');
        }
        else
        {
            $this->error('修改管理员资料失败');
        }
    }

	function add_admin(){
		$this->display('add_admin');
	}

	function add_admin_sub(){
        $_POST = $this->zaddslashes($_POST);
        $addData['username'] = $_POST['username'];
        $addData['password'] = md5($_POST["password"]);
        $addData['email'] = $_POST['email'];
        $addData['addtime'] = time();
        $addData['logintime'] = 0;

        $condition['username'] = array('eq', $_POST['username']);

        $status = $this->adminModel->getAddAdminDataStatus($condition, $addData);

        if($status['userExist'])
        {
            $this->error('需要添加的用户名已存在');
        }
        else if(!empty($_POST['username']) && !empty($_POST['password']))
        {
            if($status['addUser'])
            {
                $this->success('添加管理员成功');
            }
            else
            {
                $this->error('添加管理员失败');
            }
        }
        else
        {
            $this->error('用户名或密码为空');
        }
	}

	function del_admin(){
        $condition['id'] = intval($_GET['id']);
        $stauts = $this->adminModel->getDeleteAdminStatus($condition);

        if($stauts)
        {
	        $this->success('删除管理员成功!');
		}
        else
        {
			$this->error('删除管理员失败!');
		}
	}
}
?>