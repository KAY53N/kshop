<?php
ini_set('display_errors', 'Off');
ini_set('magic_quotes_gpc', 'NO');
date_default_timezone_set('PRC');

$sysArr = array(
    'APP_GROUP_LIST'=>'Home,Admin',
    'DEFAULT_GROUP' =>'Home',
    'URL_CASE_INSENSITIVE'  =>    true,           // URL地址是否不区分大小写
    'APP_DEBUG'             =>    true,          // 是否开启调试模式
    'TMPL_L_DELIM'          =>    '<{',           // 模板开始定界符
    'TMPL_R_DELIM'          =>    '}>',           // 模板结束定界符
    'URL_MODEL'             =>    2,              // URL访问模式
    'LANG_SWITCH_ON'        =>    false,          // 默认关闭多语言包功能
    'LANG_AUTO_DETECT'      =>    false,          // 自动侦测语言 开启多语言功能后有效
    'TMPL_DETECT_THEME'     =>    false,          // 自动侦测模板主题
    'DATA_CACHE_TIME'	    =>    -1900,           // 数据缓存有效期
    'DATA_CACHE_TYPE'		=>    'File',         // 数据缓存类型
    'TMPL_CACHE_ON'         =>    false,          // 默认开启模板编译缓存 false 的话每次都重新编译模板
    'ACTION_CACHE_ON'       =>    false,          // 默认关闭Action 缓存
    'HTML_CACHE_ON'         =>    false,          // 默认关闭静态缓存
    'TMPL_STRIP_SPACE'      =>    false,          // 是否去除模板文件里面的html空格与换行
    'DB_FIELD_CACHE'        =>    false,
	'MAIL_ADDRESS'          =>    'your@126.com', // 邮箱地址
	'MAIL_SMTP'             =>    'smtp.126.com', // 邮箱SMTP服务器
	'MAIL_LOGINNAME'        =>    'your@126.com', // 邮箱登录帐号
	'MAIL_PASSWORD'         =>    'your password' // 邮箱密码
);

$databaseArr = array(
    'DB_TYPE'    => 'mysql',
    'DB_HOST'    => '127.0.0.1',
    'DB_NAME'    => 'kshop',
    'DB_USER'    => 'root',
    'DB_PWD'     => 'xjt299',
    'DB_PORT'    => '3306',
    'DB_PREFIX'  => 'shop_'
);

$aliPayConfig = array(
    'aliPayConfig' => array(
        'partner'        => '2088402894270141',
        'key'            => 'phj5ecsel8s73afva50tstjpuhkw7upg',
        'sign_type'      => strtoupper('MD5'),
        'input_charset'  => strtolower('utf-8'),
        'transport'      => 'http',
        'seller_email'   => '71927735@qq.com',
        'notify_url'     => 'http://www.xujiantao.com/works/lvsenshop/Pay/notifyUrl',
		'return_url'     => 'http://www.xujiantao.com/works/lvsenshop/Pay/returnUrl'
    )
);

$language = include APP_PATH.'Message/Language.php';

return array_merge($sysArr, $databaseArr, $language, $aliPayConfig);