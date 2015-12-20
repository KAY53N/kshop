<?php
class QxAction extends Action {
	function feifa()
    {
        if(Cookie::get('feifa_admin') != 'passageway_admin')
        {
		    $this->redirect('/login/index');
		}
		/*
		//session 验证是否登录
    	if($_SESSION["feifa"] != "passageway"){
    		$this->redirect('admin.php/index/login');
    	}
    	*/
	}


    function zaddslashes($string, $force = 0, $strip = FALSE)
    {
        if (!defined('MAGIC_QUOTES_GPC'))
        {
            define('MAGIC_QUOTES_GPC', '');
        }

        if (!MAGIC_QUOTES_GPC || $force)
        {
            if (is_array($string)) {
                foreach ($string as $key => $val)
                {
                    $string[$key] = $this->zaddslashes($val, $force, $strip);
                }
            }
            else
            {
                $string = ($strip ? stripslashes($string) : $string);
                $string = htmlspecialchars($string);
            }
        }
        return $string;
    }
    
    function getSafeName(&$name)
    {
        $name = str_replace(array('"', '<', '>'), '', $name);
        $name = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $name);
        $info = pathinfo($name);
        $name = substr($info['basename'], 1);
        return isset($info['extension']) ? strtolower($info['extension']) : '';
    }
}