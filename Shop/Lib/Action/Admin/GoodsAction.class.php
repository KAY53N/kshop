<?php
class GoodsAction extends QxAction
{
    protected $goodsModel, $categoryModel;
    public function _initialize()
    {
        $this->feifa();
        $this->goodsModel = D('Admin.Goods');
        $this->categoryModel = D('Admin.Category');
    }

	function index()
    {
		Load('extend');
        $data = $this->goodsModel->getGoodsListData();
        $this->assign('data', $data);
		$this->display();
	}

	function add_goods()
    {
        $data = $this->categoryModel->getCategoryListData();
		$this->assign('data', $data);
		$this->display('add_goods');
	}

	function upfile()
    {
		if(empty($_FILES))
        {
			$this->error('必须选择上传文件');
		}
        else
        {
            $info = $this->up();
            $_POST  = $this->zaddslashes($_POST);
            if(isset($info)){
            	//获取到上传的图片名
                empty($info[0]['savename']) ? 0 : $_POST['pic_one'] = $info[0]['savename'];
                empty($info[1]['savename']) ? 0 : $_POST['pic_two'] = $info[1]['savename'];
                empty($info[2]['savename']) ? 0 : $_POST['pic_three'] = $info[2]['savename'];
                empty($info[3]['savename']) ? 0 : $_POST['pic_four'] = $info[3]['savename'];

                //获取一个上传图片后的路径
                for($i=0; $i<count($info); $i++)
                {
                	if(!empty($info[$i]['savepath']))
                    {
                		$_POST['path'] = $info[$i]['savepath'];
                		break;
                	}
                }

                //如果为空则生成订单号/货号
                if(empty($_POST['item_No']))
                {
		            $_POST['item_No'] = 'N'.date('Ymd') . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
                }

                //插入数据库数据
                if($this->goodsModel->getAddGoodsDataStatus($_POST))
                {
                	$this->assign('waitSecond', 3);
                    $this->success('添加商品成功!');
                }
                else
                {
                    $this->error('添加上品失败!');
                }

            }
            else
            {
                $this->error('上传失败');
            }
		}
	}

	function up()
    {
        import('@.Org.UploadFile');
        
        foreach($_FILES as $k=>$v)
        {
            $this->getSafeName($_FILES['file']['name'][0]);
        }
        
		$upload = new UploadFile();
		$upload->maxSize = '3000000';  //是指上传文件的大小，默认为-1,不限制上传文件大小bytes
		$upload->savePath = './Public/Uploads/image/img/goods_pic/';       //上传保存到什么地方？路径建议大家已主文件平级目录或者平级目录的子目录来保存
		$upload->saveRule = uniqid;    //上传文件的文件名保存规则  time uniqid  com_create_guid  uniqid
		$upload->uploadReplace = true;     //如果存在同名文件是否进行覆盖
		$upload->allowExts = array('jpg','jpeg','png','gif');     //准许上传的文件后缀
		$upload->allowTypes = array('image/png','image/jpg','image/pjpeg','image/gif','image/jpeg');  //检测mime类型
		$upload->thumb = true;   //是否开启图片文件缩略
		$upload->thumbMaxWidth = '100,310,900';  //以字串格式来传，如果你希望有多个，那就在此处，用,分格，写上多个最大宽
		$upload->thumbMaxHeight = '100,310,900';	//最大高度
		$upload->thumbPrefix = 'x_,z_,d_';//缩略图文件前缀
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
	
	//编辑商品
	function edit_goods()
    {
        $goodsCondition['id'] = array('eq', intval($_GET['id']));
        $data['categoryInfo'] = $this->categoryModel->getCategoryListData();
        $data['goodsInfo'] = $this->goodsModel->getFindGoodsData($goodsCondition);
		$this->assign('data', $data);
		$this->display("edit_goods");
	}

	//编辑商品处理
	function edit_goods_sub()
    {
        $_POST = $this->zaddslashes($_POST);
        $findGoodsCondition['id'] = array('eq', intval($_POST['where_id']));
        $list = $this->goodsModel->getFindGoodsData($findGoodsCondition);
        //删除更新以前的和数据库关联的旧图
        $pic[0] = $list['pic_one'];
        $pic[1] = $list['pic_two'];
        $pic[2] = $list['pic_three'];
        $pic[3] = $list['pic_four'];

        $data = array();
        for($i=0; $i<count($pic); $i++)
        {
        	if(!empty($_FILES['file']['name'][$i]))
            {
			    @unlink($list['path'].'x_'.$pic[$i]);
			    @unlink($list['path'].'z_'.$pic[$i]);
			    @unlink($list['path'].'d_'.$pic[$i]);
			    $i == 0 ? $data[].=$i : 0;
			    $i == 1 ? $data[].=$i : 0;
			    $i == 2 ? $data[].=$i : 0;
			    $i == 3 ? $data[].=$i : 0;
        	}
        }

        //如果有上传的图片就让他上传并更新进数据库
        for($i=0; $i<count($_FILES['file']['name']); $i++)
        {
        	if(!empty($_FILES['file']['name'][$i]))
            {
            	$info = $this->up();
            	break;
            }
        }
            
        //获取到上传的图片名
        for($i=0; $i<count($data); $i++)
        {
        	$info[$i]['key'] == 0 ? $_POST['pic_one'] = $info[$i]['savename'] : 0;
            $info[$i]['key'] == 1 ? $_POST['pic_two'] = $info[$i]['savename'] : 0;
            $info[$i]['key'] == 2 ? $_POST['pic_three'] = $info[$i]['savename'] : 0;
            $info[$i]['key'] == 3 ? $_POST['pic_four'] = $info[$i]['savename'] : 0;
        }

        //更新数据库数据
        if($this->goodsModel->getSaveGoodsDataStatus($findGoodsCondition, $_POST))
        {
            $this->assign('waitSecond', 3);
           	$this->success('更新商品成功!');
        }
        else
        {
            $this->error('您没有修改数据,更新商品失败!');
        }
	}

	function del_goods()
    {
		import('ORG.Io.Dir');
		isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
        $deleteId = $this->zaddslashes($deleteId);
        $findCondition['id'] = array('eq', $deleteId);
        $list = $this->goodsModel->getFindGoodsData($findCondition);
        //删除与数据库关联的图片
		$pic[0] = $list['pic_one'];
        $pic[1] = $list['pic_two'];
        $pic[2] = $list['pic_three'];
        $pic[3] = $list['pic_four'];
        for($i=0; $i<count($pic); $i++)
        {
		    if(isset($pic))
            {
			    @unlink($list['path'].'x_'.$pic[$i]);
			    @unlink($list['path'].'z_'.$pic[$i]);
			    @unlink($list['path'].'d_'.$pic[$i]);
		    }
        }

		if($this->goodsModel->getDeleteGoodsDataStatus($deleteId))
        {
			$this->assign('waitSecond', 3);
			$this->success('删除商品成功!');
		}
        else
        {
			$this->error('删除商品失败!');
		}
	}

    //搜索商品
    function search_goods()
    {
        Load('extend');
        $keyword = $this->zaddslashes($keyword);
        $condition['title'] = array('like', '%'.$keyword.'%');
        $condition['title_info'] = array('like', '%'.$keyword.'%');
        $condition['sell_price'] = array('like', '%'.$keyword.'%');
        $condition['_logic'] = 'or';
        $data = $this->goodsModel->getSearchGoodsData($condition);
        $this->assign('data', $data);
        $this->display('index');
    }

}
?>