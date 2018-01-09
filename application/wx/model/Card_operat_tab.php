<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7
 * Time: 14:42
 */

namespace app\wx\model;


class Card_operat_tab extends Base
{
    public function cardOperatAdd($data){
        return $this->data($data)->save();
    }
    /**
     * 会员充值记录
     */
    public function getCardOperateList($condition,$limit=""){
        return $this->where($condition)->order('CREATE_DATE DESC')->select();
    }
    /**
     * 更新充值卡操作记录
     */
    public function updateCardInfoByCon($data,$condition){
        return $this->where($condition)->update($data);
    }
}