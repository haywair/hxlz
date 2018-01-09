<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/26 0026
 * Time: 11:03
 */
namespace app\api\controller;

use app\admin\model\Recharge_money_category_tab;

class Card extends Base{
    /**
     * 查询充值金额类型种类
     */
    public function getRechargeCategorys(){
        $category_model = new Recharge_money_category_tab();
        $condition = [];
        $condition['AVAILABLE_FLG'] = 1;
        $list = $category_model->getRechargeListNP($condition);
        if(!empty($list)){
            return '{"code":"200","data":'.json_encode($list).'}';
        }else{
            return '{"code":"400","Msg":"unexpected error"}';
        }
    }
}