<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 16:57
 */
class LoginModel extends BaseModel
{
    public function loginCheck($condition)
    {
        if($this->tableUser()->create())
        {
            if($info = $this->tableUser()->where($condition)->find())
            {
                $cartCondition['user_id'] = array('eq', Cookie::get('user_id'));
                $info['cart_num'] = count($this->tableCart()->where($cartCondition)->field('id')->select());
            }
        }
        return $info;
    }

    public function forgetPassword($condition)
    {
        $result['info'] = $this->tableUser()->where($condition)->find();
        if(!empty($result))
        {
            $result['new_pass'] = mt_rand(100000, 10000000);
            $saveData['password'] = md5($result['new_pass']);
            $saveCondition['id'] = array('eq', $result['info']['id']);
            $this->tableUser()->where($saveCondition)->save($saveData);
        }

        return $result;
    }
}