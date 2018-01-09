<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
define('EARTH_RADIUS', 6378.137);//地球半径
define('PI', 3.1415926);
define('AK','9e9v0Vdhe4HyFS2UCrFcWfAp1Vefsuoe');
/**
 * 计算两组经纬度坐标 之间的距离
 * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2； len_type （1:m or 2:km);
 * return m or km
 */
function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 2, $decimal = 2)
{
    $radLat1 = $lat1 * PI / 180.0;
    $radLat2 = $lat2 * PI / 180.0;
    $a       = $radLat1 - $radLat2;
    $b       = ($lng1 * PI / 180.0) - ($lng2 * PI / 180.0);
    $s       = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
    $s       = $s * EARTH_RADIUS;
    $s       = round($s * 1000);
    if ($len_type > 1)
    {
        $s /= 1000;
    }
    return round($s, $decimal);
}
// lng lat
// 121.48789949,31.24916171
// 121.2093740000,31.2730340000
/**
 * 接口获得数据
 */
function http_post_json($url,$jsonstr){
    //init
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_URL,$url);	 
    curl_setopt($ch,CURLOPT_POSTFIELDS,$jsonstr);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_TIMEOUT,60);
    curl_setopt($ch,CURLOPT_HTTPHEADER,array(
        'Content-Type:application/json',
        'Content_Length:'.strlen($jsonstr)));
    //exec
    $resp=curl_exec($ch);	
    $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
    //close
    curl_close($ch);
    return array($httpcode,$resp);
}

/**
 * PHP stdClass Object转array
 */
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    }
    if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}
 function getInformation($latitude, $longitude)
{
    $url = "http://api.map.baidu.com/geocoder/v2/?ak=".AK."&callback=renderReverse&location=".$latitude.",".$longitude."&output=json";
    $str = file_get_contents($url);
    $str = substr($str,strpos($str,'{'));
    $str = substr($str,0,strlen($str)-1);
    $res=json_decode($str,true);
    $GI['addrName']= $res["result"]["sematic_description"];
    $GI['address'] = $res["result"]["formatted_address"];
    $GI['city']    = $res["result"]["addressComponent"]["city"];
    $GI['district']= $res["result"]["addressComponent"]["district"];
    $GI['province']= $res["result"]["addressComponent"]["province"];
    return $GI;
}
/**
 * 导出excel 表格
 * @param string $fileName 文件名称
 * @param array $headArr		标题名数组
 * @param array $data				信息数组
 */
function create_excel_general($fileName, $headArr, $data, $sheet_title = 'Sheet1') {
    if (empty($data) || !is_array($data)) {
        die("导入数据不能为空！");
    }
    //设置文件名
    if (empty($fileName)) {
        exit;
    }

   //引入PHPExcel类
   /* vendor('PHPExcel');
    vendor('PHPExcel.IOFactory');
    vendor('PHPExcel.Reader.Excel2007');
    vendor('PHPExcel.Reader.Excel5');*/

    $date = date("Y_m_d", time());
    $fileName .= "_{$date}.xls";
    $fileName = iconv("utf-8", "gb2312", $fileName);

    //创建新的PHPExcel对象
    $objPHPExcel = new \PHPExcel();
    //设置活动单指数到第一个表,所以Excel打开这是第一个表
    $objPHPExcel->setActiveSheetIndex(0);
    //当前工作薄
    $objActSheet = $objPHPExcel->getActiveSheet();
    //重命名表
    $objActSheet->setTitle($sheet_title);

    //设置表头
    foreach ($headArr as $k => $v) {
        $colum = \PHPExcel_Cell::stringFromColumnIndex($k); //索引获取字符
        $objActSheet->setCellValue($colum . '1', $v);
    }

    $row = 2;
    foreach ($data as $key => $rows) {
        //行写入
        $span = 0;
        foreach ($rows as $value) {
// 列写入
            $j = \PHPExcel_Cell::stringFromColumnIndex($span);
            $objActSheet->setCellValue($j . $row, ' ' . $value);
            $span++;
        }
        $row++;
    }

    //设置单元格宽度
    foreach ($headArr as $k => $v) {
        $colum = \PHPExcel_Cell::stringFromColumnIndex($k);
        $objActSheet->getColumnDimension($colum)->setWidth(20);
    }

    $colum_num = count($headArr);
    $last_column = \PHPExcel_Cell::stringFromColumnIndex($colum_num - 1);
    $last_column_char = $last_column . '1';

    //设置填充的样式和背景色
    $objActSheet->getStyle('A1:' . $last_column_char)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
    $objActSheet->getStyle('A1:' . $last_column_char)->getFill()->getStartColor()->setARGB('C5003EAA');
    $objActSheet->getStyle('A1:' . $last_column_char)->getFont()->getColor()->setRGB('ffffff'); //加粗
    $objActSheet->getStyle('A1:' . $last_column_char)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); //对齐设置

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header('Cache-Control: max-age=0');
    $objWriter->save('php://output'); //文件通过浏览器下载
    exit;
}
/*
 *生成二维码
 */
function buildQrcode($text){
    if($text){
        $qrcode = (new \app\wx\base\Membercard())->buildQRcode($text);
        echo $qrcode;
    }
}

function getDateweek($day){
	$time = strtotime($day);
	$year = date('Y',$time);
	$month = intval(date('m',$time));
	$da = intval(date('d',$time));
	$week = date('w',$time);
	switch($week){
		case 0:
			$wee = '周日';
			break;
		case 1:
			$wee = '周一';
			break;
		case 2:
			$wee = '周二';
			break;
		case 3:
			$wee = '周三';
			break;
		case 4:
			$wee = '周四';
			break;
		case 5:
			$wee = '周五';
			break;
		case 6:
			$wee = '周六';
			break;
	}
	$rel = $year.'年'.$month.'月'.$da.'日 '.$wee;
	return $rel;
}
