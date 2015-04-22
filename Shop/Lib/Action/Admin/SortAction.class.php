<?php
header("Content-type:text/html; charset=utf-8");
class SortAction extends QxAction {
    protected $sortModel;
    public function _initialize()
    {
        $this->feifa();
        $this->sortModel = D('Admin.Sort');
    }

	function index()
    {
        $data = $this->sortModel->getSrotListData();
		$this->assign('data', $data);
        $this->display();
	}

	function add_sort()
    {
        $data = $this->sortModel->getAllSortListData();
        $this->assign('data', $data);
		$this->display();
	}

	function add_sort_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        if(!empty($_POST['name']))
        {
            $condition['id'] = intval($_POST['pid']);
            $sort_model=$this->sortModel->getFindSortData($condition);
            $addData['name'] = $_POST['name'];
            $addData['pid'] = intval($_POST['pid']);

            $addData['path'] = 0;
            if($_POST["pid"] != 0)
            {
                $addData['path'] = $sort_model['path'].'-'.$sort_model['id'];
            }

            $addStatus = $this->sortModel->getAddSortDataStatus($addData);

            if($addStatus)
            {
                $this->success('添加分类成功!');
            }
            else
            {
                $this->error('添加分类失败!');
            }

        }
        else
        {
            $this->error('商品分类必填');
        }
	}

	function edit_sort()
    {
        $condition['id'] = array('eq', intval($_GET['id']));
	    $data = $this->sortModel->getSortNameAndActiveSortData($condition);
        $this->assign('data', $data);
		$this->display();
	}

	function edit_sort_sub()
    {
        $_POST = $this->zaddslashes($_POST);

        $condition['id'] = intval($_POST['id']);
        $saveData['pid'] = intval($_POST['pid']);
        $saveData['name'] = $_POST['name'];
        $status = $this->sortModel->getSaveSortDataStatus($condition, $saveData);

        if($status)
        {
			$this->success('更新商品分类成功!');
		}
        else
        {
            $this->error('更新商品分类失败!');
		}
	}

	function zhuanyi_sort(){
        $condition['id'] = array('eq', intval($_GET['id']));
        $data = $this->sortModel->getSortNameAndActiveSortData($condition);
        $data['allList'] = $this->sortModel->getAllSortListData();
		$this->assign('data', $data);
		$this->display();
	}

	function zhuanyi_sort_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        $conditionNameId['id'] = array('eq', intval($_POST['name_id']));
        $conditionPid['id'] = array('eq', intval($_POST['pid']));
        $status = $this->sortModel->getZhuanyiSortDataStatus($conditionPid, $conditionNameId);

        if($status)
        {
			$this->success('转移成功!');
		}
        else
        {
			$this->error('转移失败!');
		}
 
	}

    function del_sort()
    {
        isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
        $deleteId = $this->zaddslashes($deleteId);
        $status = $this->sortModel->getDeleteSortDataStatus($deleteId);

        if($status)
        {
            $this->success('删除商品分类成功!');
        }
        else
        {
            $this->error('删除商品分类失败!');
        }
    }
}
?>