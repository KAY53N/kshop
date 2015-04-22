<?php
/**
 * Created by PhpStorm.
 * User: xujiantao
 * Date: 13-11-19
 * Time: 20:58
 */
class RegisterModel extends BaseModel
{
    public function getUserCheckStatus($condition)
    {
        return $this->tableUser()->where($condition)->select();
    }

    public function getRigisterUser($saveData)
    {
        $result['status'] = false;
        if($userid = $this->tableUser()->add($saveData))
        {
            $cartCondition['user_id'] = array('eq', $userid);
            $result['user_id'] = $userid;
            $result['cart_num'] = count($this->tableCart()->where($cartCondition)->field('id')->select());
            $result['status'] = true;
            $result['userId'] = $userid;
        }
        return $result;
    }

    public function getSaveUserInfo($post)
    {
        $status = false;
        if($this->tableUserinfo()->create())
        {
            if($this->tableUserinfo()->add($post))
            {
                $status = true;
            }
        }
        return $status;
    }
}