<?php
header("Content-type:text/html; charset=utf-8");
class GroupAction extends QxAction
{
	function index()
    {
		$this->feifa();
		$this->display();
	}
}
?>