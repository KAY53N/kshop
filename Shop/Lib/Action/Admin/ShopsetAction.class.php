<?php
class ShopsetAction extends QxAction
{
    protected $shopsetModel;
    public function _initialize()
    {
        $this->feifa();
        $this->shopsetModel = D('Admin.Shopset');
    }

	function index()
    {
		$data['info'] = $this->shopsetModel->getShopSetInfoData();
		$this->assign('data', $data);
		$this->display();
	}

    function editShopSet()
    {
        $condition['id'] = array('eq', intval($_POST['conditionId']));
        unset($_POST['conditionId']);

        if(!empty($_FILES['file']['name']))
        {
			$upFileStatus = $this->up();
		}

        $_POST = $this->zaddslashes($_POST);
        $saveDataStatus = $this->shopsetModel->getSaveShopSetInfoStatus($condition, $_POST);
        if(!empty($saveDataStatus) || !empty($upFileStatus))
        {
        	$this->success('更新成功!');
        }
        else
        {
        	$this->error('更新失败!您未更改内容或其他原因!');
        }
	}

	function up()
    {
        import('@.Org.UploadFile');
        
        $this->getSafeName($_FILES['file']['name']);
		
		$upload = new UploadFile();
		$upload->maxSize = '3000000';  //是指上传文件的大小，默认为-1,不限制上传文件大小bytes
		$upload->savePath = './Public/images/';       //上传保存到什么地方？路径建议大家已主文件平级目录或者平级目录的子目录来保存
		$upload->saveRule = "logo";    //上传文件的文件名保存规则  time uniqid  com_create_guid  uniqid
		$upload->uploadReplace = true;     //如果存在同名文件是否进行覆盖
        $upload->allowExts = array('gif');     //准许上传的文件后缀
        $upload->allowTypes = array('image/gif');
		$upload->thumb = true;   //是否开启图片文件缩略
		$upload->thumbMaxWidth = '210';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
		$upload->thumbMaxHeight = '82';	//最大高度
		$upload->thumbPrefix = 'kshop_';//缩略图文件前缀
		$upload->thumbRemoveOrigin = 1;  //如果生成缩略图，是否删除原图		
		if($upload->upload())
        {
			$info = $upload->getUploadFileInfo();
			return $info;
		}
        else
        {
			$this->error($upload->getErrorMsg());
		}
	}
}
?>