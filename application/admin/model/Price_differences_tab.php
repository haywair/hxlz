<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/3 0003
 * Time: 16:20
 */
namespace app\admin\model;


class Price_differences_tab extends Base
{
    public function priceDifAdd($data){
        return $this->data($data)->save();
    }

    /**
     * 差价单列表
     */
    public function getPriceDiffList($condition){
        $list = $this->alias('a')
              -> join('ORDER_TAB b','a.ORDER_CD = b.ORDER_CD','LEFT')
              -> field('a.*,b.USER_NAME,b.STORE_NAME')
              -> where($condition)
              ->paginate (15,false);
        return $list;
    }
    /**
     * 获取差价单数量
     */
    public function getPriceDiffNum($condition){
        return $this->where($condition)->count();
    }
    /**
     * 更新差价单
     */
    public function priceDiffUpdate($data,$diff_price_no){
        return $this->where(['diff_price_no'=>$diff_price_no])->update($data);
    }

    /**
     * 获取差价单详情
     *
     */
    public function getPriceDiffInfoById($id){
        $info =  $this->alias('a')
             -> join('ORDER_TAB b','a.ORDER_CD = b.ORDER_CD','LEFT')
             -> field('a.*,b.USER_NAME,b.STORE_NAME')
             ->where(['DIFF_PRICE_NO'=>$id])
             ->find();
        return $info;
    }
    
    public function getPriceListByStore($storeId){
        return $this->where(['STORE_CD'=>$storeId])->select();
    }
    public function delPriceOne($priceId){
        $data = [
            'AVAILABLE_FLG'=>0
        ];
        return $this->where(['DIFF_PRICE_NO'=>$priceId])->update($data);
    }

}