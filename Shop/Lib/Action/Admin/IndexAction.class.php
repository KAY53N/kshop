<?php
class IndexAction extends QxAction
{
    protected $indexModel;
    public function _initialize()
    {
        $this->feifa();
        $this->indexModel = D('Admin.Index');
    }

    function index()
    {
        $this->display();
    }
    
    function topFrame()
    {
    	$this->display('Public/topFrame');
    }
    
    function menuFrame()
    {
    	$this->display('Public/menuFrame');
    }

    function hideFrame()
    {
    	$this->display('Public/hideFrame');
    }
    
    function mainFrame()
    {
        $data = $this->indexModel->getMainFrameData();
        $this->assign('data', $data);
    	$this->display('Public/mainFrame');
    }
    
    function footFrame()
    {
    	$this->display('Public/footFrame');
    } 
}