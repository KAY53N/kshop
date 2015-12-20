<?php
class CommonAction extends Action {

	function feifa()
    {
        if(Cookie::get('feifa_home') != 'passageway_home')
        {
		    $this->error(C('ERROR_INVALID_ARGUMENT'));
		}
	}

    function verify()
    {
        import('@.ORG.Image');
        Image::buildImageVerify(3, 1, 'gif', 145, 25, 'verify');
    }

    function logResult($word='')
    {
        $fp = fopen("log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
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
    
    //发送邮件类
    function SendMail($address,$title,$message)
    {
        vendor('PHPMailer.class#phpmailer');

        $mail=new PHPMailer();

        // 设置PHPMailer使用SMTP服务器发送Email
        $mail->IsSMTP(true);

        // 设置邮件的字符编码，若不指定，则为'UTF-8'
        $mail->CharSet='UTF-8';

        // 添加收件人地址，可以多次使用来添加多个收件人
        $mail->AddAddress($address);

        // 设置邮件正文
        $mail->Body=$message;

        // 设置html
        $mail->IsHTML(true);

        // 设置邮件头的From字段。
        $mail->From=C('MAIL_ADDRESS');

        // 设置发件人名字
        $mail->FromName='Kshop数码,数码相机,手机商城,电脑商城,全国货到付款';

        // 设置邮件标题
        $mail->Subject=$title;

        // 设置SMTP服务器。
        $mail->Host=C('MAIL_SMTP');

        // 设置为"需要验证"
        $mail->SMTPAuth=true;

        // 设置用户名和密码。
        $mail->Username=C('MAIL_LOGINNAME');
        $mail->Password=C('MAIL_PASSWORD');

        // 发送邮件。
        return($mail->Send());
    }
}