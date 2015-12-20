<?php
class SlideAction extends QxAction {
    protected $slideModel;
    public function _initialize()
    {
        $this->feifa();
        $this->slideModel = D('Admin.Slide');
    }

	function index()
    {
        $data['list'] = $this->slideModel->getSlideListData();
		$this->assign('data', $data);
		$this->display();
	}

    function add_slide()
    {
		$this->display('add_slide');
	}

	function add_slide_sub()
    {
		if(empty($_FILES['file']['name']))
        {
			$this->error('请上传文件');
		}
        else
        {
			$info = $this->upload_file();
            $_POST  = $this->zaddslashes($_POST);
			$addData['href_url'] = $_POST['href_url'];
            $addData['alt'] = $_POST['alt'];
            $addData['target'] = $_POST['target'];
            $addData['img_url'] = $info['prefix'].$info[0]['savename'];

            $status = $this->slideModel->getAddSlideDataStatus($addData);
            if($status)
            {
				$this->assign('waitSecond', 3);
				$this->success('添加幻灯片成功!');
			}
            else
            {
				$this->error('添加幻灯片失败!');
			}
		}
	}

	function edit_slide()
    {
        $condition['id'] = array('eq', intval($_GET['id']));
        $data['slideInfo'] = $this->slideModel->getFindSlideDataStatus($condition);
		$this->assign('data', $data);
		$this->display('edit_slide');
	}

	function edit_slide_sub()
    {
        $condition['id'] = array('eq', intval($_POST['id']));
		if(!empty($_FILES['file']['name']))
        {
			$info = $this->upload_file();
			$saveData['img_url'] = $info['prefix'].$info[0]['savename'];
		}

        $_POST = $this->zaddslashes($_POST);

        $saveData['href_url'] = $_POST['href_url'];
        $saveData['alt'] = $_POST['alt'];
        $saveData['target'] = $_POST['target'];

        $status = $this->slideModel->getSaveSlideDataStatus($condition, $saveData);
		if($status)
        {
			$this->assign('waitSecond', 3);
			$this->success('更新幻灯片成功!');
		}
        else
        {
			$this->error('更新幻灯片失败!');
		}
	}

	function del_slide()
    {
        $condition['id'] = array('eq', intval($_GET['id']));
        $slideFind = $this->slideModel->getFindSlideDataStatus($condition);
		$picPath = './Public/images/banna/'.$slideFind['img_url'];

        if(is_file($picPath))
        {
			@unlink($picPath);
			unset($picPath);
		}

        $deleteId = intval($_GET['id']);
        $deleteStatus = $this->slideModel->getDeleteSlideDataStatus($deleteId);
		if($deleteStatus)
        {
			$this->assign('waitSecond', 3);
			$this->success('删除幻灯片成功!');
		}
        else
        {
			$this->error('删除幻灯片失败!');
		}
	}

	private function upload_file()
    {
		import('@.Org.UploadFile');
		
		 $this->getSafeName($_FILES['file']['name']);
		
		$prefix = 'banna_';  //上传后的图片前缀
		
		$upload = new UploadFile();
		$upload->maxSize = '1000000';  //是指上传文件的大小，默认为-1,不限制上传文件大小bytes
		$upload->savePath = './Public/images/banna/';       //上传保存到什么地方？路径建议大家已主文件平级目录或者平级目录的子目录来保存
		$upload->saveRule = uniqid;    //上传文件的文件名保存规则  time uniqid  com_create_guid
		$upload->thumbPrefix = $prefix;
		$upload->uploadReplace = true;     //如果存在同名文件是否进行覆盖
		$upload->allowExts = array('jpg','jpeg','png','gif');     //准许上传的文件后缀
		$upload->allowTypes = array('image/png','image/jpg','image/pjpeg','image/gif','image/jpeg');  //检测mime类型
		$upload->thumb = true;   //是否开启图片文件缩略
		$upload->thumbMaxWidth = '500';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
		$upload->thumbMaxHeight = '200';	//最大高度
		$upload->thumbRemoveOrigin = 1;  //如果生成缩略图，是否删除原图

		if($upload->upload())
        {
				$info=$upload->getUploadFileInfo();
				$info['prefix'] = $prefix;
				return $info;
		}
        else
        {
			$this->error($upload->getErrorMsg());
		}
	}
}