<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-18
 * Time: 21:13
 */
class IndexModel extends BaseModel
{
    protected $thisGoodsCategory;
    public function _initialize()
    {
        $this->thisGoodsCategory = $this->goodsCategory();
    }

    public function getIndexData($condition)
    {
        $cart_num = $this->tableCart()->where($condition)->count();
        Cookie::set('cart_num', empty($cart_num) ? 0 : $cart_num, 60*60*24);
        $result['slideList'] = $this->tableSlide()->order('id asc')->select();
        $result['newsList'] = $this->tableNews()->order('id desc')->field('id,news_title')->select();
        $result['shopList'] = $this->tableGoods()->where('putaway=1')->order("id desc")->limit('0,8')->select();
        $result['motorola'] = $this->tableGoods()->where('brand=100 AND putaway=1')->order('id desc')->select();
        $result['htc'] = $this->tableGoods()->where('brand=103 AND putaway=1')->order('id desc')->select();
        $result['lenovo'] = $this->tableGoods()->where('brand=108 AND putaway=1')->order('id desc')->select();
        $result['thinkpad'] = $this->tableGoods()->where('brand=109 AND putaway=1')->order('id desc')->select();
        $result['camera'] = $this->tableGoods()->where('brand=125 AND putaway=1')->order('id desc')->select();
        $result['url'] = U('Myapp://news/news_list');
        $result = array_merge($this->thisGoodsCategory, $result);
        return $result;
    }
}