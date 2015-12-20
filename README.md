#Kshop

演示地址：http://www.xujiantao.com/works/lvsenshop

早期托管地址：http://git.oschina.net/71927735/Kshop

### 说明
整站DIV+CSS+jQuery+Thinkphp，仿的 2012 年的绿森数码，后台仿的 Ecshop ，并且重新手写了所有前端页面，功能设计和前后端独自完成<br><br>
主要的功能：支持支付宝支付、会员管理、积分管理、公告管理、幻灯片管理、分类管理、商品管理、评论管理、优惠券管理、订单管理、商城设置
<hr />

### 安装
1、PHP版本请使用 ≥ 5.3 并且 ≤ 5.6 

2、修改配置文件数据库相关并将表结构文件 kshop.sql 导入至Mysql，如果需要测试数据下载请到 [Tag](https://github.com/kaysen820/kshop/releases/tag/testPackageFor1.4) 中下载

3、默认为 REWRITE 模式，需要开启 Apache 或 Nginx 的 REWRITE 并配置 vhost；如果使用的是 IIS 则需要自行将 .htaccess 转换成 IIS 的形式

4、后台访问地址为你配置的vhost地址后加/Admin (如：`http://loc.kshop.com/Admin`)，用户名和密码：admin admin
<hr />

### 历史版本
___Kshop-v1.4___
```PHP
更改Runtime路径
配置默认为不缓存所有数据并开启DEBUG
修复部分命名错误的类、方法、变量
支付和订单部分的数据库操作加了悲观行锁
分离测试数据到 Tag 中
修复上传文件安全问题
```

___Kshop-v1.3___
```PHP
增加模块分组、关联查询、复合查询、分离Model层
大部分功能进行重写，时间有限，还有一小部分BUG未修复
增加针对TP2.1版本的URL安全漏洞补丁
```

___Kshop-Revision-v1.2___
```PHP
修复XSS和SQL注入过滤
调整了数据库操作方面和分页问题
处理了部分物品、购物车、订单所属权限问题
```

___Kshop-v1.1___
```PHP
2013.11发布 1.1 版本并开源，修复BUG若干，重新整理前后端代码格式
```

___Kshop-v1.0___
```PHP
2012-1-21完成第一版本，仿的当时的绿森数码，后台仿的Ecshop
所有前端代码重新手写，后端根据我个人的设计和思路独自完成
集成支付宝支付功能
```
<hr />

### 建议
建议增加一个全局的模型的自动验证
<hr />

### 其他
下一版本主要是脱离PHP框架开发的功能更强大的B2C独立网店系统

可以进行安装、多模板、插件功能、多种支付方式、分析和报表、RBAC等...

如果有感兴趣的朋友可以Email我

如要正式商用此源码或修改后商用的请Email通知本人

Email: kaysen820@gmail.com
