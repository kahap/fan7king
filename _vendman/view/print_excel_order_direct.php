<?php 
function __autoload($ClassName){
	include_once('../cls/'.$ClassName.".cls.php");
}

require_once('../cfg/cfg.inc.php');

require_once('../lib/function.php');

$or = new Orders();
$sr = new Status_Record();
$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$mem = new Member();

$allOrData = array();
$allSupData = array();

foreach($_GET as $key=>$value){
	$$key = $value;
}
if(isset($orSupDateFrom)){
	$allOrData = $or->getAllOrderOnSupPageWithPayStatus($action,$status, $supno, $orDateFrom, $orDateTo, $orSupDateFrom, $orSupDateTo,$orPaySuccess);
}else{
	$allOrData = $or->getAllOrderOnSupPageWithPayStatusNoSupOrder($action,$status, $supno, $orDateFrom, $orDateTo,$orPaySuccess);
}




?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Nowait管理後台</title>
<style>
table{
	border-collapse:collapse;
}
table th, table td{
	border:1px solid #000;
	padding:5px;
}
</style>
</head>
<body>
	<table>
		<tr>
			<td colspan="28">供應商　【直購訂單】</td>
		</tr>
		<tr>
			<?php if($supno == "all"){ ?>
				<td colspan="28">供應商：全部</td>
			<?php 
				}else{
					$supCurData = $sup->getOneSupplierByNo($supno);
			?>
				<td colspan="28">供應商：<?php echo $supCurData[0]["supName"]; ?></td>
			<?php } ?>
		</tr>
		<tr>
			<td colspan="28">訂單日期：<?php echo $orDateFrom; ?> 至 <?php echo $orDateTo; ?></td>
		</tr>
		<tr>
			<td colspan="28"> </td>
		</tr>
		<tr>
			<th colspan="19" rowspan="2">訂購單(系統自動帶入)</th><th colspan="6">廠商填寫</th>
			<th colspan="2">EC確認</th><th rowspan="2">供應商</th>
		</tr>
		<tr>
			<th colspan="3">出貨</th><th>簽收</th><th>換貨</th>
			<th>退貨</th><th>出貨中</th><th>撥款</th>
		</tr>
		<tr>
			<th>訂單狀態</th><th>序號</th><th>訂單編號</th><th>訂單日期</th>
			<th>商品編號</th><th>商品名稱</th><th>商品規格</th><th>訂購人</th>
			<th>電話</th><th>手機</th><th>地址</th><th>收貨人</th>
			<th>電話</th><th>手機</th><th>地址</th><th>收貨備註</th>
			<th>公司抬頭</th><th>統一編號</th><th>供貨價</th><th>出貨日期</th>
			<th>宅配公司</th><th>宅配單號</th><th>到貨日期</th><th>完成日期</th>
			<th>完成日期</th><th>訂貨日期</th><th>撥款日期</th><th>供應商名稱</th>
		</tr>
		<?php 
		foreach($allOrData as $key=>$value){
			$or->changeToReadable($value,0);
			$pmData = $pm->getOnePMByNo($value["pmNo"]);
			$supData = $sup->getOneSupplierByNo($value["supNo"]);
			$memData = $mem->getOneMemberByNo($value["memNo"]);
			$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
			//正在使用的商品上架
			$curPmInOr = $pm->getOnePMBySupAndPro($pmData[0]["proNo"], $value["supNo"]);
		?>
			<tr>
				<td><?php echo $value["orStatus"]; ?></td>
				<td><?php echo $value["orNo"]; ?></td>
				<td><?php echo $value["orCaseNo"]; ?></td>
				<td><?php echo $value["orDate"]; ?></td>
				<td><?php echo $proData[0]["proNo"]; ?></td>
				<td><?php echo $proData[0]["proName"]; ?></td>
				<td><?php echo $value["orProSpec"]; ?></td>
				<td><?php echo $memData[0]["memName"]; ?></td>
				<td><?php echo $memData[0]["memPhone"]; ?></td>
				<td><?php echo $memData[0]["memCell"]; ?></td>
				<td><?php echo $memData[0]["memAddr"]; ?></td>
				<td><?php echo $value["orReceiveName"]; ?></td>
				<td><?php echo $value["orReceivePhone"]; ?></td>
				<td><?php echo $value["orReceiveCell"]; ?></td>
				<td><?php echo $value["orReceiveAddr"]; ?></td>
				<td><?php echo $value["orReceiveComment"]; ?></td>
				<td><?php echo $value["orBusinessNumTitle"]; ?></td>
				<td><?php echo $value["orBusinessNumNumber"]; ?></td>
				<td><?php echo number_format($value["orSupPrice"]); ?></td>
				<td><?php echo $value["orHandleSupOutDate"]; ?></td>
				<td><?php echo $value["orHandleTransportComp"]; ?></td>
				<td><?php echo $value["orHandleTransportSerialNum"]; ?></td>
				<td><?php echo $value["orHandleGetFromSupDate"]; ?></td>
				<td><?php echo $value["orHandleChangeProDate"]; ?></td>
				<td><?php echo $value["orHandleRefundDate"]; ?></td>
				<td><?php echo $value["orHandleOrderFromSupDate"]; ?></td>
				<td><?php echo $value["orHandlePaySupDate"]; ?></td>
				<td><?php echo $supData[0]["supName"]; ?></td>
			</tr>
		<?php 
		} 
		?>
	</table>
</body>
</html>