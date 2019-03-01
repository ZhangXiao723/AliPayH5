<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

$arr=$_POST;

require_once("config.php");
require_once 'wappay/service/AlipayTradeService.php';

$alipaySevice = new AlipayTradeService($config); 
$alipaySevice->writeLog(var_export($_POST,true));
$result = $alipaySevice->check($arr);

/* 实际验证过程建议商户添加以下校验。
1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
4、验证app_id是否为该商户本身。
*/
if($result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

    //验证金额及参数
    $trade_status = $_POST['trade_status'];
    if($trade_status != 'TRADE_SUCCESS') {
        die("fail");
    }

	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号
	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];

    // 获取网站订单号
    $item_id = end(explode("_",$out_trade_no));

    if(!empty($trade_no) && !empty($item_id)){
        //判断该笔订单是否在商户网站中已经做过处理
        //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
        //如果有做过处理，不执行商户的业务程序
        
        //开启一个事务
        //再次写您的业务逻辑，一般是支付成功之后的逻辑处理
        date_default_timezone_set('PRC');

        //查找订单是是否存在
        $sql = "SELECT * FROM deposits where id = $item_id";
        $info = $db->getLine($sql);
        if(empty($info)) {
            die("fail");
        }

        if($info['total'] != $_POST['total_amount'] * 100) {

        }

        //判断订单的状态是否是0，如果是0，就做下一步的处理
        if($info['pay_status'] == 0){
            if($ret) {
                die("success");
            }
        }
        else{
            die("success");
        }
    }
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
		
}else {
    //验证失败
    echo "fail";	//请不要修改或删除

}

?>

