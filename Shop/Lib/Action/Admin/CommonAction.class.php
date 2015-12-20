<?php
class CommonAction extends Action {
	function index()
    {

	}
	
	function verify()
    {
		import('@.ORG.Image');
        Image::buildImageVerify(3, 1, 'gif', 145, 25, 'verify');
	}	

}