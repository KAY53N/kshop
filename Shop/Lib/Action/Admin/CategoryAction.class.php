<?php
class CategoryAction extends QxAction {
    protected $categoryModel;
    public function _initialize()
    {
        $this->feifa();
        $this->categoryModel = D('Admin.Category');
    }

	function index()
    {
        $data = $this->categoryModel->getCategoryListData();
		$this->assign('data', $data);
        $this->display();
	}

	function add_category()
    {
        $data = $this->categoryModel->getAllCategoryListData();
        $this->assign('data', $data);
		$this->display();
	}

	function add_category_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        if(!empty($_POST['name']))
        {
            $condition['id'] = intval($_POST['pid']);
            $category_model = $this->categoryModel->getFindCategoryData($condition);
            $addData['name'] = $_POST['name'];
            $addData['pid'] = intval($_POST['pid']);

            $addData['path'] = 0;
            if($_POST["pid"] != 0)
            {
                $addData['path'] = $category_model['path'].'-'.$category_model['id'];
            }

            $addStatus = $this->categoryModel->getAddCategoryDataStatus($addData);

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

	function edit_category()
    {
        $condition['id'] = array('eq', intval($_GET['id']));
	    $data = $this->categoryModel->getCategoryNameAndActiveData($condition);
        $this->assign('data', $data);
		$this->display();
	}

	function edit_category_sub()
    {
        $_POST = $this->zaddslashes($_POST);

        $condition['id'] = intval($_POST['id']);
        $saveData['pid'] = intval($_POST['pid']);
        $saveData['name'] = $_POST['name'];
        $status = $this->categoryModel->getSaveCategoryDataStatus($condition, $saveData);

        if($status)
        {
			$this->success('更新商品分类成功!');
		}
        else
        {
            $this->error('更新商品分类失败!');
		}
	}

	function move_category(){
        $condition['id'] = array('eq', intval($_GET['id']));
        $data = $this->categoryModel->getCategoryNameAndActiveData($condition);
        $data['allList'] = $this->categoryModel->getAllCategoryListData();
		$this->assign('data', $data);
		$this->display();
	}

	function move_category_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        $conditionNameId['id'] = array('eq', intval($_POST['name_id']));
        $conditionPid['id'] = array('eq', intval($_POST['pid']));
        $status = $this->categoryModel->getMoveCategoryDataStatus($conditionPid, $conditionNameId);

        if($status)
        {
			$this->success('转移成功!');
		}
        else
        {
			$this->error('转移失败!');
		}
 
	}

    function del_category()
    {
        isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
        $deleteId = $this->zaddslashes($deleteId);
        $status = $this->categoryModel->getDeleteCategoryDataStatus($deleteId);

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