<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-18
 * Time: 21:18
 */
class BaseModel extends RelationModel
{
    private static $_newsModel, $_sliderModel, $_goodsModel, $_cartModel, $_sortModel,
                   $_couponModel, $_userModel, $_userinfoModel, $_ordersModel, $_commentModel,
                   $_shopsetModel, $_adminModel, $_webInfo, $_webFooterNews, $_goodsSort;

    public function tableNews()
    {
        if(!(self::$_newsModel instanceof self))
        {
            self::$_newsModel = new Model('News');
        }
        return self::$_newsModel;
    }

    public function tableSlide()
    {
        if(!(self::$_sliderModel instanceof self))
        {
            self::$_sliderModel = new Model('Slide');
        }
        return self::$_sliderModel;
    }

    public function tableGoods()
    {
        if(!(self::$_goodsModel instanceof self))
        {
            self::$_goodsModel = new Model('Goods');
        }
        return self::$_goodsModel;
    }

    public function tableCart()
    {
        if(!(self::$_cartModel instanceof self))
        {
            self::$_cartModel = new Model('Cart');
        }
        return self::$_cartModel;
    }

    public function tableSort()
    {
        if(!(self::$_sortModel instanceof self))
        {
            self::$_sortModel = new Model('Sort');
        }
        return self::$_sortModel;
    }

    public static function tableCoupon()
    {
        if(!(self::$_couponModel instanceof self))
        {
            self::$_couponModel = new Model('Coupon');
        }
        return self::$_couponModel;
    }

    public function tableUser()
    {
        if(!(self::$_userModel instanceof self))
        {
            self::$_userModel = new Model('User');
        }
        $userModel = self::$_userModel;

        $userRelation = $userModel->switchModel('Relation');
        $link = array(
            'userinfo'=>array(
                'mapping_type' => HAS_ONE,
                'class_name'   => 'userinfo',
                'mapping_name' => 'userinfo',
                'foreign_key'  => 'user_id',
                'as_fields'    => 'id:userinfo_id,name,gender,birth_date,sel0,sel1,sel2,site,zip_code,mobile,phone,question,answer'
            ),
        );
        $userRelation->setProperty('_link', $link);
        return $userRelation;
    }

    public function tableUserinfo()
    {
        if(!(self::$_userinfoModel instanceof self))
        {
            self::$_userinfoModel = new Model('Userinfo');
        }
        return self::$_userinfoModel;
    }

    public function tableOrders()
    {
        if(!(self::$_ordersModel instanceof self))
        {
            self::$_ordersModel = new Model('Orders');
        }
        return self::$_ordersModel;
    }

    public function tableComment()
    {
        if(!(self::$_commentModel instanceof self))
        {
            self::$_commentModel = new Model('Comment');
        }
        return self::$_commentModel;
    }

    public function tableShopSet()
    {
        if(!(self::$_shopsetModel instanceof self))
        {
            self::$_shopsetModel = new Model('Shop_set');
        }
        return self::$_shopsetModel;
    }

    public function tableAdmin()
    {
        if(!(self::$_adminModel instanceof self))
        {
            self::$_adminModel = new Model('Admin');
        }
        return self::$_adminModel;
    }

    public function webInfo()
    {
        if(empty(self::$_webInfo))
        {
            self::$_webInfo = self::tableShopSet()->find();
        }
        return self::$_webInfo;
    }

    public function webFooterNews()
    {
        if(empty(self::$_webFooterNews))
        {
            self::$_webFooterNews = self::tableNews()->order('id asc')->select();
        }
        return self::$_webFooterNews;
    }

    public function goodsSort()
    {
        if(empty(self::$_goodsSort))
        {
            $result['sort_list'] = $this->tableSort()->field('id,name,pid,path,concat(path,"-",id) as bpath')->order('bpath')->select();
            $result['one_list'] = $this->tableSort()->where('pid = 0')->order('id asc')->select();
            self::$_goodsSort = $result;
        }
        return self::$_goodsSort;
    }

    public function pageNavgation($count, $pageSize = 10)
    {
        import('@.ORG.Page');
        $page = new Page($count, $pageSize);
        $page->setConfig('header', '条记录');
        $page->setConfig('theme', '<span class="pagestyle" style="color:blue">共%totalRow%%header%</span> <span class="pagestyle">当前%nowPage%&nbsp;/&nbsp;%totalPage% 页</span> %first% %upPage%  %linkPage% %downPage% %end%');
        return $page;
    }
}