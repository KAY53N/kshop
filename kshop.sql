/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.6.24 : Database - kshop
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`kshop` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `kshop`;

/*Table structure for table `shop_admin` */

DROP TABLE IF EXISTS `shop_admin`;

CREATE TABLE `shop_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `logintime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `indexname` (`username`,`password`),
  KEY `logintime` (`logintime`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='后台登陆';

/*Table structure for table `shop_cart` */

DROP TABLE IF EXISTS `shop_cart`;

CREATE TABLE `shop_cart` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pic` varchar(200) NOT NULL,
  `path` varchar(200) NOT NULL,
  `goods_name` varchar(300) NOT NULL COMMENT '商品名字',
  `item_No` varchar(50) DEFAULT NULL COMMENT '货号',
  `points` int(11) NOT NULL COMMENT '积分',
  `market_price` float NOT NULL COMMENT '市场价格',
  `sell_price` float NOT NULL COMMENT '销售价格',
  `buy_num` int(11) NOT NULL COMMENT '购买数量',
  `total_price` float NOT NULL COMMENT '总金额',
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`id`),
  KEY `item_No` (`item_No`),
  KEY `market_price` (`market_price`),
  KEY `sell_price` (`sell_price`),
  KEY `user_id` (`user_id`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_category` */

DROP TABLE IF EXISTS `shop_category`;

CREATE TABLE `shop_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `path` varchar(400) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `path` (`path`(333))
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_comment` */

DROP TABLE IF EXISTS `shop_comment`;

CREATE TABLE `shop_comment` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(200) NOT NULL COMMENT '评论内容',
  `contact` varchar(30) NOT NULL COMMENT '联系方式',
  `author` varchar(30) NOT NULL COMMENT '作者',
  `add_date` int(50) NOT NULL COMMENT '评论时间',
  `reply` varchar(200) NOT NULL COMMENT '回复内容',
  `reply_date` int(11) NOT NULL COMMENT '回复时间',
  `show` int(1) NOT NULL COMMENT '是否显示',
  `goods_id` int(11) NOT NULL COMMENT '评论商品id',
  PRIMARY KEY (`id`),
  KEY `contact` (`contact`),
  KEY `author` (`author`),
  KEY `add_date` (`add_date`),
  KEY `reply_date` (`reply_date`),
  KEY `show` (`show`),
  KEY `goods_id` (`goods_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_coupon` */

DROP TABLE IF EXISTS `shop_coupon`;

CREATE TABLE `shop_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '名称',
  `discount` float NOT NULL COMMENT '折扣',
  `coupon_code` varchar(50) NOT NULL COMMENT '优惠码',
  `validity_date` int(11) NOT NULL COMMENT '有效期',
  `user_id` int(11) NOT NULL COMMENT '被赠送的用户的id',
  `status` char(2) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `discount` (`discount`),
  KEY `validity_date` (`validity_date`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_goods` */

DROP TABLE IF EXISTS `shop_goods`;

CREATE TABLE `shop_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '商品标题',
  `title_info` varchar(300) NOT NULL COMMENT '商品标题2',
  `item_No` varchar(100) NOT NULL COMMENT '货号',
  `brand` varchar(100) NOT NULL COMMENT '品牌',
  `weight` varchar(100) NOT NULL COMMENT '重量',
  `inventory` varchar(200) NOT NULL COMMENT '库存',
  `market_price` varchar(200) NOT NULL COMMENT '市场价格',
  `points` int(11) NOT NULL COMMENT '积分',
  `sell_price` varchar(200) NOT NULL COMMENT '销售价格',
  `pic_one` varchar(200) NOT NULL COMMENT '商品展示图1',
  `pic_two` varchar(200) NOT NULL COMMENT '商品展示图2',
  `pic_three` varchar(200) NOT NULL COMMENT '商品展示图3',
  `pic_four` varchar(200) NOT NULL COMMENT '商品展示图4',
  `path` varchar(200) NOT NULL COMMENT '商品展示图路径',
  `details` text NOT NULL COMMENT '商品详情',
  `putaway` int(1) NOT NULL COMMENT '上架',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `item_No` (`item_No`),
  KEY `brand` (`brand`),
  KEY `inventory` (`inventory`),
  KEY `points` (`points`),
  KEY `sell_price` (`sell_price`),
  KEY `putaway` (`putaway`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_news` */

DROP TABLE IF EXISTS `shop_news`;

CREATE TABLE `shop_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `news_title` varchar(32) NOT NULL,
  `news_date` varchar(11) NOT NULL,
  `news_con` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `news_date` (`news_date`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='首页新闻';

/*Table structure for table `shop_orders` */

DROP TABLE IF EXISTS `shop_orders`;

CREATE TABLE `shop_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '订单标题',
  `title_info` varchar(300) NOT NULL,
  `order_No` char(100) NOT NULL COMMENT '订单号',
  `alipay_No` char(100) NOT NULL,
  `details` varchar(200) NOT NULL COMMENT '详情',
  `price` float NOT NULL COMMENT '价格',
  `more_price` char(200) NOT NULL COMMENT '每个商品的价格',
  `buy_num` char(100) NOT NULL COMMENT '每个商品数量',
  `points` char(200) NOT NULL COMMENT '每个商品积分',
  `path` varchar(200) NOT NULL COMMENT '图片路径',
  `order_pic` varchar(200) NOT NULL COMMENT '每个商品图片名',
  `remark` varchar(100) NOT NULL,
  `add_date` int(11) NOT NULL COMMENT '创建时间',
  `goods_id` char(200) NOT NULL COMMENT '每个商品id',
  `user_id` int(11) NOT NULL COMMENT '会员ID',
  `status` char(1) NOT NULL DEFAULT '0' COMMENT '交易状态',
  PRIMARY KEY (`id`),
  KEY `order_No` (`order_No`),
  KEY `alipay_No` (`alipay_No`),
  KEY `price` (`price`),
  KEY `buy_num` (`buy_num`),
  KEY `add_date` (`add_date`),
  KEY `goods_id` (`goods_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_shop_set` */

DROP TABLE IF EXISTS `shop_shop_set`;

CREATE TABLE `shop_shop_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `web_name` varchar(20) NOT NULL COMMENT '商店名称',
  `web_title` varchar(100) NOT NULL COMMENT '商店标题',
  `web_descripion` varchar(300) NOT NULL COMMENT '商店描述',
  `web_keyword` varchar(200) NOT NULL COMMENT '商店关键字',
  `work_time` varchar(20) NOT NULL COMMENT '工作时间',
  `reply_time` varchar(20) NOT NULL COMMENT '回复时间',
  `icp` varchar(20) NOT NULL COMMENT '备案号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_slide` */

DROP TABLE IF EXISTS `shop_slide`;

CREATE TABLE `shop_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `href_url` text NOT NULL,
  `alt` varchar(20) NOT NULL,
  `target` varchar(10) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `target` (`target`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*Table structure for table `shop_user` */

DROP TABLE IF EXISTS `shop_user`;

CREATE TABLE `shop_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `available_funds` float NOT NULL COMMENT '可用资金',
  `blocked_funds` float NOT NULL COMMENT '冻结资金',
  `grade_points` float NOT NULL COMMENT '等级积分',
  `consumption_points` float NOT NULL COMMENT '消费积分',
  `sum_points` float NOT NULL COMMENT '总积分',
  `add_time` int(10) unsigned NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `sum_points` (`sum_points`),
  KEY `available_funds` (`available_funds`),
  KEY `indexname` (`username`,`password`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='前台会员';

/*Table structure for table `shop_userinfo` */

DROP TABLE IF EXISTS `shop_userinfo`;

CREATE TABLE `shop_userinfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '关联用户的id',
  `name` varchar(11) NOT NULL COMMENT '姓名',
  `gender` enum('男','女') NOT NULL DEFAULT '男' COMMENT '性别',
  `birth_date` varchar(30) NOT NULL COMMENT '出生日期',
  `sel0` varchar(20) NOT NULL COMMENT '省',
  `sel1` varchar(20) NOT NULL COMMENT '市',
  `sel2` varchar(20) NOT NULL COMMENT '县',
  `site` varchar(50) NOT NULL COMMENT '地址',
  `zip_code` varchar(7) NOT NULL COMMENT '邮编',
  `mobile` varchar(11) NOT NULL COMMENT '手机',
  `phone` varchar(15) NOT NULL COMMENT '固定电话',
  `question` varchar(20) NOT NULL COMMENT '提问',
  `answer` varchar(20) NOT NULL COMMENT '回答',
  PRIMARY KEY (`id`),
  KEY `sel0` (`sel0`),
  KEY `sel1` (`sel1`),
  KEY `sel2` (`sel2`),
  KEY `name` (`name`),
  KEY `user_id` (`user_id`),
  KEY `mobile` (`mobile`),
  KEY `site` (`site`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='前台会员关联信息';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
