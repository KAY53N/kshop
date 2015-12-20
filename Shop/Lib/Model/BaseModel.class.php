<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-18
 * Time: 21:18
 */
class BaseModel extends RelationModel
{
    private static $_goodsModel, $_userModel, $_webInfo, $_webFooterNews, $_goodsCategory;
    
    public function tableNews()
    {
        return M('News');
    }

    public function tableSlide()
    {
        return M('Slide');
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
        return M('Cart');
    }

    public function tableCategory()
    {
        return M('Category');
    }

    public function tableCoupon()
    {
        return M('Coupon');
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
        return M('Userinfo');
    }

    public function tableOrders()
    {
        return M('Orders');
    }

    public function tableComment()
    {
        return M('Comment');
    }

    public function tableShopSet()
    {
        return M('Shop_set');
    }

    public function tableAdmin()
    {
        return M('Admin');
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

    public function goodsCategory()
    {
        if(empty(self::$_goodsCategory))
        {
            $result['category_list'] = $this->tableCategory()->field('id, name, pid, path, concat(path,"-",id) as bpath')->order('bpath')->select();
            $result['one_list'] = $this->tableCategory()->where('pid = 0')->order('id asc')->select();
            self::$_goodsCategory = $result;
        }
        return self::$_goodsCategory;
    }

    public function pageNavgation($count, $pageSize = 10)
    {
        import('@.ORG.Page');
        $page = new Page($count, $pageSize);
        $page->setConfig('header', '条记录');
        $page->setConfig('theme', '<span class="pagestyle" style="color:blue">共%totalRow%%header%</span> <span class="pagestyle">当前%nowPage%&nbsp;/&nbsp;%totalPage% 页</span> %first% %upPage%  %linkPage% %downPage% %end%');
        return $page;
    }
    
    public function logResult($word='')
    {
        $fp = fopen("log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}