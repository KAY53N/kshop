<?php
class CommentAction extends QxAction
{
    protected $commentModel;
    public function _initialize()
    {
        $this->feifa();
        $this->commentModel = D('Admin.Comment');
    }

	function index()
    {
        $data = $this->commentModel->getCommentListData();
        $this->assign('data', $data);
		$this->display();
	}

	function add_comment()
    {
		$this->display();
	}

	function add_comment_sub()
    {
        $_POST = $this->zaddslashes($_POST);
		$_POST['add_date'] = time($_POST['add_date']);
		$_POST['reply_date'] = time($_POST['reply_date']);
		if($this->commentModel->getAddCommentDataStatus($_POST))
        {
			$this->success('添加商品评论成功!');

		}
        else{
			$this->error('添加商品评论失败!');
		}
	}


	function edit_comment()
    {
        $commentCondition['id'] = array('eq', intval($_GET['id']));
		$data['commentInfo'] = $this->commentModel->getFindCommentData($commentCondition);

        $goodsTitleCondition['id'] = array('eq', intval($data['commentInfo']['goods_id']));
        $data['goodsTitle'] = $this->commentModel->getGoodsTitleData($goodsTitleCondition);

		$this->assign('data', $data);
		$this->display('edit_comment');
	}

	function edit_comment_sub()
    {
		$comment = M('comment');
		$_POST['add_date'] = time($_POST['add_date']);
		$_POST['reply_date'] = time($_POST['reply_date']);
		if($comment->where('id ='.intval($_POST['where_id']))->save($_POST))
        {
			$this->success('修改评论成功!');
		}
        else
        {
			$this->error('修改评论失败!');
		}
	}

	function del_comment()
    {
		isset($_GET) ? $deleteId = implode(',', $_GET) : 0;
		$deleteId = $this->zaddslashes($deleteId);
        if($this->commentModel->getDeleteCommentDataStatus($deleteId))
        {
			$this->assign('waitSecond', 3);
			$this->success('删除评论成功!');
		}
        else
        {
			$this->error('删除评论失败!');
		}
	}
	
}
?>